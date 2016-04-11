<?php

namespace Luvaax\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'attr' => ['placeholder' => 'Only alpha characters and spaces']
            ])
            ->add('fields', CollectionType::class, [
                'entry_type' => ContentTypeFieldType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'required' => false
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Luvaax\CoreBundle\Model\ContentType'
        ]);
    }
}
