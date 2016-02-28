<?php namespace Scraper\Parsers\Contract;

/**
 * Defines the public interface for
 * web scrapers.
 *
 * Interface Contract
 * @package Scraper\Scraper
 */
interface Parser
{
    /**
     * @return boolean
     */
    public function parse();
}