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
     * @var BetLineHydrator
     */
    protected $lineHydrator;

    /**
     * BetBetLinesHydrator constructor.
     * @param BetLineHydrator $lineHydrator
     */
    public function __construct(BetLineHydrator $lineHydrator)
    {
        $this->hydrator = $lineHydrator;
    }

    /**
     * @param mixed $value
     * @return array
     */
    public function extract($value)
    {
        return [];
    }

    /**
     * @param mixed $value
     * @return mixed|void
     *
     */
    public function hydrate($value)
    {
        if(empty($value)) {
            return;
        }

        $existingLines = $this->context->getLines();

        foreach($value as $line) {
            $existing = $this->filterById($existingLines, $line);

            if($existing) {
                $modify = $existing;
            } else {
                $modify = new BetLine();

                $existingLines->add($modify);
                $modify->setBet($this->context);
            }

            $this->hydrator->hydrate($line, $modify);
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
        if(empty($line['id'])) {
            return false;
        }

        return $collection->filter(function($entry) use ($line) {
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