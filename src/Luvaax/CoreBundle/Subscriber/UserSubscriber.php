<?php

namespace Luvaax\CoreBundle\Subscriber;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Luvaax\CoreBundle\Model\UserInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_persist' => ['encodePassword'],
            'easy_admin.pre_update' => ['encodePassword']
        ];
    }

    /**
     * Encode user password
     *
     * @param  GenericEvent $event
     */
    public function encodePassword(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof UserInterface) || !$entity->getPlainPassword()) {
            return;
        }

        $encodedPassword = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encodedPassword);

        $event['entity'] = $entity;
    }
}
