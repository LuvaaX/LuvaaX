<?php

namespace Luvaax\CoreBundle\Generator;

use Luvaax\GeneratorBundle\File\ClassGenerator;
use Luvaax\GeneratorBundle\File\Model\ClassGenerator\ClassModel;
use Luvaax\GeneratorBundle\File\Model\ClassGenerator\PropertyModel;
use Luvaax\CoreBundle\Model\ContentType;
use Luvaax\CoreBundle\Model\ContentTypeField;
use Luvaax\CoreBundle\Reader\ConfigurationReader;
use Luvaax\CoreBundle\Helper\CommandCreator;
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
     * @param ClassGenerator $generator
     * @param ConfigurationReader $configurationReader
     * @param string         $rootDir
     * @param array          $generatorConfiguration
     * @param CommandCreator $commandCreator
     */
    public function __construct(
        ClassGenerator $generator,
        ConfigurationReader $configurationReader,
        $rootDir,
        array $generatorConfiguration,
        CommandCreator $commandCreator
    ) {
        $this->generator = $generator;
        $this->configurationReader = $configurationReader;
        $this->rootDir = $rootDir;
        $this->generatorConfiguration = $generatorConfiguration;
        $this->commandCreator = $commandCreator;
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

        $filePath = $path . $contentType->getName() . '.php';
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
            ->setName($contentType->getName())
            ->setNamespace($this->generatorConfiguration['namespace'])
            ->addUse('Doctrine\ORM\Mapping as ORM')
            ->addUse('Symfony\Component\Validator\Constraints as Assert')
            ->addAnnotation('@ORM\Table')
            ->addAnnotation('@ORM\Entity')
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

        foreach ($contentType->getFields() as $field) {
            /** @var $field ContentTypeField */
            $property = new PropertyModel();
            $property
                ->setName($field->getName())
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
        $classPath = $this->generatorConfiguration['namespace'] . '\\' . $contentType->getName();

        $content = $this->configurationReader->getContent(ConfigurationReader::CONFIG_EASY_ADMIN);

        // Create the entry in "entities" if the entity does not exists
        $found = $this->entityExists($contentType->getName());
        if (!$found) {
            $content['easy_admin']['entities'][$contentType->getName()] = [
                'class' => $classPath,
                'label' => $contentType->getName()
            ];
        }

        // List fields
        foreach ($contentType->getFields() as $field) {
            /** @var $field ContentTypeField */
            if (!$field->getShowList()) {
                continue;
            }

            if (!isset($content['easy_admin']['entities'][$contentType->getName()]['list'])) {
                $content['easy_admin']['entities'][$contentType->getName()]['list'] = [];
            }

            if (!isset($content['easy_admin']['entities'][$contentType->getName()]['list']['fields'])) {
                $content['easy_admin']['entities'][$contentType->getName()]['list']['fields'] = [];
            }

            $content['easy_admin']['entities'][$contentType->getName()]['list']['fields'][] = $field->getName();
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
                    if (isset($child['entity']) && $child['entity'] == $contentType->getName()) {
                        $found = true;
                        break;
                    }
                }

                // Not found, let's create it
                if (!$found) {
                    $found = true;
                    $menuItem['children'][] = [
                        'entity' => $contentType->getName(),
                        'label'  => $contentType->getName()
                    ];
                }

                break;
            }
        }

        // Create the menu entry to the root menu if no content_type found
        if (!$found) {
            $content['easy_admin']['design']['menu'][] = [
                'entity' => $contentType->getName(),
                'label'  => $contentType->getName()
            ];
        }

        // Save it
        $this->configurationReader->saveContent(ConfigurationReader::CONFIG_EASY_ADMIN, $content);

        // Update doctrine
        $this->commandCreator->execute('doctrine:schema:update', ['--force' => true]);
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
}
