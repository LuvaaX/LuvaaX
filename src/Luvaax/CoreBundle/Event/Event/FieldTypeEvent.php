<?php

namespace Luvaax\CoreBundle\Event\Event;

use Symfony\Component\EventDispatcher\Event;
use Luvaax\CoreBundle\Model\FieldType;

/**
 * Event to get all field types "luvaax.core.register_field_type"
 */
class FieldTypeEvent extends Event
{
    /**
     * Contains all field types
     *
     * @var array
     */
    private $fieldTypes = [];

    /**
     * Registers a new field type
     *
     * @param  FieldType[]  $fields
     */
    public function register(array $fields)
    {
        foreach ($fields as $field) {
            /** @var FieldType $field */
            $this->fieldTypes[$field->getFieldType()] = $field;
        }
    }

    /**
     * Returns all field types
     *
     * @return FieldType[]
     */
    public function getFieldTypes()
    {
        return $this->fieldTypes;
    }
}
