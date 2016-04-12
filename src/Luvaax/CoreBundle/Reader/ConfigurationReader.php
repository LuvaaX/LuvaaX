<?php

namespace Luvaax\CoreBundle\Reader;

use Symfony\Component\Yaml\Yaml;

class ConfigurationReader
{
    const CONFIG_EASY_ADMIN = 'easy_admin';

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var string
     */
    protected $bundleConfigPath;

    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
        $this->bundleConfigPath = $this->rootDir . '/config/bundles/';
    }

    /**
     * Returns file_name.yml file content
     *
     * @param string $fileName File name of the config file to parse in "bundles"
     *
     * @return array
     */
    public function getContent($fileName)
    {
        if (!isset($this->content[$fileName])) {
            $this->content[$fileName] = Yaml::parse(file_get_contents($this->bundleConfigPath . $fileName . '.yml'));
        }

        return $this->content[$fileName];
    }

    /**
     * Save the content in file_name.yml file
     *
     * @param  string $fileName File name of the config file to write in "bundles"
     * @param  array  $content
     */
    public function saveContent($fileName, array $content)
    {
        $this->content[$fileName] = $content;

        file_put_contents($this->bundleConfigPath . $fileName . '.yml', Yaml::dump($content, 10));
    }
}
