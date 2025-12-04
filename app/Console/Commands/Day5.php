<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Day5 extends Command
{
    const USES_SAMPLE = false;

    protected $signature = 'app:day5';

    protected $description = 'Command description';

    public array $fileContent = [];

    public array $invalidLines = [];

    public array $ruleLines = [];

    public function handle(): void
    {
        $this->loadInput();

        $this->info('The answer for step 1: '.$this->step1());
        $this->info('The answer for step 2: '.$this->step2());
    }

    public function loadInput(): void
    {
        $this->fileContent['rules'] = Storage::get(
            self::USES_SAMPLE ? 'input/day-5-sample-rules.txt' : 'input/day-5-rules.txt'
        );
        $this->fileContent['orders'] = Storage::get(
            self::USES_SAMPLE ? 'input/day-5-sample-orders.txt' : 'input/day-5-orders.txt'
        );
    }

    private function step1(): int
    {
        // Iterate through the rules and build an array of before/after

        // Iterate through the order & check each one for any matching rules.
        //      Then look at the following records to see if the following records match the rules

        foreach (array_filter(explode(PHP_EOL, $this->fileContent['rules'])) as $rule) {
            $first = Str::before($rule, '|');
            $second = Str::after($rule, '|');
            $this->ruleLines[] = [
                'first' => $first, 'second' => $second,
            ];
        }

        $invalidLines = [];
        $orders = array_filter(explode(PHP_EOL, $this->fileContent['orders']));
        foreach ($orders as $key => $order) {
            foreach ($this->ruleLines as $rule) {
                if (! $this->orderMatchesRule($order, $rule)) {
                    if (isset($orders[$key])) {
                        $invalidLines[] = $orders[$key];
                        unset($orders[$key]);
                    }
                }
            }
        }
        $this->invalidLines = $invalidLines;

        // Get the middle item of each remaining array
        $total = 0;
        foreach ($orders as $order) {
            $orderArray = explode(',', $order);
            $middle = floor((count($orderArray)) / 2);
            $middleItem = array_slice($orderArray, $middle, 1)[0];
            $total = $total + (int) $middleItem;
        }

        return $total;
    }

    private function step2(): int
    {
        $orders = [];
        foreach ($this->invalidLines as $invalidLine) {
            $orders[] = explode(',', $invalidLine); // Split each invalid line into numbers
        }

        $total = 0;

        foreach ($orders as $order) {
            // Fix the order of this update
            $fixedOrder = $this->reorderPages($order, $this->ruleLines);

            // Find the middle number in the fixed order
            $middleIndex = floor(count($fixedOrder) / 2);
            $middleNumber = $fixedOrder[$middleIndex];

            // Add the middle number to the total
            $total += (int) $middleNumber;
        }

        return $total;
    }

    private function orderMatchesRule(string $order, array $rule): bool
    {
        $order = explode(',', $order);

        if (! in_array($rule['first'], $order)) {
            return true; // Rule does not apply since 'first' is missing
        }

        $firstIndex = array_search($rule['first'], $order);
        $secondIndex = array_search($rule['second'], $order);

        if ($secondIndex === false) {
            return true; // Rule does not apply since 'second' is missing
        }

        return $firstIndex < $secondIndex; // 'First' must come before 'Second'
    }

    private function reorderPages(array $order, array $rules): array
    {
        // Keep checking the rules until the order is correct
        $isChanged = true;
        while ($isChanged) {
            $isChanged = false;

            foreach ($rules as $rule) {
                $first = $rule['first'];
                $second = $rule['second'];

                // Only apply the rule if both numbers are in the current update
                if (in_array($first, $order) && in_array($second, $order)) {
                    $firstIndex = array_search($first, $order);
                    $secondIndex = array_search($second, $order);

                    // If the rule is broken (first comes after second), fix it
                    if ($firstIndex > $secondIndex) {
                        // Swap the two numbers
                        $temp = $order[$firstIndex];
                        $order[$firstIndex] = $order[$secondIndex];
                        $order[$secondIndex] = $temp;
                        $isChanged = true; // Keep checking after making a change
                    }
                }
            }
        }

        return $order; // Return the fixed order
    }
}
