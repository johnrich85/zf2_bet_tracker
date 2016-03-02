<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;
use Doctrine\ORM\EntityRepository;
use Matches\Repository\EventRepository;

class Event extends TaService\TaService
{
    /**
     * @var
     */
    protected $eventRepository;

    /**
     * @param EventRepository $eventRepo
     */
    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepository = $eventRepo;
    }

    /**
     * @param $source
     * @return mixed
     */
    public function findEventBySource($source)
    {
        return $this->eventRepository
            ->findEventBySource($source);
    }

    /**
     * @return \Matches\Entity\Event|mixed
     */
    public function findDefault()
    {
        $event = $this->eventRepository
            ->findDefault();

        if (!$event) {
            $event = new \Matches\Entity\Event();
            $event->setName('Default');

            $this->em->persist($event);
            $this->em->flush();
        }

        return $event;
    }
}