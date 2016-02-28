<?php namespace Matches\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="match_event")
 */

class Event {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`name`",type="string") */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="event", fetch="EAGER")
     */
    protected $matches;

}