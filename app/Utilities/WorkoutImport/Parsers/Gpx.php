<?php

namespace App\Utilities\WorkoutImport\Parsers;


use App\Utilities\WorkoutImport\Point;

/**
 * This library loads and parse gpx file
 *
 */
class Gpx extends Parser implements \Iterator, ParserInterface
{
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