<?php

namespace App\Utilities\WorkoutImport\Parsers;

use App\Utilities\WorkoutImport\Point;

/**
 * This library loads and parse gpx file
 *
 */
abstract class Parser implements \Iterator, ParserInterface
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    private $position;

    /**
     * @var Point[]
     */
    protected $points = array();

    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->points[0]->getTime();
    }

    /**
     * @return Point[]
     */
    public function getPoints()
    {
        return $this->points;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return Point
     */
    public function current()
    {
        return $this->points[$this->position];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->points[$this->position]);
    }


    public function parse($file)
    {
    }
}
