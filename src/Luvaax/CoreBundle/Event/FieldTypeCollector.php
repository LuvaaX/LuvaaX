<?php

namespace Luvaax\CoreBundle\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Luvaax\CoreBundle\Event\Event\FieldTypeEvent;
use Luvaax\CoreBundle\Model\FieldType;

class FieldTypeCollector
{
    const EVENT_REGISTER = 'luvaax.core.register_field_type';

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Send "luvaax.core.register_field_type" event to get all field types available
     *
     * @return array
     */
    public function getFieldTypes()
    {
        $event = new FieldTypeEvent();

        $this->eventDispatcher->dispatch(self::EVENT_REGISTER, $event);

        return $event->getFieldTypes();
    }

    /**
     * Get a field type that match the given type
     *
     * @param  string $type Field type (class namespace)
     *
     * @throws \InvalidArgumentException if type not found
     * @return FieldType
     */
    public function getFieldType($type)
    {
        $fieldTypes = $this->getFieldTypes();

        foreach ($fieldTypes as $fieldType) {
            /** @var $fieldType FieldType */
            if ($fieldType->getFieldType() == $type) {
                return $fieldType;
            }
        }

        throw new \InvalidArgumentException(sprintf('Field type %s not found', $type));
    }
}
