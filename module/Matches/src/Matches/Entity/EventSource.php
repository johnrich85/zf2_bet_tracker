<?php namespace Matches\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="match_event_source")
 */

class EventSource {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`url`",type="string") */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="sources")
     * @ORM\JoinColumn(name="`event_id`", referencedColumnName="id")
     */
    protected $event;
}