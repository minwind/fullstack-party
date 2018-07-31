<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'choiceField',
                ChoiceType::class, array(
                    'label' => false,
                    'expanded' => true,
                    'multiple' => false,
                    'choices' => array(
                        'Open' => 'open',
                        'Closed' => 'closed'
                    ),
                    'data' => 'open'
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' => array('class' => 'hidden'),
                ]
            )
        ;
    }
}


