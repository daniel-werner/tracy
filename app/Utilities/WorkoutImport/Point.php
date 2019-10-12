<?php

namespace App\Utilities\WorkoutImport;

/**
 *
 */
class Point
{

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var int
     */

    private $heartRate;

    /**
     * @var int
     */

    private $elevation;

    private $segmentIndex;

    /**
     * @return mixed
     */
    public function getSegmentIndex()
    {
        return $this->segmentIndex;
    }

    /**
     * @param mixed $segmentIndex
     */
    public function setSegmentIndex($segmentIndex)
    {
        $this->segmentIndex = $segmentIndex;
    }

    /**
     * @var string
     */
    private $time;

    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getEvelation()
    {
        return $this->elevation;
    }

    /**
     * @param int $evelation
     */
    public function setEvelation($elevation)
    {
        $this->elevation = $elevation;
    }

    /**
     * @return int
     */
    public function getHeartRate()
    {
        return $this->heartRate;
    }

    /**
     * @param int $heartRate
     */
    public function setHeartRate($heartRate)
    {
        $this->heartRate = $heartRate;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return new \DateTime($this->time);
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }
}
