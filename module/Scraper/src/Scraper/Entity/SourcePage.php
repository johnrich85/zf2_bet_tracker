<?php namespace Scraper\Entity;

use Doctrine\ORM\Mapping as ORM;
use Matches\Entity\Match;

/**
 * @ORM\Entity
 * @ORM\Table(name="source_page")
 * @ORM\Entity(repositoryClass="Scraper\Repository\SourcePageRepository")
 */

class SourcePage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *  @ORM\Column(name="`source_id`",type="integer")
     */
    protected $source_id;

    /** @ORM\Column(name="`title`",type="string") */
    protected $title;

    /** @ORM\Column(name="`caster`",type="string") */
    protected $caster;

    /** @ORM\Column(name="`parser`",type="string") */
    protected $parser;

    /** @ORM\Column(name="`uri`",type="string") */
    protected $uri;

    /**
     *  @ORM\ManyToOne(targetEntity="Source", inversedBy="pages")
     *  @ORM\JoinColumn(name="`source_id`", referencedColumnName="id")
     */
    protected $source;

    /**
     * @ORM\OneToOne(targetEntity="SourcePageMatch", mappedBy="page", fetch="LAZY", cascade={"persist", "remove"})
     */
    protected $match;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getCaster()
    {
        return $this->caster;
    }

    /**
     * @param mixed $caster
     */
    public function setCaster($caster)
    {
        $this->caster = $caster;
    }

    /**
     * @return mixed
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param mixed $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return Match|null
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param mixed $match
     */
    public function setMatch(SourcePageMatch $match)
    {
        $this->match = $match;
    }
}