<?php

namespace Luvaax\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Luvaax\CoreBundle\Security\Model\RoleManagerInterface;

/**
 * Role selector
 * ChoiceType with all roles in security.yml in the configuration
 */
class RoleSelectorType extends AbstractType
{
    /**
     * @var RoleManagerInterface
     */
    private $roleManager;

    /**
     * @param RoleManagerInterface $roleManager
     */
    public function __construct(RoleManagerInterface $roleManager)
    {
        $this->roleManager = $roleManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->roleManager->getRoles()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
