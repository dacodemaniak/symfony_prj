<?php
/**
 * namespace Espace de nom correspond généralement au dossier contenant le contrôleur
 */
namespace BlogBundle\Controller;

/**
 * Utilisation de la classe Controller parente pour définir notre contrôleur
 */
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Controller\myController as Controller;
use Symfony\Component\HttpFoundation\Response; // Classe permettant de retourner une réponse HTTP simplement
use Symfony\Component\HttpFoundation\Request; // Classe permettant d'accéder aux informations de la requête HTTP
use BlogBundle\Entity\Blog;
use BlogBundle\Entity\Commentaire;

class BlogController extends Controller{
	/**
	 * Stocke les articles déjà disponibles
	 * @var array
	 */
	private $articles;
	
	public function indexAction(){
		$this->articles = $this->getArticles();
		
		$manager = $this->getDoctrine()
			->getManager();
		
		// 1. On va utiliser la méthode findAll() du Respository de Blog pour récupérer tous les articles
		//$articles = $manager->getRepository("BlogBundle:Blog")->findAll();
		$articles = $manager->getRepository("BlogBundle:Blog")
			->findBy(
				array(), 
				array("date" => "desc"),
				5,
				0
			);
		return $this->render(
			"BlogBundle:Hello:index.html.twig",
			array(
				"pageTitle" => "J'aime Symfony",
				"titreInterne" => "Symfony exposé par le contrôleur",
				"majVersion" => 1,
				"minVersion" => 0,
				"articles" => $articles
			)
		);
	}
	
	/**
	 * Méthode permettant de récupérer les articles de manière différentes
	 * @param Request $httpRequest
	 */
	public function getArticlesAction(Request $httpRequest){
		$auteur = $httpRequest->query->get("auteur",null);
		$date = $httpRequest->query->get("date",null);
		$publication = $httpRequest->query->get("publication",null);
		
		$repo = $this->getDoctrine()
			->getManager()
			->getRepository("BlogBundle:Blog"); // Récupère le dépôt de l'entité Blog
		
		// Définition des critères de la requête
		$criteres = array();
		if(!is_null($auteur)){
			$criteres["auteur"] = $auteur;
		}
		if(!is_null($date)){
			$criteres["date"] = new \DateTime($date);
		}
		if(!is_null($publication)){
			$criteres["publication"] = $publication;
		}
		
		$articles = $repo->findBy($criteres);
		
		return $this->render("BlogBundle:Hello:articles.html.twig",
				array(
					"articles" => $articles
		));
		
	}
	
	public function byAuteurAction(Request $httpRequest){
		$auteur = $httpRequest->query->get("auteur", "JLA");
		
		$manager = $this->getDoctrine()->getManager();
		
		/**
		 * Utilisation de la méthode findBy() de l'Entity Manager
		 * 	@param array $criteres : critère de restriction
		 *  @param array [optionnel] : $orderBy => critères de tri
		 *  @param int $limite [optionnel] => Nombre de lignes à retourner
		 *  @param int $offset [optionnel] => A partir de quel ligne on commence la requête
		 * @var \ArrayCollection $articles
		 */
		/*$articles = $manager->getRepository("BlogBundle:Blog")
			->findBy(
				array("auteur" => $auteur)
			);
		*/
		$articles = $manager->getRepository("BlogBundle:Blog")->findByAuteur($auteur);
		return $this->render("BlogBundle:Hello:articleByAuteur.html.twig",
				array(
					"articles" => $articles
				));
	}
	
	private function queryGet($param,$defaultValue=null){
		if(!array_key_exists($param,$_GET)){
			return !is_null($defaultValue) ? $defaultValue : null;
		}
		return $_GET[$param];
	}
	
