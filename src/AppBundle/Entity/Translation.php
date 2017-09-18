<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translation
 *
 * @ORM\Table(name="translation")
 * @ORM\Entity
 */
class Translation
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
     * @ORM\Column(name="lang", type="string", length=4)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="table", type="string", length=10)
     */
    private $table;

    /**
     * @var integer
     *
     * @ORM\Column(name="entry", type="integer")
     */
    private $entry;


    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=35)
     */
    private $field;

    /**
     * @var text
     *
     * @ORM\Column(name="translated", type="text")
     */
    private $translated;

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
     * Set lang
     *
     * @param string $lang
     *
     * @return Translation
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set table
     *
     * @param string $table
     *
     * @return Translation
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set entry
     *
     * @param integer $entry
     *
     * @return Translation
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return integer
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set field
     *
     * @param string $field
     *
     * @return Translation
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set translated
     *
     * @param string $translated
     *
     * @return Translation
     */
    public function setTranslated($translated)
    {
        $this->translated = $translated;

        return $this;
    }

    /**
     * Get translated
     *
     * @return string
     */
    public function getTranslated()
    {
        return $this->translated;
    }
}
