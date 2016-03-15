<?php namespace Scraper\Events\Contract;

use Zend\EventManager\EventInterface;

interface PageScraperHandler
{
    public function handle(EventInterface $e);
}