<?php
namespace MenuBundle\Controller;

/**
 * Définir les classes à utiliser dans notre contrôleur
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Entity\Categorie; // On a besoin de récupérer les catégories...

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
	
	public function footerAction(){
		return $this->render(
				"MenuBundle:Default:footer.html.twig",
				array(
						"menu" => $this->menu()
				)
		);
	}
	
	private function menu(){
		// Essayons donc de lister nos catégories
		$depot = $this->getDoctrine()
			->getManager()
			->getRepository("BlogBundle:Categorie");
		//$categories = $depot->findAll(); // Injecte toutes les catégories dans $categories
		
		/**
		 * Si on voulait les catégories triées par libellés
		 */
		 $categories = $depot->findBy(
		  		array(), // Pas de filtres
		  		array("libelle" => "asc")
		  );
		 
		 /**
		  * On peut passer par nos méthodes... définies dans le repository
		  */
		$categories = $depot->allCategories();
		
		/**
		 * On peut aussi appeler les méthodes du Repository avec des paramètres
		 */
		$categories = $depot->allCategoriesBy("c.libelle", "DESC");
		
		/**
		 * On peut faire des requêtes un peu plus évoluées
		 */
		$categories = $depot->PHPCategories();
		
		/**
		 * On peut aussi utiliser une requête DQL...
		 */
		$categories = $depot->findAllCategories();
		
		/**
		 * On peut utiliser aussi le DQL directement dans le contrôleur
		 */
		$categories = $this->categoriesFromController();
		
		$enfants = array(); // Contient l'ensemble des informations pour me permettre de faire générer les options du menu
		
		// On va boucler sur les catégories...
		foreach($categories as $categorie){
			$laCategorie = array(
				"libelle" => $categorie->getLibelle(),
				"route" => "blog_bycat",
				"titre" => "Voir tous les articles de la catégorie " . $categorie->getLibelle(),
				"identifiant" => $categorie->getId()
			);
			// Ajoute le tableau $laCategorie au tableau $enfants
			$enfants[] = $laCategorie;
		}
		
		
		return array( // Tableau principal "menu"
				array( // Elemént 1 du tableau menu
					"libelle" => "Accueil",
					"route" => "blog_homepage",
					"titre" => "Retour à l'accueil de myBlog"
				),
				array( // Elément 2 du tableau menu
					"libelle" => "Hot Posts",
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
				// Ce serait bien d'avoir la liste des catégories...
				array(
					"libelle" => "Catégories",
					"route" => "",
					"titre" => "Articles par Catégorie",
					"enfants" => $enfants
				),
				array( // Elément 3 du tableau menu
					"libelle" => "Contact",
					"route" => "blog_contact",
					"titre" => "Contactez l'auteur de myBlog"
				)
		);
	}
	
	private function categoriesFromController(){
		$em = $this->getDoctrine()->getManager();
		
		$query = $em->createQuery("SELECT c FROM BlogBundle:Categorie c");
		
		return $query->getResult();
	}
}

