<?php

namespace App\Console\Commands;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RunAdventOfCodeChallenge extends Command
{
    protected $signature = 'run {day?} {year?}';

    protected $description = 'Command description';

    // app/Console/Commands/AocCommand.php

    public function handle(): void
    {
        $dayArgument = $this->argument('day');
        $day = (int)($dayArgument ?? now()->format('d'));

        $yearArgument = $this->argument('year');
        $year = (int)($yearArgument ?? now()->format('Y'));

        $challengeClassName = "App\\Services\\AdventOfCode\\Y{$year}\\Day{$day}";

        if (!class_exists($challengeClassName)) {
            $this->error("Challenge not found: {$challengeClassName}");
            return;
        }

        try {
            /** @var BaseChallenge $challenge */
            $challenge = app($challengeClassName);

            $input = $challenge->getInput($day);

            $this->info("--- Running Advent of Code {$year} Day {$day} ---");

            $this->info('Starting Step 1...');

            $startTime1 = microtime(true);
            $step1Answer = $challenge->handleStep1($input);

            $duration1 = microtime(true) - $startTime1;

            $this->info('Step 1 Answer: ' . $step1Answer);
            $this->comment('Time taken: ' . number_format($duration1 * 1000, 2) . 'ms');
            $this->newLine();

            $this->info('Starting Step 2...');

            $startTime2 = microtime(true);
            $step2Answer = $challenge->handleStep2($input);

            $duration2 = microtime(true) - $startTime2;

            $this->info('Step 2 Answer: ' . $step2Answer);
            $this->comment('Time taken: ' . number_format($duration2 * 1000, 2) . 'ms');
            $this->newLine();

        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
