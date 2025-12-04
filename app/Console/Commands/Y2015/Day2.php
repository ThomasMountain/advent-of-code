<?php

namespace App\Console\Commands\Y2015;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Day2 extends Command
{
    protected $signature = 'app:2015-day2';

    protected $description = 'Command description';

    const DAY_NUMBER = 2;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting processing day '.self::DAY_NUMBER);
        $startTime = now();
        $input = Storage::get('input/2015/day'.self::DAY_NUMBER.'.txt');

        $this->info('Step 1: '.$this->step1($input));
        $this->info('Step 2: '.$this->step2($input));
        $this->info('Finished processing day '.self::DAY_NUMBER.' in '.$startTime->diffInSeconds(now()).' seconds');
    }

    public function step1(string $input): int
    {
        $totalArea = 0;
        foreach (explode(PHP_EOL, trim($input)) as $dimensions) {
            $d = array_map('intval', explode('x', $dimensions));
            [$length, $width, $height] = $d;

            $side1 = $length * $width;
            $side2 = $width * $height;
            $side3 = $height * $length;

            $surfaceArea = 2 * ($side1 + $side2 + $side3);
            $slack = min($side1, $side2, $side3);

            $totalArea += $surfaceArea + $slack;
        }

        return $totalArea;
    }

    public function step2(string $input): int
    {
        return 0;
    }
}
