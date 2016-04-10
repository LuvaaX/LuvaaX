<?php

namespace Luvaax\CoreBundle\Event\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Luvaax\CoreBundle\Model\FieldType;
use Luvaax\CoreBundle\Event\FieldTypeCollector;
use Luvaax\CoreBundle\Event\Event\FieldTypeEvent;

/**
 * Register base types to the field type collection
 */
class FieldTypeSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FieldTypeCollector::EVENT_REGISTER => ['getFieldTypes']
        ];
    }

    /**
     * Returns base field types
     *
     * @return array
     */
    public function getFieldTypes(FieldTypeEvent $event)
    {
        $event->register([
            $this->buildTextField(),
            $this->buildTextareaField()
        ]);
    }

    /**
     * Builds a simple text field
     *
     * @return string
     */
    private function buildTextField()
    {
        $field = new FieldType();
        $field
            ->setName('text')
            ->setDoctrineType('string')
            ->setFieldType(TextType::class)
        ;

        return $field;
    }

    /**
     * Builds a simple text field
     *
     * @return string
     */
    private function buildTextareaField()
    {
        $field = new FieldType();
        $field
            ->setName('textarea')
            ->setDoctrineType('text')
            ->setFieldType(TextareaType::class)
        ;

        return $field;
    }
}
