<?php

namespace Luvaax\GeneratorBundle\File;
use Symfony\Bridge\Twig\TwigEngine;

/**
 * Permits to generate PHP class file
 */
class ClassGenerator
{
    /**
     * @var TwigEngine
     */
    private $twig;

    /**
     * @param TwigEngine $twig
     */
    public function __construct(TwigEngine $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Builds the class file's content
     *
     * @param  ClassModel $model Model to parse
     *
     * @return string content of the class
     */
    public function buildClass(ClassModel $model)
    {
        return $this->twig->render('LuvaaxGeneratorBundle:templates:php_class.php.twig', [
            'model' => $model
        ]);
    }
}
