<?php

namespace Luvaax\CoreBundle\Model;

use Luvaax\CoreBundle\Model\ValidationConstraint;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FieldType
{
    const MANY_TO_MANY = 'many_to_many';
    const ONE_TO_ONE = 'one_to_one';
    const ONE_TO_MANY = 'one_to_many';
    const MANY_TO_ONE = 'many_to_one';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $doctrineType;

    /**
     * Symfony form field type
     *
     * @var string
     */
    protected $fieldType;

    /**
     * Options to apply to the field
     *
     * @var array
     */
    protected $options = [];

    /**
     * @todo peekmo : Relations not supported for the moment
     *
     * @var string
     * @see consts
     */
    protected $relation;

    /**
     * @var array
     */
    protected $cascades = [];

    /**
     * @var ValidationConstraint[]
     */
    protected $constraints = [];

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
     * Get the value of Doctrine Type
     *
     * @return string
     */
    public function getDoctrineType()
    {
        return $this->doctrineType;
    }

    /**
     * Set the value of Doctrine Type
     *
     * @param string $doctrineType
     *
     * @return self
     */
    public function setDoctrineType($doctrineType)
    {
        $this->doctrineType = $doctrineType;

        return $this;
    }

    /**
     * Get the value of Required
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set the value of Required
     *
     * @param boolean $required
     *
     * @return self
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get the value of Symfony form field type
     *
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set the value of Symfony form field type
     *
     * @param string $fieldType
     *
     * @return self
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get the value of Options to apply to the field
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the value of Options to apply to the field
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get the value of Relation
     *
     * @return string
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set the value of Relation
     *
     * @param string $relation
     *
     * @return self
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * Get the value of Target
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set the value of Target
     *
     * @param string $target
     *
     * @return self
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get the value of Cascades
     *
     * @return array
     */
    public function getCascades()
    {
        return $this->cascades;
    }

    /**
     * Set the value of Cascades
     *
     * @param array $cascades
     *
     * @return self
     */
    public function setCascades(array $cascades)
    {
        $this->cascades = $cascades;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFieldType();
    }

    /**
     * Get the value of Constraints
     *
     * @return ValidationConstraint[]
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Set the value of Constraints
     *
     * @param ValidationConstraint[] $constraints
     *
     * @return self
     */
    public function setConstraints(array $constraints)
    {
        $this->constraints = $constraints;

        return $this;
    }

}
