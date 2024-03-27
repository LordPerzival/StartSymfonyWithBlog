<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, ['label' => 'Titre', 'required' => true])
        ->add('description', TextType::class, ['label' => 'Description', 'required' => true])
        ->add('content', TextareaType::class, ['label' => 'Contenu', 'required' => true])
        ->add('category',EntityType::class,[
            'class' => Category::class,
            'label' => 'Categorie',
            'choice_label' => function ($title) {
                return $title->getTitle();
            }
        ])
        ->add('image',FileType::class,['label' => 'Image', 'data_class' => null, 'required' => true, 'constraints' => [
            new File([
                'maxSize' => '1024k',
                'mimeTypes' => [
                    'image/*',
                ],
                'mimeTypesMessage' => 'Veuillez selectonner une Image valide',
            ])
        ],])
        ->add('author', TextType::class, ['label' => 'Auteur', 'required' => true,])
        ->add('date', DateType::class, ['label' => 'Date', 'required' => true,])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
