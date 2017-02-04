<?php
namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use BlogBundle\Entity\Categorie; // Parce qu'on veut gérer les nouvelles catégories

class CategorieController extends Controller{
	
	public function addCategoriesAction(){
		/**
		 * Récupère le Gestionnaire d'Entités du service Doctrine
		 * @var \EntityManager $manager
		 */
		$manager = $this->getDoctrine()
			->getManager();
		
		$categorie = new Categorie();
		$categorie->setLibelle("Symfony")
			->setDescription("Framework PHP");
		
		$categorie1 = new Categorie();
		$categorie1->setLibelle("Doctrine")
			->setDescription("Object Relational Mapper destiné à gérer vos bases de données");
		
		$categorie2 = new Categorie();
		$categorie2->setLibelle("Entités")
			->setDescription("Définit les schémas des tables et des relations d'une base de données");
		
		$manager->persist($categorie);
		$manager->persist($categorie1);
		$manager->persist($categorie2);
		
		$manager->flush();
		
		return new Response("Okay, je viens de créer 3 catégories");
	}
	
	public function voirAction($id){
		$depot = $this->getDoctrine()
			->getManager()
			->getRepository("BlogBundle:Categorie");
		$categorie = $depot->find($id);
		
		return $this->render(
				"BlogBundle:Hello:categorie.html.twig",
				array("categorie" => $categorie)
			);
	}
}