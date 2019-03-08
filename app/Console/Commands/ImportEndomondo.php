<?php

namespace App\Console\Commands;

use App\Point;
use App\User;
use App\Workout;
use Fabulator\Endomondo\EndomondoApi;
use Fabulator\Endomondo\WorkoutType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportEndomondo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:endomondo {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import workouts from endomondo.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EndomondoApi $endomondo)
    {
        parent::__construct();

        $this->endomondo = $endomondo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = config('app.endomondo_user_email', '');
        $password = config('app.endomondo_user_password', '');

        $this->endomondo->login($email, $password);

        $user = User::where('email', $email)->first();
        $this->info('Getting endomondo workouts...');

        $typeMap = [
            WorkoutType::RUNNING => Workout::TYPE_RUNNING,
            WorkoutType::WALKING => Workout::TYPE_RUNNING,
            WorkoutType::CYCLING_TRANSPORT => Workout::TYPE_CYCLING,
            WorkoutType::CYCLING_SPORT => Workout::TYPE_CYCLING
        ];

        $data = [];

        foreach ($typeMap as $endomondoType => $mappedType) {
            $data = array_merge_recursive($data, $this->endomondo->getWorkouts(['sport' => $endomondoType]));
        }

        $data['workouts'] = array_reverse($data['workouts']);

        if ($this->option('clear')) {
            $this->info('Deleting workouts...');
            DB::table('workouts')->where('user_id', '=', $user->id)->delete();
        }

        $workoutExternalIds = Workout::withoutGlobalScope('user_id')->whereNotNull('external_id')->pluck('id',
            'external_id');

        $this->info('Importing workouts...');

        $importCount = 0;
        $bar = $this->output->createProgressBar(count($data['workouts']));
        foreach ($data['workouts'] as $index => $endomondoWorkout) {

            $type = $endomondoWorkout->getTypeId();
            $externalId = $endomondoWorkout->getId();

            if (isset($typeMap[$type]) && empty($workoutExternalIds[$externalId])) {

                $importCount++;

                $workout = [
                    'external_id' => $externalId,
                    'title' => 'Endomondo workout',
                    'time' => $endomondoWorkout->getStart()->setTimeZone(new \DateTimeZone('UTC')),
                    'type' => $typeMap[$type],
                    'import_filename' => '',
                    'user_id' => $user->id,
                    'status' => Workout::STATUS_ACTIVE
                ];

                $workout = Workout::create($workout);

                $points = [];

                foreach ($endomondoWorkout->getPoints() as $point) {
                    $points[] = new Point([
                        'workout_id' => $workout->id,
                        'segment_index' => 0,
                        'coordinates' => $point,
                        'heart_rate' => $point->getHeartRate(),
                        'elevation' => $point->getAltitude(),
                        'time' => $point->getTime()->setTimeZone(new \DateTimeZone('UTC')),
                    ]);
                }

                $workout->points()->saveMany($points);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->output->newLine();

        $this->info(sprintf("Imported %s new workouts from Endomondo, skipped the existing: %s", $importCount,
            count($data['workouts']) - $importCount));
    }
}
