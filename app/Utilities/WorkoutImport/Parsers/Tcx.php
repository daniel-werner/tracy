<?php

namespace App\Utilities\WorkoutImport\Parsers;


use App\Utilities\WorkoutImport\Point;

/**
 * This library loads and parse gpx file
 *
 */
class Tcx extends Parser implements \Iterator, ParserInterface
{
	public function parse($file) {
		if (file_exists($file)) {
			$tcx = simplexml_load_file($file);


			if (!isset($tcx->Activities->Activity)){
				throw new \Exception("Unable to find valid activity in file contents");
			}

			$activityNode = $tcx->Activities->Activity[0];

			// save name
			if (isset($tcx->trk->type)) {
				$this->type = (string)$activityNode['Sport'];
			}

			$index = 0;
			foreach( $activityNode->Lap as $lap ) {

				// push points to array
				foreach ($lap->Track->Trackpoint as $trkpt) {
					$point = new Point(floatval($trkpt->Position->LatitudeDegrees), floatval($trkpt->Position->LongitudeDegrees));

					$point->setSegmentIndex( $index );

					if (!empty($trkpt->AltitudeMeters)) {
						$point->setEvelation(floatval($trkpt->AltitudeMeters));
					};

					if (!empty($trkpt->Time)) {
						$point->setTime($trkpt->Time->__toString());
					};

//					$namespaces = $trkpt->getNamespaces(true);
//					if (!empty($namespaces['gpxtpx'])) {
//						$gpxtpx = $trkpt->extensions->children($namespaces['gpxtpx']);
//						$hr = intval($gpxtpx->TrackPointExtension->hr);
//
//						$point->setHeartRate($hr);
//
//					}
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