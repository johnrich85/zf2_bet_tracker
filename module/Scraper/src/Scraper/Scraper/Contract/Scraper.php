<?php namespace Scraper\Scraper\Contract;

/**
 * Defines the public interface for
 * web scrapers.
 *
 * Interface Contract
 * @package Scraper\Scraper
 */
interface Scraper
{
    /**
     * @return boolean
     */
    public function connect();

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get();
}