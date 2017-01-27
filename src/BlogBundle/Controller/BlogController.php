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

class BlogController extends Controller{
	/**
	 * Stocke les articles déjà disponibles
	 * @var array
	 */
	private $articles;
	
	public function indexAction(){
		$this->articles = $this->getArticles();
		
		return $this->render(
			"BlogBundle:Hello:index.html.twig",
			array(
				"pageTitle" => "J'aime Symfony",
				"titreInterne" => "Symfony exposé par le contrôleur",
				"majVersion" => 1,
				"minVersion" => 0,
				"articles" => $this->articles
			)
		);
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
		
		
		// La méthode $this->generateUrl() permet de convertir
		//	le nom d'une route en une URL :
		// $this->generateUrl("blog_ajouter") => http://blog.dev/app_dev.php/blog/ajouter
		// La méthode $this->redirect() redirige une requête HTTP vers une autre requête HTTP
		
		if($action == "ajouter"){
			return $this->doRedirect("blog_hello");
			
			/**return $this->redirect(
					$this->generateUrl("blog_hello")
			);
			
			$url = $this->generateUrl("blog_hello");
			return $this->redirect($url);
			**/
		}
		
		// Charger le post correspondant à l'ID passé en paramètre
		return $this->render(
			"BlogBundle:Hello:article.html.twig",
			array(
				"id" => $id,
				"auteur" => "Jean-Luc Aubert",
				"action" => $action
			)
		);
		
	}
	
	public function ajouterAction(Request $request){
		$idCree = 5; // Pour l'instant, on va créer une valeur "artificielle"
		
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
				"date" => date("d-m-Y H:i:s")
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