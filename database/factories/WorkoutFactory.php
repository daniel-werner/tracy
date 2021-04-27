<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WorkoutFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workout::class;


    public function definition()
    {
        return [
            'type' => $this->faker->numberBetween(1, 2),
            'title' => $this->faker->text(50),
            'status' => Workout::STATUS_ACTIVE,
            'time' => $this->faker->dateTime()
        ];
    }
}
