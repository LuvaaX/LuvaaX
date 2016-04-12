<?php

namespace Luvaax\CoreBundle\Model;

class ValidationConstraint
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $annotation;

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
     * Get the value of Annotation
     *
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Set the value of Annotation
     *
     * @param string $annotation
     *
     * @return self
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

}
