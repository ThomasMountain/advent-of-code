<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Day7 extends Command
{

    const USES_SAMPLE = false;

    protected $signature = 'app:day7';

    protected $description = 'Command description';
    private ?string $data;

    public function handle()
    {
        $this->loadData();

//        $this->info('Step 1 : '.$this->step1());
        $this->info('Step 2 : '.$this->step2());
    }

    public function step1(): int
    {
        // Manipulate the data
        $lines = explode(PHP_EOL, $this->data);
        foreach ($lines as $key => $line) {
            $answers[$key] = Str::before($line, ':');
            $values[$key] = explode(' ', Str::after($line, ': '));
        }

        $totalAnswer = 0;
        foreach ($answers as $key => $answerValue) {
            if ($this->canBeMadeTrue($answerValue, $values[$key])) {
                $totalAnswer = $totalAnswer + $answerValue;
            }
        }

        return $totalAnswer;
    }

    public function step2(): int
    {
        $lines = explode(PHP_EOL, $this->data);
        foreach ($lines as $key => $line) {
            $answers[$key] = Str::before($line, ':');
            $values[$key] = explode(' ', Str::after($line, ': '));
        }

        $totalAnswer = 0;
        foreach ($answers as $key => $answerValue) {
            if ($this->canBeMadeTrueWithConcat($answerValue, $values[$key])) {
                $totalAnswer = $totalAnswer + $answerValue;
            }
        }

        return $totalAnswer;
    }

    public function loadData()
    {
        $this->data = Storage::get(self::USES_SAMPLE ? 'sample/day-7.txt' : 'input/day-7.txt');
    }

    private function canBeMadeTrue(int $answer, mixed $values)
    {
        if (array_sum($values) === $answer || array_product($values) === $answer) {
            return true;
        }

        // Helper function to recursively evaluate all combinations
        $evaluate = function (array $values, int $target, int $currentResult, int $index) use (&$evaluate): bool {
            // If we've used all values, check if the current result matches the target
            if ($index === count($values)) {
                return $currentResult === $target;
            }

            // Pick the next value
            $nextValue = $values[$index];

            // Try adding the next value
            if ($evaluate($values, $target, $currentResult + $nextValue, $index + 1)) {
                return true;
            }

            // Try multiplying by the next value
            if ($evaluate($values, $target, $currentResult * $nextValue, $index + 1)) {
                return true;
            }

            // If neither operation works, return false
            return false;
        };

        // Start the recursive evaluation with the first value
        return $evaluate($values, $answer, $values[0], 1);
    }

    private function canBeMadeTrueWithConcat(int $answer, array $values): bool
    {
        // Base case: Check if the array sum, product, or concatenation matches the answer
        if (array_sum($values) === $answer || array_product($values) === $answer) {
            return true;
        }

        $evaluate = function (array $values, int $target, int $currentResult, int $index) use (&$evaluate): bool {
            // If we've used all values, check if the current result matches the target
            if ($index === count($values)) {
                return $currentResult === $target;
            }

            // Pick the next value
            $nextValue = $values[$index];

            // Try adding the next value
            if ($evaluate($values, $target, $currentResult + $nextValue, $index + 1)) {
                return true;
            }

            // Try multiplying by the next value
            if ($evaluate($values, $target, $currentResult * $nextValue, $index + 1)) {
                return true;
            }

            // Try concatenating with the next value
            $concatenatedResult = intval(strval($currentResult).strval($nextValue));
            if ($evaluate($values, $target, $concatenatedResult, $index + 1)) {
                return true;
            }

            // If no operations work, return false
            return false;
        };

        // Start the recursive evaluation with the first value
        return $evaluate($values, $answer, $values[0], 1);
    }

// Example function to calculate the total calibration result
    public function calculateTotalCalibration(array $testCases): int
    {
        $total = 0;

        foreach ($testCases as $testCase) {
            [$answer, $values] = $testCase;

            if ($this->canBeMadeTrueWithConcat($answer, $values)) {
                $total += $answer;
            }
        }

        return $total;
    }

}
