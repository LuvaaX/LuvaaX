<?php

namespace Luvaax\CoreBundle\Generator;

use Luvaax\GeneratorBundle\File\ClassGenerator;
use Luvaax\GeneratorBundle\File\Model\ClassGenerator\ClassModel;
use Luvaax\GeneratorBundle\File\Model\ClassGenerator\MethodModel;
use Luvaax\GeneratorBundle\File\Model\ClassGenerator\PropertyModel;
use Luvaax\CoreBundle\Model\FieldType;
use Luvaax\CoreBundle\Model\ContentType;
use Luvaax\CoreBundle\Model\ContentTypeField;
use Luvaax\CoreBundle\Reader\ConfigurationReader;
use Luvaax\CoreBundle\Helper\CommandCreator;
use Luvaax\CoreBundle\Event\FieldTypeCollector;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class EntityGenerator
{
    /**
     * @var ClassGenerator
     */
    private $generator;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * Generator configuration (from luvaax_core config)
     *
     * @var array
     */
    private $generatorConfiguration;

    /**
     * @var ConfigurationReader
     */
    private $configurationReader;

    /**
     * @var CommandCreator
     */
    private $commandCreator;

    /**
     * @var FieldTypeCollector
     */
    private $fieldCollector;

    /**
     * @param ClassGenerator $generator
     * @param ConfigurationReader $configurationReader
     * @param string         $rootDir
     * @param array          $generatorConfiguration
     * @param CommandCreator $commandCreator
     * @param FieldTypeCollector $fieldCollector
     */
    public function __construct(
        ClassGenerator $generator,
        ConfigurationReader $configurationReader,
        $rootDir,
        array $generatorConfiguration,
        CommandCreator $commandCreator,
        FieldTypeCollector $fieldCollector
    ) {
        $this->generator = $generator;
        $this->configurationReader = $configurationReader;
        $this->rootDir = $rootDir;
        $this->generatorConfiguration = $generatorConfiguration;
        $this->commandCreator = $commandCreator;
        $this->fieldCollector = $fieldCollector;
    }

    /**
     * Creates a content type :
     *  - Generate entity file
     *  - Insert in configuration file
     *  - Update schema
     *
     * @param  ContentType $contentType
     */
    public function createContentType(ContentType $contentType)
    {
        $content = $this->generator->buildClass($this->createModel($contentType));

        $path = $this->rootDir . '/' . trim($this->generatorConfiguration['dest_dir'], '/') . '/';
        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf('Entity\'s destination directory %s not found', $path));
        }

        $filePath = $path . $contentType->getNameFormatted() . '.php';
        file_put_contents($filePath, $content);

        $this->updateEasyAdmin($contentType);
    }

    /**
     * Creates the class model to generate the file content
     *
     * @param  ContentType $contentType
     *
     * @return ClassModel
     */
    private function createModel(ContentType $contentType)
    {
        $model = new ClassModel();
        $model
            ->setName($contentType->getNameFormatted())
            ->setNamespace($this->generatorConfiguration['namespace'])
            ->addUse('Doctrine\ORM\Mapping as ORM')
            ->addUse('Symfony\Component\Validator\Constraints as Assert')
            ->addUse('Luvaax\CoreBundle\Model\CustomContentTypeInterface')
            ->addUse('Umanit\Bundle\TreeBundle\Model\TreeNodeInterface')
            ->addUse('Umanit\Bundle\TreeBundle\Model\TreeNodeTrait')
            ->addUse('Umanit\Bundle\TreeBundle\Model\SeoTrait')
            ->addUse('Umanit\Bundle\TreeBundle\Model\SeoInterface')
            ->addAnnotation('@ORM\Table')
            ->addAnnotation('@ORM\Entity')
            ->addTrait('SeoTrait')
            ->addTrait('TreeNodeTrait')
            ->addInterface('CustomContentTypeInterface')
            ->addInterface('SeoInterface')
            ->addInterface('TreeNodeInterface')
        ;

        // Adds $id automatically
        $id = new PropertyModel();
        $id
            ->setName('id')
            ->setType('int')
            ->setDescription('Primary key and identifier of the content type')
            ->addAnnotation('@ORM\Column(name="id", type="integer")')
            ->addAnnotation('@ORM\Id')
            ->addAnnotation('@ORM\GeneratedValue(strategy="AUTO")')
        ;

        $model->addProperty($id, true, true);

        $checkMethod = new MethodModel();
        $checkMethod
            ->setName('isAvailableForEditing')
            ->setIsStatic(true)
            ->setContent('      return true;')
        ;

        $model->addMethod($checkMethod);

        $treeNodeMethod = new MethodModel();
        $treeNodeMethod
            ->setName('getTreeNodeName')
            ->setContent('      return $this->' . ContentType::TITLE_FIELD . ';')
        ;

        $model->addMethod($treeNodeMethod);

        foreach ($contentType->getFields() as $field) {
            /** @var $field ContentTypeField */
            $property = new PropertyModel();
            $property
                ->setName($field->getNameFormatted())
                ->setAnnotations($field->getAnnotations());
            ;

            $model->addProperty($property, true, true);
        }

        return $model;
    }

    /**
     * Update easy admin bundle config to add the content type
     *
     * @param  ContentType $contentType
     */
    private function updateEasyAdmin(ContentType $contentType)
    {
        $classPath = $this->generatorConfiguration['namespace'] . '\\' . $contentType->getNameFormatted();

        $content = $this->configurationReader->getContent(ConfigurationReader::CONFIG_EASY_ADMIN);

        // Create the entry in "entities" if the entity does not exists
        $found = $this->entityExists($contentType->getNameFormatted());
        if (!$found) {
            $content['easy_admin']['entities'][$contentType->getNameFormatted()] = [
                'class' => $classPath,
                'label' => $contentType->getName()
            ];
        }

        // List fields
        foreach ($contentType->getFields() as $field) {
            /** @var $field ContentTypeField */
            if ($field->getShowList()) {
                $this->addFieldToConfig($content, 'list', $field, $contentType->getNameFormatted());
            }

            $this->addFieldToConfig($content, 'form', $field, $contentType->getNameFormatted());
        }

        $found = false;

        // Check if the menu entry already exists
        foreach ($content['easy_admin']['design']['menu'] as &$menuItem) {
            // If we have a 'content_type' menu type (which should contains all content types)
            if (isset($menuItem['type']) && $menuItem['type'] == 'content_type') {
                // No children entry = error
                if (!isset($menuItem['children'])) {
                    continue;
                }

                // Look in all children
                foreach ($menuItem['children'] as $child) {
                    if (isset($child['entity']) && $child['entity'] == $contentType->getNameFormatted()) {
                        $found = true;
                        break;
                    }
                }

                // Not found, let's create it
                if (!$found) {
                    $found = true;
                    $menuItem['children'][] = [
                        'entity' => $contentType->getNameFormatted(),
                        'label'  => $contentType->getName()
                    ];
                }

                break;
            }
        }

        // Create the menu entry to the root menu if no content_type found
        if (!$found) {
            $content['easy_admin']['design']['menu'][] = [
                'entity' => $contentType->getNameFormatted(),
                'label'  => $contentType->getName()
            ];
        }

        // Save it
        $this->configurationReader->saveContent(ConfigurationReader::CONFIG_EASY_ADMIN, $content);

        // Update doctrine
        $this->commandCreator->execute('doctrine:schema:update', ['--force' => true]);
    }

    /**
     * Adds a field to the given config
     *
     * @param array     $content Content to update
     * @param string    $type    Config type ('list', 'form', 'edit', 'new')
     * @param ContentTypeField $field   Field type
     * @param string    $contentTypeName
     */
    private function addFieldToConfig(&$content, $type, ContentTypeField $field, $contentTypeName)
    {
        if (!isset($content['easy_admin']['entities'][$contentTypeName][$type])) {
            $content['easy_admin']['entities'][$contentTypeName][$type] = [];
        }

        if (!isset($content['easy_admin']['entities'][$contentTypeName][$type]['fields'])) {
            $content['easy_admin']['entities'][$contentTypeName][$type]['fields'] = [];
        }

        // Type list, no fieldType
        if ($type == 'list') {
            $content['easy_admin']['entities'][$contentTypeName][$type]['fields'][] = [
                'label' => $field->getName(),
                'property' => $field->getNameFormatted()
            ];

            return;
        }

        // Type form, new, edit..
        $content['easy_admin']['entities'][$contentTypeName][$type]['fields'][] = [
            'label' => $field->getName(),
            'property' => $field->getNameFormatted(),
            'type' => $field->getFieldType()->getFieldType(),
            'required' => $field->getRequired()
        ];
    }

    /**
     * Does the entity exists or not ?
     *
     * @param  string $name Entity name
     *
     * @return bool
     */
    public function entityExists($name)
    {
        $content = $this->configurationReader->getContent(ConfigurationReader::CONFIG_EASY_ADMIN);

        foreach ($content['easy_admin']['entities'] as $entityName => $entityValue) {
            if ($entityName == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the content type from the given entity name
     *
     * @param  string $entity Entity to build
     *
     * @throws \InvalidArgumentException
     * @return ContentType
     */
    public function getEntityContentType($entity)
    {
        $content = $this->configurationReader->getContent(ConfigurationReader::CONFIG_EASY_ADMIN);

        if (
            !$this->entityExists($entity) ||
            !isset($content['easy_admin']['entities'][$entity]['form']) ||
            !isset($content['easy_admin']['entities'][$entity]['label'])
        ) {
            throw new \InvalidArgumentException(sprintf('Entity %s not found', $entity));
        }

        $form = $content['easy_admin']['entities'][$entity]['form'];

        $contentType = new ContentType();
        $contentType
            ->setName($content['easy_admin']['entities'][$entity]['label'])
        ;

        $fieldsList = [];
        if (
            isset($content['easy_admin']['entities'][$entity]['list']) &&
            isset($content['easy_admin']['entities'][$entity]['list']['fields'])
        ) {
            foreach ($content['easy_admin']['entities'][$entity]['list']['fields'] as $listField) {
                $fieldsList[] = $listField['property'];
            }
        }

        foreach ($form['fields'] as $field) {
            $contentTypeField = new ContentTypeField();
            $contentTypeField
                ->setName($field['label'])
                ->setFieldType($this->fieldCollector->getFieldType($field['type']))
                ->setRequired($field['required'])
                ->setShowList(in_array($field['property'], $fieldsList))
            ;

            $contentType->addField($contentTypeField);
        }

        return $contentType;
    }
}
