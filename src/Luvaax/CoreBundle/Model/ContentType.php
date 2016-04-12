<?php

namespace Luvaax\CoreBundle\Model;

use Luvaax\CoreBundle\Model\FieldType;
use Luvaax\CoreBundle\Model\ContentTypeField;
use Symfony\Component\Validator\Constraints as Assert;

class ContentType
{
    const TITLE_FIELD = 'title';
    
    /**
     * @var string
     * @Assert\Regex(pattern="/^[a-zA-Z ]+$/")
     */
    protected $name;

    /**
     * @var FieldType[]
     */
    protected $fields;

    /**
     * @var string
     */
    protected $nameFormatted;

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
     * Returns the name formatted for class name
     *
     * @return string
     */
    public function getNameFormatted()
    {
        if (!$this->nameFormatted) {
            $this->nameFormatted = implode('', explode(' ', ucwords(strtolower($this->name))));
        }

        return $this->nameFormatted;
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
        $this->name = ucfirst($name);

        return $this;
    }


    /**
     * Get the value of Fields
     *
     * @return FieldType[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the value of Fields
     *
     * @param ContentTypeField[] $fields
     *
     * @return self
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Add a field
     *
     * @param ContentTypeField $field
     */
    public function addField(ContentTypeField $field)
    {
        $this->fields[] = $field;

        return $this;
    }
}
