<?php

namespace App\Console\Commands;

use App\Models\Training;
use App\Models\TrainingFile;
use Faker\Generator as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateTrainings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:trainings {count?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the trainings (count is the number of trainings to be generate)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Faker $faker
     * @return void
     */
    public function handle(Faker $faker)
    {
        $limit = (int) ($this->argument('count') ?? 20);

        $trainings = factory(Training::class, $limit)->create();

        $demoImages = Storage::allFiles('public/default-trainings-images');

        if (!empty($demoImages)) {
            foreach ($trainings as $key => $training) {
                for ($i = 0; $i < rand(2, 6); $i++) {
                    try {
                        $uniqueName = md5($training->id) . rand(1, 99999) . time();
                        $path = "trainings/generated-automatically/images/{$uniqueName}.jpg";

                        Storage::put($path, Storage::get($demoImages[rand(0, count($demoImages) - 1)]));

                        TrainingFile::create([
                            'training_id' => $training->id,
                            'name'        => $faker->word,
                            'type'        => 'image',
                            'url'         => $path
                        ]);
                    } catch (\Exception $exception) {
                        $this->error($exception->getMessage());
                        break;
                    }
                }

                $this->line(($key + 1) . " from {$limit} trainings were generated");
            }
        }

        $this->info("{$limit} trainings were successfully generated.");
    }
}
