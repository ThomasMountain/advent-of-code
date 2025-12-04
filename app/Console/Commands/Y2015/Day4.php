<?php

namespace App\Console\Commands\Y2015;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Day4 extends Command
{
    protected $signature = 'app:2015-day4';

    protected $description = 'Command description';

    public function handle()
    {
        $this->input = 'iwrupvqb';

        $this->info('Step 1 Result: '.$this->step1());
        $this->info('Step 1 Result: '.$this->step2());
    }

    public function step1(): int
    {
        $number = 0;
        while (true) {
            $string = $this->input.$number;

            if (Str::startsWith(md5($string), '00000')) {
                return $number;
            }

            $number++;
        }
    }

    public function step2()
    {
        $number = 0;
        while (true) {
            $string = $this->input.$number;

            if (Str::startsWith(md5($string), '000000')) {
                return $number;
            }

            $number++;
        }
    }
}
