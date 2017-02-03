<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Blog", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     * @var /Entity/Blog
     * 	inversedBy définit le nom de l'attribut de la relation bi-directionnelle
     * 	dans l'Entité inverse.
     */
	private $blog;
	
    public function __construct(){
    	$this->date = new \DateTime();
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
     * @return Commentaire
     */
    public function setDate(\DateTime $date=null)
    {
    	if(!is_null($date))
        	$this->date = $date;
		else 
			$this->date = new \DateTime();
		
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
     * Set auteur
     *
     * @param string $auteur
     * @return Commentaire
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
     * @return Commentaire
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
     * Set blog
     *
     * @param \BlogBundle\Entity\Blog $blog
     * @return Commentaire
     */
    public function setBlog(\BlogBundle\Entity\Blog $blog)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return \BlogBundle\Entity\Blog 
     */
    public function getBlog()
    {
        return $this->blog;
    }
}
