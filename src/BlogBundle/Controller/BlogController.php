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
	
	public function indexAction(){
		return $this->render(
			"BlogBundle:Hello:index.html.twig",
			array(
				"pageTitle" => "J'aime Symfony",
				"titreInterne" => "Symfony exposé par le contrôleur",
				"majVersion" => 1,
				"minVersion" => 0,
				"menu" => $this->menu()
			)
		);
	}
	
	private function queryGet($param,$defaultValue=null){
		if(!array_key_exists($param,$_GET)){
			return !is_null($defaultValue) ? $defaultValue : null;
		}
		return $_GET[$param];
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
				"action" => $action,
				"menu" => $this->menu()
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
				"date" => date("d-m-Y H:i:s"),
				"menu" => $this->menu()
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

	private function menu(){
		return array(
				array(
						"libelle" => "Accueil",
						"route" => "blog_homepage",
						"titre" => "Retour à l'accueil de myBlog"
				),
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
						"libelle" => "Contact",
						"route" => "blog_contact",
						"titre" => "Contactez l'auteur de myBlog"
				)
		);
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