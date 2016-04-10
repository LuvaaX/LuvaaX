<?php

namespace Luvaax\CoreBundle\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Luvaax\CoreBundle\Event\Event\FieldTypeEvent;

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
}
