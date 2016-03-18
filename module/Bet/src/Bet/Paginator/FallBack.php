<?php namespace Bet\Paginator;

use Countable;

class FallBack implements Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var integer
     */
    protected $total_rows;

    /**
     * @var integer
     */
    protected $per_page;

    /**
     * @var integer
     */
    protected $page_count;

    public function __construct(array $data, $totalRows, $perPage)
    {
        $this->data = $data;
        $this->total_rows = $totalRows;
        $this->per_page = $perPage;
    }

    /**
     * Returns the number of pages.
     *
     * @return int
     */
    public function count()
    {
        if (!$this->pageCount) {
            $this->pageCount = (int) ceil($this->total_rows / $this->per_page);
        }

        return $this->pageCount;
    }

    /**
     * Returns a foreach compatible iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}