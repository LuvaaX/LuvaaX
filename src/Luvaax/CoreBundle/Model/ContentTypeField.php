<?php

namespace Luvaax\CoreBundle\Model;

use Luvaax\CoreBundle\Model\FieldType;

class ContentTypeField
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var FieldType
     */
    private $fieldType;

    /**
     * @var boolean
     */
    private $required = true;

    /**
     * Show the field in list mode
     * @var boolean
     */
    protected $showList = false;

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
     * Get the value of Field Type
     *
     * @return FieldType
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set the value of Field Type
     *
     * @param FieldType $fieldType
     *
     * @return self
     */
    public function setFieldType(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Returns doctrine/assert annotations related to this field type
     *
     * @return array
     */
    public function getAnnotations()
    {
        if (!$this->fieldType->getRelation()) {
            if ($this->fieldType->getFieldType() == TextType::class) {
                $annotations = [
                    sprintf(
                        '@ORM\Column(name="%s", type="%s", length="255" nullable=%s)',
                        $this->name,
                        $this->fieldType->getDoctrineType(),
                        $this->required ? 'false' : 'true'
                    ),
                ];
            } else {
                $annotations = [
                    sprintf(
                        '@ORM\Column(name="%s", type="%s", nullable=%s)',
                        $this->name,
                        $this->fieldType->getDoctrineType(),
                        $this->required ? 'false' : 'true'
                    ),
                ];
            }


            if ($this->required) {
                $annotations[] = '@Assert\NotBlank()';
            }

            foreach ($this->fieldType->getConstraints() as $constraint) {
                $annotations[] = $constraint->getAnnotation();
            }

            return $annotations;
        }
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
     * Get the value of Show the field in list mode
     *
     * @return boolean
     */
    public function getShowList()
    {
        return $this->showList;
    }

    /**
     * Set the value of Show the field in list mode
     *
     * @param boolean $showList
     *
     * @return self
     */
    public function setShowList($showList)
    {
        $this->showList = $showList;

        return $this;
    }

}
