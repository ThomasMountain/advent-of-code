<?php

namespace App\Console\Commands\Y2015;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Day6 extends Command
{
    const DAY_NUMBER = 6;

    protected $signature = 'app:2015-day6';

    protected $description = 'Command description';

    public function handle()
    {
        $this->info('Starting processing day ' . self::DAY_NUMBER);
        $input = Storage::get('input/2015/day6.txt');

        $startTime = now();

        $this->info('Step 1 Result: ' . $this->step1($input));
        $this->info('Step 1 Result: ' . $this->step2($input));

        $this->info('Finished processing day ' . self::DAY_NUMBER . ' in ' . $startTime->diffInSeconds(now()) . ' seconds');
    }

    public function step1(string $input): int
    {
        $json = json_decode($input, true);
        $totalValues = [];

        array_walk_recursive($json, function (&$value) use(&$totalValues) {
            if (is_numeric($value)) {
                $totalValues[] = $value;
            }
        });
        return array_sum($totalValues);
    }

    public function step2(): int
    {
        $process = function (&$data) use (&$process) {
            if (is_array($data)) {
                $isObject = array_keys($data) !== range(0, count($data) - 1);

                if ($isObject) {
                    foreach ($data as $key => $value) {
                        if ($value === "red") {
                            return 0;
                        }
                    }
                }

                $sum = 0;
                foreach ($data as $key => $value) {
                    $sum += $process($value);
                }
                return $sum;
            } elseif (is_numeric($data)) {
                return $data;
            }
            return 0;
        };

        return $process($json);
    }
}
