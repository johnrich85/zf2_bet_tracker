<?php namespace Scraper\Events\Contract;

use Scraper\Entity\SourcePage;

interface PageScraperHandler
{
    public function handle(EventInterface $e);
}