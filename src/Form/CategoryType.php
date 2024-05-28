<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('slug', TextType::class, [
            'attr' => [
                'class' => 'invisible'
            ],
            'required' => false,
            'label_attr' => [
                'class' => 'invisible'],
           ])
           ->add('isActive', TextType::class, [
            'attr' => [
                'class' => 'invisible'
            ],
            'required' => false,
            'label_attr' => [
                'class' => 'invisible'],
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