	private function getArticles(){
		return array(
			array(
				"titre" => "Mon premier post",
				"contenu" => "blablabla...",
				"image" => "image/first.jpg",
				"auteur" => "JLA",
				"date" => new \DateTime("2016-12-23")
			),
			array(
						"titre" => "Mon second post",
						"contenu" => "blablabla...",
						"image" => "image/second.jpg",
						"auteur" => "JLA",
						"date" => new \DateTime("2016-12-24")
				),
			array(
						"titre" => "Mon troisième post",
						"contenu" => "blablabla...",
						"image" => "image/third.jpg",
						"auteur" => "JLA",
						"date" => new \DateTime("2017-01-02")
				)
		);	
	}
	
	/**
	 * Permet de récupérer un paramètre qui vient d'une URL de type /blog/post/{id}
	 * @param int $id
	 * @param Request $httpRequest : Objet Request de Symfony
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function voirAction($id, Request $httpRequest){
		//return new Response("L'identifiant transmis est : " . $id);
		
		// Maintenant je peux accéder aux paramètres de requête HTTP
		//	(Tout ce qui se situe après ? dans la requête HTTP)
		// par l'intermédiaire de l'objet Request $httpRequest
		
		$action = $httpRequest->query->get("action","voir");
		
		/**
		 * Utilisons les services offerts par le Repository de Doctrine
		 */
		$depot = $this->getDoctrine()
			->getManager()
			->getRepository("BlogBundle:Blog");
		
		/**
		 * Demande à Doctrine, à partir de l'Entité "Blog" d'alimenter le dépôt
		 * 	"BlogRepository" avec les données associée à la clé primaire dont la valeur
		 * 	est $id <=>
		 * SELECT id,date,titre,contenu,auteur,vues FROM blog WHERE id=$id;
		 * $article = MaBase->fetch();
		 * 
		 * @var Object $article => Contient les informations de la ligne identifiée par $id
		 * 	de la table blog
		 */
		$article = $depot->find($id);
		

		
		if(is_null($article)){
			// Signifie que la méthode find($id) de $depot n'a pas pu aboutir
			//	Probablement que la valeur $id n'existe plus... dans la base de données
			throw $this->createNotFoundException("L'article de Blog [" . $id . "] n'existe pas !");
		}
		
		/**
		 * Attention, on visualise l'article, donc... on doit incrémenter le nombre de vues
		 */
		$vuesCourantes = $article->getVues() + 1; // On récupère le nombre de vues courant dans la base
		//$vuesIncrementees = $vuesCourantes + 1; // Incrémente le nombre de vues
		
		/**
		 * On va enregistrer la nouvelle information dans la base
		 */
		$article->setVues($vuesCourantes); // On utilise la méthode setVues()
		
		$this->getDoctrine()->getManager()->flush(); // On écrit le tout dans la base de données
		
