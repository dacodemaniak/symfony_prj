<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use BlogBundle\Form\CommentaireType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use BlogBundle\Entity\Categorie;

class BlogType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('date', DateTimeType::class, array("label" => "Date de création"))
        	->add('titre', TextType::class, array("required" => true), array("attr" => array("class" => "form-control")))
        	->add('auteur', TextType::class, array("required" => true))
        	->add('contenu', TextareaType::class, array("required" => true))
        	->add('publication', CheckboxType::class)
        	->add('vues', NumberType::class)
        	->add("categories", EntityType::class,array(
        			"class" => Categorie::class,
        			"choice_label" => "libelle",
        			"multiple" => true,
        			"expanded" => true
        			/**
        			 * Si multiple = true, expanded = true affiche des boîtes à cocher
        			 * 
        			 */
        	))
        	->add("commentaires", CollectionType::class,
        			array("entry_type" => CommentaireType::class,
        					"allow_add" => true
        	))
        	->add("file", FileType::class, array(
        		"label" => "Image",
        		"attr" => array(
        			"accept" => "image/jpeg, image/png, image/gif"
        		)
        		
        	));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Blog'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_blog';
    }


}
