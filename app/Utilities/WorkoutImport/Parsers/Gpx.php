<?php

namespace App\Utilities\WorkoutImport\Parsers;


use App\Utilities\WorkoutImport\Point;
/**
 * This library loads and parse gpx file
 *
 */
class Gpx implements \Iterator, Parser
{

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var int
	 */
	private $position;

	/**
	 * @var Point[]
	 */
	private $points = array();

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
	 * @return Point[]
	 */
	public function getPoints()
	{
		return $this->points;
	}

	public function rewind() {
		$this->position = 0;
	}

	/**
	 * @return Point
	 */
	public function current() {
		return $this->points[$this->position];
	}

	/**
	 * @return int
	 */
	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return isset($this->points[$this->position]);
	}


	public function parse($file) {
		if (file_exists($file)) {
			$gpx = simplexml_load_file($file);

			// save name
			if (isset($gpx->trk->type)) {
				$this->type = (string) $gpx->trk->type;
			}

			$index = 0;
			foreach( $gpx->trk->trkseg as $trkseg ) {

				// push points to array
				foreach ($trkseg->children() as $trkpt) {
					$point = new Point(floatval($trkpt['lat']), floatval($trkpt['lon']));

					$point->setSegmentIndex( $index );

					if (!empty($trkpt->ele)) {
						$point->setEvelation(floatval($trkpt->ele->__toString()));
					};

					if (!empty($trkpt->time)) {
						$point->setTime($trkpt->time->__toString());
					};

					$namespaces = $trkpt->getNamespaces(true);
					if (!empty($namespaces['gpxtpx'])) {
						$gpxtpx = $trkpt->extensions->children($namespaces['gpxtpx']);
						$hr = intval($gpxtpx->TrackPointExtension->hr);

						$point->setHeartRate($hr);

					}
					$this->points[] = $point;
				}

				$index++;
			}

		} else {
			throw new \Exception('The file does not exist.');
		}

		return $this->points;
	}
}