<?php

namespace App\Services\AdventOfCode\Y2024;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Day1 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        [$left, $right] = $this->processInput($input);

        $sortedLeft = array_values(Arr::sort($left));
        $sortedRight = array_values(Arr::sort($right));

        $value = 0;
        for ($i = 0; $i < count($sortedLeft); $i++) {
            $value += abs((int)$sortedLeft[$i] - (int)$sortedRight[$i]);
        }

        return $value;
    }

    public function handleStep2(string $input): mixed
    {
        [$left, $right] = $this->processInput($input);

        $overallValue = 0;
        foreach ($left as $value) {
            $countInRightArray = count(Arr::where($right, fn($item) => $item === $value));
            $overallValue += ((int)$value * $countInRightArray);
        }

        return $overallValue;
    }

    protected function processInput(string $input): array
    {
        $left = [];
        $right = [];

        foreach ($this->getLines($input) as $line) {
            if (empty($line)) {
                continue;
            } // Handle empty lines

            $left[] = Str::before($line, '   ');
            $right[] = Str::after($line, '   ');
        }

        return [$left, $right];
    }
}
