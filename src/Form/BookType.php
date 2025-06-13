<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Format;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('synopsis', null, [
                'label' => 'Synopsis',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('release_date', null, [
                'label' => 'Date de sortie',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('fichier-image', FileType::class, [
                'label' => 'Photo de couverture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '200k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Sélectionnez un fichier JPEG ou PNG de poids inférieur à 200Ko',
                    ])
                ]
            ])
            ->add('price', null, [
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégories thèmes',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => Author::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('format', EntityType::class, [
                'label' => 'Formats de livre',
                'class' => Format::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
