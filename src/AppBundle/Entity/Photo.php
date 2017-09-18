<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table(name="photo", indexes={@ORM\Index(name="fk_photos_1_idx", columns={"story"})})
 * @ORM\Entity
 */
class Photo
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
     * @ORM\Column(name="file", type="string", length=255, nullable=false)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", length=65535, nullable=true)
     */
    private $notes;

    /**
     * @var \AppBundle\Entity\Story
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Story", inversedBy="photos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="story", referencedColumnName="id")
     * })
     */
    private $story;

    /**
     * @var \AppBundle\Entity\Photographer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Photographer", inversedBy="photos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="photographer", referencedColumnName="id")
     * })
     */
    private $photographer;



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
     * Set file
     *
     * @param string $file
     *
     * @return Photo
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Photo
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set story
     *
     * @param \AppBundle\Entity\Story $story
     *
     * @return Photo
     */
    public function setStory(\AppBundle\Entity\Story $story = null)
    {
        $this->story = $story;

        return $this;
    }

    /**
     * Get story
     *
     * @return \AppBundle\Entity\Story
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * Set photographer
     *
     * @param \AppBundle\Entity\Photographer $photographer
     *
     * @return Photo
     */
    public function setPhotographer(\AppBundle\Entity\Photographer $photographer = null)
    {
        $this->photographer = $photographer;

        return $this;
    }

    /**
     * Get photographer
     *
     * @return \AppBundle\Entity\Photographer
     */
    public function getPhotographer()
    {
        return $this->photographer;
    }
}
