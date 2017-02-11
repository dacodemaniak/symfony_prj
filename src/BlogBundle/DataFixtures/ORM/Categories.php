<?php
/**
 * \BlogBundle\DataFixtures\ORM\Categories.php Service de définition de données
 * dans la table "categorie"
 * ATTENTION : doctrine:fixtures:load vide la base de données avant de réinjecter
 */
namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Categorie;

class Categories implements FixtureInterface{
	
	public function load(ObjectManager $manager){
		/**
		 * Définition des valeurs à injecter dans la base de données
		 */
		$libelles = array(
			"Symfony",
			"Routes",
			"Contrôleur",
			"Doctrine",
			"Formulaires",
			"Twig"
		);
		
		$descriptions = array(
			"Framework de développement PHP",
			"Gestionnaire de routes évolués et paramétrable",
			"Architecture MVC passant par des contrôleurs automatiquement chargés",
			"ORM Object Relational Mapper pour gérer les transactions dans la base de données",
			"Système évolué pour la gestion et le contrôle des formulaires utilisateur",
			"Moteur de gestion de templates orienté objet pour faciliter la publication"
		);
		
		/**
		 * Boucle sur le tableau principal...
		 */
		$fixture = array();
		
		for($i=0; $i < sizeof($libelles); $i++){
			$categorie = new Categorie;
			$categorie->setLibelle($libelles[$i]);
			$categorie->setDescription($descriptions[$i]);
			
			// On demande la persistence de l'objet
			$manager->persist($categorie);
			
			// L'objet Categorie a été créé et hydraté, on peut l'ajouter
			$fixture[] = $categorie;
		}
		
		// C'est bon... on peut enregistrer le tout
		$manager->flush();
		
	}
}

