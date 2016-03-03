<?php namespace Matches\Transformers\Serializer;

use League\Fractal\Serializer\DataArraySerializer;

/**
 * Class MatchesByDate
 * @package Matches\Transformers\Serializer
 */
class MatchesByDate extends DataArraySerializer
{
    /**
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        $payload = [];

        foreach ($data as $key => $match) {
            $group = $match['group'];
            $id = $match['id'];
            $name = $match['name'] . ", " . $match['date'];

            $payload[$group][$id] = $name;

            unset($data[$key]);
        }

        return $payload;
    }
}