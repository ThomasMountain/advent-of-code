<?php

namespace App\Console\Commands\Y2015;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Day1 extends Command
{
    protected $signature = 'app:2015-day1';

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting processing day 1');
        $startTime = now();
        $input = Storage::get('input/2015/day1.txt');

        $this->info('Step 1: '.$this->step1($input));
        $this->info('Step 2: '.$this->step2($input));
        $this->info('Finished processing day 1 in '.$startTime->diffInSeconds(now()).' seconds');
    }

    public function step1(string $input): int
    {
        $openBracket = Str::substrCount($input, '(');
        $closeBracket = Str::substrCount($input, ')');

        return $openBracket - $closeBracket;
    }

    public function step2(string $input): int
    {
        $floor = 0;
        foreach (str_split($input) as $index => $char) {
            $floor += ($char === '(') ? 1 : -1;

            if ($floor === -1) {
                return $index + 1; // Return 1-based index
            }
        }
    }
}
