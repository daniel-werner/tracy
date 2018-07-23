<?php

namespace App\Utilities\WorkoutImport\Parsers;

interface ParserInterface{
	public function parse($fileName);
}