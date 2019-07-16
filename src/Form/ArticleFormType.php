<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\CustomFormTypes\TagFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre"])
            ->add('content', TextareaType::class, [
                "label" => "Contenu",
                "attr" => ["rows" => 10, "cols" => 25]
            ])
            /*
            ->add('author', EntityType::class, [
                "label" => "Auteur",
                "class" => Author::class,
                "choice_label" => "fullName"
            ])
            */
            ->add('author', AuthorFormType::class)
            ->add('tags', TagFormType::class, [
                'label' => 'tags'

            ])
            ->add('uploadedFile', FileType::class, [
                "data_class" => null,
                "required" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
