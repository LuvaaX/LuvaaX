<?php

namespace Luvaax\GeneratorBundle\File\Model\ClassGenerator;

class PropertyModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string[]
     */
    protected $annotations = [];

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
     * Get the value of Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of Type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of Description
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of Annotations
     *
     * @return string[]
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * Set the value of Annotations
     *
     * @param string[] $annotations
     *
     * @return self
     */
    public function setAnnotations(array $annotations)
    {
        $this->annotations = $annotations;

        return $this;
    }

    /**
     * Adds a new annotation to the property
     *
     * @param string $annotation
     * @return self
     */
    public function addAnnotation($annotation)
    {
        $this->annotations = $annotation;

        return $this;
    }
}
