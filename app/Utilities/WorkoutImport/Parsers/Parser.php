<?php

namespace app\Utilities\WorkoutImport\Parsers;

interface Parser{
	public function parse($fileName);
}