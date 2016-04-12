<?php

namespace Luvaax\GeneratorBundle\File\Model\ClassGenerator;

class MethodModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $arguments = [];

    /**
     * @var string
     */
    protected $content;

    /**
     * @var boolean
     */
    protected $isStatic = false;

    /**
     * Get the value of Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of Arguments
     *
     * @return string[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set the value of Arguments
     *
     * @param string[] $arguments
     *
     * @return self
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get the value of Content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of Content
     *
     * @param string $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Adds a new argument to the class
     *
     * @param string $argument
     * @return self
     */
    public function addArgument($argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * Get the value of Is Static
     *
     * @return boolean
     */
    public function getIsStatic()
    {
        return $this->isStatic;
    }

    /**
     * Set the value of Is Static
     *
     * @param boolean $isStatic
     *
     * @return self
     */
    public function setIsStatic($isStatic)
    {
        $this->isStatic = $isStatic;

        return $this;
    }
}
