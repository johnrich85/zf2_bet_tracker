<?php namespace Scraper\Casters\Contract;

/**
 * Defines the public interface for
 * web scrapers.
 *
 * Interface Contract
 * @package Scraper\Scraper
 */
interface Caster
{
    /**
     * @return array
     */
    public function getEntities();

    /**
     * @param array $data
     * @return null
     */
    public function cast(array $data);
}