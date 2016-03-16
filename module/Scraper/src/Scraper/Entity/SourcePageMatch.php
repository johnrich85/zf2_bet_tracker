<?php namespace Scraper\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="source_page_match")
 */

class SourcePageMatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *  @ORM\Column(name="`page_id`",type="integer")
     */
    protected $page_id;

    /**
     *  @ORM\Column(name="`match_id`",type="integer")
     */
    protected $match_id;

    /**
     *  @ORM\ManyToOne(targetEntity="SourcePage", inversedBy="match")
     *  @ORM\JoinColumn(name="`page_id`", referencedColumnName="id")
     */
    protected $page;

    /**
     * @ORM\OneToOne(targetEntity="Matches\Entity\Match", fetch="EAGER")
     * @ORM\JoinColumn(name="`match_id`", referencedColumnName="id")
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
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @param mixed $page_id
     */
    public function setPageId($page_id)
    {
        $this->page_id = $page_id;
    }

    /**
     * @return mixed
     */
    public function getMatchId()
    {
        return $this->match_id;
    }

    /**
     * @param mixed $match_id
     */
    public function setMatchId($match_id)
    {
        $this->match_id = $match_id;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param mixed $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }
}