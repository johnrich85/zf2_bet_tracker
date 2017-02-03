<?php namespace Bet\Hydrator;

use Bet\Entity\Bet;
use Bet\Entity\BetLine;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Class BetBetLinesHydrator
 *
 * @package Bet\Hydrator
 */
class BetBetLinesHydrator implements StrategyInterface
{
    /**
     * @var Bet
     */
    protected $context;

    /**
     * @param mixed $value
     * @return array
     */
    public function extract($value)
    {
        // TODO: Implement extract() method.

        return [];
    }

    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function hydrate($value)
    {
        if(empty($value)) {
            return;
        }

        var_dump($value);
        $existingLines = $this->context->getLines();

        foreach($value as $line) {
            $existing = $this->filterById($existingLines, $line);

            if($existing) {
                $modify = $existing;
            } else {
                $modify = new BetLine();
                $existingLines->add($modify);
            }

            $modify->populate($line);
        }

        return $existingLines;
    }

    /**
     * @param $collection
     * @param $line
     * @return mixed
     */
    protected function filterById($collection, $line)
    {
        return $collection->filter(
            function($entry) use ($line) {
                return $entry->getId() == $line['id'];
            })->first();
    }

    /**
     * @param Bet $context
     */
    public function setContext(Bet $context)
    {
        $this->context = $context;
    }
}