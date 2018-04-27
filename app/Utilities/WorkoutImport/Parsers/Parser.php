<?php

namespace App\Utilities\WorkoutImport\Parsers;

interface Parser{
	public function parse($fileName);
}