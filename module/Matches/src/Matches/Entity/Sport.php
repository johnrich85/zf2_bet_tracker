<?php namespace Matches\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="match_sport")
 */

class Sport {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`name`",type="string") */
    protected $name;

    /** @ORM\Column(name="`category`",type="string") */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="sport", fetch="EAGER")
     */
    protected $matches;

}