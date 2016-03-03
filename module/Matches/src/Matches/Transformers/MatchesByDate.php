<?php namespace Matches\Transformers;

use League\Fractal\TransformerAbstract;
use Matches\Entity\Match;

class MatchesByDate extends TransformerAbstract
{
    /**
     * @param Match $match
     * @return array
     */
    public function transform(Match $match)
    {
        return [
            'id' => $match->getId(),
            'name' => $match->toString(),
            'first_team' => $match->getFirstTeam()->getName(),
            'second_team' => $match->getSecondTeam()->getName(),
            'sport' => $match->getSport()->getName(),
            'date' => $this->formatDate($match->getDate()),
            'group' => $this->groupName($match->getDate())
        ];
    }

    /**
     * @param $date
     * @return mixed
     */
    protected function formatDate($date)
    {
        return $date->format('H:i:s');
    }

    /**
     * @param $date
     * @return mixed
     */
    protected function groupName($date)
    {
        return $date->format('d-m-y');
    }
}