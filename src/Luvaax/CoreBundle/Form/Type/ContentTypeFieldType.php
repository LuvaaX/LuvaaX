<?php

namespace Luvaax\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Luvaax\CoreBundle\Event\FieldTypeCollector;
use Luvaax\CoreBundle\Model\ContentType;

class ContentTypeFieldType extends AbstractType
{
    /**
     * @var FieldTypeCollector
     */
    private $fieldTypeCollector;

    /**
     * @param FieldTypeCollector $fieldTypeCollector
     */
    public function __construct(FieldTypeCollector $fieldTypeCollector)
    {
        $this->fieldTypeCollector = $fieldTypeCollector;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = $this->fieldTypeCollector->getFieldTypes();
        $readableChoices = [];

        foreach ($choices as $key => $choice) {
            $readableChoices[$choice->getName()] = $key;
        }

        $builder
            ->add('name', null, [
                'attr' => ['placeholder' => 'Only alpha characters and spaces']
            ])
            ->add('fieldType', ChoiceType::class, [
                'required' => true,
                'choices' => $readableChoices
            ])
            ->add('required', CheckboxType::class)
            ->add('showList', CheckboxType::class)
            ->add('hidden', HiddenType::class, ['mapped' => false]) // used to get form value from the entity generator
        ;

        $builder->get('fieldType')
            ->addModelTransformer(new CallbackTransformer(function ($originalFieldType) use ($readableChoices) {
                if ($originalFieldType) {
                    return $readableChoices[$originalFieldType->getName()];
                }
            }, function ($submittedFieldType) use ($choices) {
                if ($submittedFieldType) {
                    return $choices[$submittedFieldType];
                }
            }));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($readableChoices) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data) {
                if ($data->getName() == ContentType::TITLE_FIELD) {
                    $form->remove('name');
                    $form->remove('fieldType');
                    $form->remove('required');
                    $form->remove('showList');

                    $form
                        ->add('name', null, [
                            'attr' => ['placeholder' => 'Only alpha characters and spaces'],
                            'disabled' => true,
                        ])
                        ->add('fieldType', ChoiceType::class, [
                            'required' => true,
                            'choices' => $readableChoices,
                            'disabled' => true,
                        ])
                        ->add('required', CheckboxType::class, [
                            'disabled' => true
                        ])
                        ->add('showList', CheckboxType::class, [
                            'disabled' => true
                        ])
                    ;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Luvaax\CoreBundle\Model\ContentTypeField'
        ]);
    }
}