		// Charger le post correspondant à l'ID passé en paramètre
		return $this->render(
			"BlogBundle:Hello:voir.html.twig",
			array(
				"article" => $article
			)
		);
		
	}
	
	public function ajouterAction(Request $request){
		$idCree = 5; // Pour l'instant, on va créer une valeur "artificielle"
		
		/**
		 * Récupère le service Doctrine dans la variable $doctrine
		 * @var Object $doctrine
		 */
		$doctrine = $this->get("doctrine");
		// ou... $doctrine = $this->getDoctrine();
		// Encore plus simple : $manager = $this->getDoctrine()->getManager();
		/**
		 * Depuis le service Doctrine, on veut récupérer le gestionnaire d'entités
		 *  (Entity Manager)
		 * @var unknown $manager
		 */
		$manager = $doctrine->getManager();
		
		$article = new Blog(); // Instanciation de l'entité Blog (@see Blog.php)
		//$article->setId($idCree);
		$article->setTitre("Un article et des commentaires");
		$article->setPublication(true);
		$article->setContenu("Nouvel article, mais directement avec des commentaires");
		
		$commentaire = new Commentaire();
		$commentaire->setAuteur("Jean-Luc Aubert")
			->setContenu("Pas mal Symfony, d'un coup, je peux créer un article et des commentaires")
			->setDate();
		$commentaire->setBlog($article); // On définit l'entité à laquelle rattacher le commentaire
		
		$commentaire2 = new Commentaire();
		$commentaire2->setAuteur("Coder")
			->setContenu("Et oui, sans avoir à réfléchir, Doctrine va tout faire à ma place.");
		$commentaire2->setBlog($article); // Deux commentaires, pour le même article
		
		
		$autrePost = new Blog();
		$autrePost->setTitre("Un autre article avec d'autres commentaires")
			->setPublication(true)
			->setContenu("La gestion des transactions de Doctrine s'occupe du tout..")
			->setAuteur("Jean-Luc");
		
		$commentaire3 = new Commentaire();
		$commentaire3->setAuteur("Jean-Luc Aubert")
			->setContenu("On peut faire tout ce qu'on veut d'un seul coup...\nAttention pourtant à ne pas oublier de faire persister les entités !!!");
		$commentaire3->setBlog($autrePost);
		
		// On va demander à Doctrine de faire "persister" l'objet $article en base de données
		$manager->persist($commentaire);
		$manager->persist($commentaire2);
		$manager->persist($commentaire3);
		$manager->persist($article);
		$manager->persist($autrePost);
		
		$manager->flush(); // Pour écrire l'ensemble des objets à faire persister
		
		// Dans la classe Contrôleur, on récupère un objet Session
		// et de cet objet Session, on utilise le service getFlashBag()
		$flashMessage = $this->get("session")->getFlashBag();
		//$flashMessage = $this->get("session")->getFlashBag();
		$flashMessage->add("info","Je suis un message Flash, service de Symfony.");
		$flashMessage->add("info","Autre message");
		
		// Vous ne faites pas... $mailer = new swiftMailer();
		
		/**
		 * En PHP :
		 * $_SESSION["info"] = array(
		 * 	"Je suis un message Flash, service de Symfony",
		 * 	"Je suis capable d'afficher la valeur 5"
		 * );
		**/
		return $this->render(
			"BlogBundle:Hello:ajouter.html.twig",
			array(
				"date" => date("d-m-Y H:i:s"),
				"article" => $article // On passe l'instance de l'objet BlogBundle\Entity\Blog
			)
		);
	}
	
	/**
	 * Méthode qui permet d'associer un article à TOUTES les catégories
	 */
	public function modifierAction(){
		// Récupère l'Entity Manager
		$manager = $this->getDoctrine()
			->getManager();
		
		//1. On récupère l'article sur lequel on veut faire une mise à jour
		$article = $manager->getRepository("BlogBundle:Blog")->find(1);
		
		//2. On récupère la totalité des Catégories...
		$categories = $manager->getRepository("BlogBundle:Categorie")->findAll();
		
		//3. On peut boucler sur le tableau des objets Categorie
		foreach($categories as $categorie){
			//4. Appelle la méthode addCategory() de l'objet $article
			$article->addCategory($categorie); // Ajoute la catégorie à la pile
		}
		
		//4. Il ne reste plus qu'à enregistrer le tout...
		$manager->flush();
		
		//5. Une action de contrôleur doit toujours retourner une réponse...
		return $this->render(
			"BlogBundle:Hello:articlecategories.html.twig",
			array(
				"article" => $article,
				"categories" => $categories
			)
		);
	}
	
	private function toHtml($id){
		return "
			<!doctype html>
			<html>
				<head>
					<title>Symfony is beautifull</title>
				</head>
				
				<body>
					<header>
						<h1>Bonjour Symfony</h1>
						<h2>On devra retouner l'article avec le n° : " . $id . "
					</header>
				</body>
			</html>
		";
	}
}

/**
 * Redirection en PHP
 * if(array_key_exists("action", $_GET)){
 * 	if($_GET["action"] == "ajouter"){
 * 		$url = "app_dev.php/hello-world";
 * 		header("Location: " . $url);
 * 	}
 * }
 */