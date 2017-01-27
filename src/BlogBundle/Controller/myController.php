<?php
/**
 * @name myController.php : Classe qui étend Controller de Symfony
 */
namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class myController extends Controller{
	
	public function doRedirect($routeName){
		return $this->redirect(
			$this->generateUrl($routeName)
		);
	}
}