<?php

namespace App\Commands;

use App\Discretize;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DiscretizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:discretize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dataset = $this->ask('Enter data set. Numerical values only. Separate values by comma');
        $data = json_decode('[' . $dataset . ']', true);
        $this->testIfAllNumeric($data);

        $filterType = $this->choice('Select filter to use', ['Equal Width', 'Equal Frequency']);

        if ($filterType == 'Equal Width') {
            $filterType = 'equalWidth';
        } elseif ($filterType == 'Equal Frequency') {
            $filterType = 'equalFrequency';
        }

        $discretize = new Discretize($data, $filterType);
        dump($discretize->run());
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
    private function testIfAllNumeric ($array)
    {
        if (count($array) !== count(array_filter($array, 'is_numeric'))) {
            $this->error('Data set should be all numeric values');
        }
    }
}
