<?php
namespace MenuBundle\Controller;

/**
 * Définir les classes à utiliser dans notre contrôleur
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller{
	
	public function indexAction(){		
		return $this->render(
			"MenuBundle:Default:menu.html.twig",
			array(
				"menu" => $this->menu()
			)
		);
		//return new Response("Coucou, je suis le menu");
	}
	
	private function menu(){
		return array( // Tableau principal "menu"
				array( // Elemént 1 du tableau menu
					"libelle" => "Accueil",
					"route" => "blog_homepage",
					"titre" => "Retour à l'accueil de myBlog"
				),
				array( // Elément 2 du tableau menu
					"libelle" => "Articles",
					"route" => "",
					"titre" => "Blog",
					"enfants" => array(
						array(
								"libelle" => "Tous les articles",
								"route" => "blog_hello",
								"titre" => "Voir tous les articles"
						),
						array(
								"libelle" => "Les 5 derniers articles",
								"route" => "blog_ajouter",
								"titre" => "Voir les 5 derniers articles"
						),
						array(
								"libelle" => "Voir l'article",
								"route" => "blog_voir",
								"titre" => "Voir un article en particulier",
								"identifiant" => 39
						)
					)
				),
				array( // Elément 3 du tableau menu
					"libelle" => "Contact",
					"route" => "blog_contact",
					"titre" => "Contactez l'auteur de myBlog"
				)
		);
	}
}

