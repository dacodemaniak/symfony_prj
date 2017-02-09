<?php
/**
 * @name BlogAddType.php Extension de la classe BlogType pour la création d'articles
 */
namespace BlogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use BlogBundle\Form\BlogType;

class BlogAddType extends BlogType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		parent::buildForm($builder, $options); // Méthode de la classe parente pour générer tous les champs
		
		// Il ne reste plus qu'à "enlever" les champs qui ne nous intéresse pas pour un ajout
		$builder->remove("date")
			->remove("publication")
			->remove("vues")
			->remove("commentaires");
	}
	
	public function getName()
	{
		return 'blogbundle_blogaddtype';
	}
}