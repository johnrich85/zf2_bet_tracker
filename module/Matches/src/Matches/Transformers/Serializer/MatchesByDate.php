<?php namespace Matches\Transformers\Serializer;

use League\Fractal\Serializer\DataArraySerializer;

/**
 * Class MatchesByDate
 * @package Matches\Transformers\Serializer
 */
class MatchesByDate extends DataArraySerializer
{
    /**
     * @var array
     */
    protected $indexMap = [];

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

            if (!in_array($group, $this->indexMap)) {
                $payload[] = $this->createIndex($group);
            }

            $groupKey = array_keys($this->indexMap, $group)[0];

            $payload[$groupKey]['options'][$id] = $name;

            unset($data[$key]);
        }

        return $payload;
    }

    /**
     * Returns structure for group.
     *
     * @param $label
     * @return array
     */
    protected function createIndex($label)
    {
        $this->indexMap[] = $label;

        return [
            'label' => $label,
            'options' => []
        ];
    }
}