<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('birthdate', null, [
                'widget' => 'single_text',
            ])
            ->add('active')
            ->add('employed_since', null, [
                'widget' => 'single_text',
            ])
            ->add('employed_until', null, [
                'widget' => 'single_text',
            ])
            ->add('salary')
            ->add('superieur', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'id',
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
