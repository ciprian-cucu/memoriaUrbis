<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Story
 *
 * @ORM\Table(name="story", indexes={@ORM\Index(name="fk_stories_1_idx", columns={"author"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StoryRepository")
 */
class Story
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text", length=65535, nullable=false)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=16777215, nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted_date", type="date", nullable=true)
     */
    private $postedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="date", nullable=true)
     */
    private $lastUpdate;

    /**
     * @var decimal
     *
     * @ORM\Column(name="story_order", type="decimal", precision=4, scale=1, nullable=true)
     */
    private $storyOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=45, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=45, nullable=true)
     */
    private $latitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="year0", type="integer", nullable=true)
     */
    private $year0;

    /**
     * @var integer
     *
     * @ORM\Column(name="year1", type="integer", nullable=true)
     */
    private $year1;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="stories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="author", referencedColumnName="id")
     * })
     */
    private $author;

    /**
     * @var photos
     *
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="story")
     */
    private $photos;

    public function __construct()
    {
       $this->photos = new ArrayCollection();
    }

    public function addPhoto(Photo $photo)
    {
        $this->photos->add($photo) ;
    }

    public function removeStory(Photo $photo)
    {
        $this->photos->removeElement($photo) ;
    }

    /**
     * @return ArrayCollection|Photo[]
     */
    public function getPhotos()
    {
        return $this->photos;
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
     * Set title
     *
     * @param string $title
     *
     * @return Story
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     *
     * @return Story
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Story
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set postedDate
     *
     * @param \DateTime $postedDate
     *
     * @return Story
     */
    public function setPostedDate($postedDate)
    {
        $this->postedDate = $postedDate;

        return $this;
    }

    /**
     * Get postedDate
     *
     * @return \DateTime
     */
    public function getPostedDate()
    {
        return $this->postedDate;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return Story
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set storyOrder
     *
     * @param integer $storyOrder
     *
     * @return Story
     */
    public function setStoryOrder($storyOrder)
    {
        $this->storyOrder = $storyOrder;

        return $this;
    }

    /**
     * Get storyOrder
     *
     * @return integer
     */
    public function getStoryOrder()
    {
        return $this->storyOrder;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Story
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Story
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set year0
     *
     * @param integer $year0
     *
     * @return Story
     */
    public function setYear0($year0)
    {
        $this->year0 = $year0;

        return $this;
    }

    /**
     * Get year0
     *
     * @return integer
     */
    public function getYear0()
    {
        return $this->year0;
    }

    /**
     * Set year1
     *
     * @param integer $year1
     *
     * @return Story
     */
    public function setYear1($year1)
    {
        $this->year1 = $year1;

        return $this;
    }

    /**
     * Get year1
     *
     * @return integer
     */
    public function getYear1()
    {
        return $this->year1;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Story
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Remove photo
     *
     * @param \AppBundle\Entity\Photo $photo
     */
    public function removePhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }
}
