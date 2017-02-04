<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Blog
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\BlogRepository")
 */
class Blog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=150)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=75)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;
	
    /**
     * @ORM\Column(name="publication", type="boolean")
     * @var boolean
     */
    private $publication;
    
    /**
     * @ORM\Column(name="vues", type="integer")
     * @var int
     */
    private $vues;
    
    /**
     * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Categorie", cascade={"persist"}, inversedBy="articles")
     * @var ArrayCollection
     */
    private $categories;
    
    
    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Commentaire", mappedBy="blog")
     * @var ArrayCollection
     * 	targetEntity => Entité cible de la relation bi-directionnelle
     * 	mappedBy => Définit le nom de l'attribut dans l'entité Propriétaire qui fait la relation avec l'entité courante.
     */
    private $commentaires;
    
    public function __construct(){
    	$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    	
    	$this->date = new \DateTime();
    	$this->auteur = "WebDev 2016-2017";
    	$this->publication = false;
    	$this->vues = 0; // Par défaut, on attribue 0 à la création d'un article
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Blog
     */
    public function setDate()
    {	
    	$currentDateTime = new \DateTime(); // Récupère la date et l'heure courante
    	if($date < $currentDateTime){
    		$this->date = $currentDateTime;
    	} else {
        	$this->date = $date;
    	}
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Blog
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Blog
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Blog
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Blog
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean 
     */
    public function getPublication()
    {
        return $this->publication;
    }
	
    public function truncateContenu($size=50){
    	if(strlen($this->contenu) > $size)
    		return substr($this->contenu, 0, $size) . " ...";
    	
    	return $this->contenu;
    }
    /**
     * Set vues
     *
     * @param integer $vues
     * @return Blog
     */
    public function setVues($vues)
    {
        $this->vues = $vues;

        return $this;
    }

    /**
     * Get vues
     *
     * @return integer 
     */
    public function getVues()
    {
        return $this->vues;
    }

    /**
     * Add categories
     *
     * @param \BlogBundle\Entity\Categorie $categories
     * @return Blog
     */
    public function addCategory(\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \BlogBundle\Entity\Categorie $categories
     */
    public function removeCategory(\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add commentaires
     *
     * @param \BlogBundle\Entity\Commentaire $commentaires
     * @return Blog
     */
    public function addCommentaire(\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;

        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \BlogBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

}
