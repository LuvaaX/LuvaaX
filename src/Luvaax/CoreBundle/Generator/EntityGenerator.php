<?php

namespace Luvaax\CoreBundle\Generator;
use Luvaax\GeneratorBundle\File\ClassGenerator;
use Luvaax\GeneratorBundle\File\Model\ClassGenerator\ClassModel;
use Luvaax\GeneratorBundle\File\Model\ClassGenerator\PropertyModel;
use Luvaax\CoreBundle\Model\ContentType;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Yaml\Yaml;

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
     * @param ClassGenerator $generator
     * @param string         $rootDir
     * @param array          $generatorConfiguration
     */
    public function __construct(ClassGenerator $generator, $rootDir, array $generatorConfiguration)
    {
        $this->generator = $generator;
        $this->rootDir = $rootDir;
        $this->generatorConfiguration = $generatorConfiguration;
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
        $easyAdminConfig = $this->rootDir . '/config/bundles/easy_admin.yml';

        $content = Yaml::parse(file_get_contents($easyAdminConfig));

        $found = false;
        foreach ($content['easy_admin']['entities'] as $entityName => $entityValue) {
            if ($entityName == $contentType->getName()) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $content['easy_admin']['entities'][$contentType->getName()] = [
                'class' => $classPath,
                'label' => $contentType->getName()
            ];
        }

        $found = false;
        foreach ($content['easy_admin']['design']['menu'] as &$menuItem) {
            if (isset($menuItem['type']) && $menuItem['type'] == 'content_type') {
                if (!isset($menuItem['children'])) {
                    continue;
                }

                foreach ($menuItem['children'] as $child) {
                    if (isset($child['entity']) && $child['entity'] == $contentType->getName()) {
                        $found = true;
                        break;
                    }
                }

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

        if (!$found) {
            $content['easy_admin']['design']['menu'][] = [
                'entity' => $contentType->getName(),
                'label'  => $contentType->getName()
            ];
        }

        file_put_contents($easyAdminConfig, Yaml::dump($content, 10));
    }
}
