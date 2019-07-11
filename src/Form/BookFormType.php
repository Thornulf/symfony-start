<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookGenre;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre"
            ])
            ->add('price', TextType::class, [
                "label" => "Prix"
            ])
            ->add('author', EntityType::class, [
                "label" => "Auteur",
                "class" => Author::class,
                "choice_label" => "fullName"
            ])
            ->add('publisher', EntityType::class, [
                "label" => "Editeur",
                "class" => Publisher::class,
                "choice_label" => "name"
            ])
            ->add('bookGenre', EntityType::class, [
                "label" => "Genre",
                "class" => BookGenre::class,
                "choice_label" => "name"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
