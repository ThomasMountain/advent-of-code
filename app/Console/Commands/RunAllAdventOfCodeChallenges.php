<?php

namespace App\Console\Commands;

use App\Contracts\AdventChallenge;
use App\Models\Result;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RunAllAdventOfCodeChallenges extends Command
{
    protected $signature = 'run-all';

    protected $description = '';

    public function handle(): void
    {
        for ($x=1; $x<=12; $x++) {
            $this->runChallenge($x, 2025);
        }
    }

    private function runChallenge(int $day, int $year): ?int
    {
        $class = "App\\Services\\AdventOfCode\\Y{$year}\\Day{$day}";

        if (!class_exists($class)) {
            $this->error("Challenge class {$class} does not exist.");
            return null;
        }

        /** @var BaseChallenge $challenge */
        $challenge = app($class);

        $this->info("--- Running Advent of Code {$year} Day {$day} ---");

        $input = $challenge->getInput($day, $year);

        $this->runStep($challenge, 'handleStep1', 'Step 1', $input);
        $this->runStep($challenge, 'handleStep2', 'Step 2', $input);

        return 0;
    }

    private function runStep(BaseChallenge $challenge, string $methodName, string $label, $input): mixed
    {
        $this->info("Starting {$label}...");

        $start = microtime(true);
        $answer = $challenge->{$methodName}($input);
        $duration = microtime(true) - $start;

        $this->storeRun($challenge, $methodName, $label, $answer, $duration);

        $this->info("{$label} Answer: " . $answer);
        $this->comment('Time taken: ' . number_format($duration * 1000, 2) . 'ms');
        $this->newLine();

        $this->storeRun($challenge, $methodName, $label, $answer, $duration);

        return $answer;
    }

    private function storeRun(BaseChallenge $challenge, string $methodName, string $label, $answer, $duration)
    {
        Result::create([
            'year' => 2025,
            'day' => (int)Str::afterLast(get_class($challenge), 'Day'),
            'step' => $methodName === 'handleStep1' ? 1 : 2,
            'result' => is_numeric($answer) ? (int)$answer : 0,
            'time_taken_ms' => (int)($duration * 1000),
        ]);
    }
}
