<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Day3 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        $total = 0;
        foreach ($this->getLines($input) as $line) {
            if ($line === '') {
                continue;
            }
            $array = str_split($line);
            // We can't use the last element in the list because the 2nd element has to be after it
            $trimmedArray = array_slice($array, 0, -1);
            $largest = max($trimmedArray);

            $firstIndex = Arr::first(array_keys($array, $largest));

            // Get the elements in the array after the first index of the largest
            $rest = array_slice($array, $firstIndex + 1);
            $secondLargest = max($rest);

            $voltage = (int) ($largest.$secondLargest);

            $total = $total + $voltage;
        }

        return $total;
    }

    public function handleStep2(string $input): mixed
    {
        $total = 0;
        // Now 12 elements need to be turned on, and we need to get the largest number we can make from the 15 digits provided
        foreach ($this->getLines($input) as $line) {
            // Skip empty lines
            if ($line === '') {
                continue;
            }

            $digits = str_split($line);
            $result = [];
            $remainingToPick = 12;
            $start = 0;

            while ($remainingToPick > 0) {
                $end = count($digits) - $remainingToPick;
                $maxDigit = -1;
                $maxIndex = -1;
                for ($i = $start; $i <= $end; $i++) {
                    $currentDigit = (int)$digits[$i];
                    if ($currentDigit > $maxDigit) {
                        $maxDigit = $currentDigit;
                        $maxIndex = $i;
                    }
                }
                $result[] = $maxDigit;
                $start = $maxIndex + 1;
                $remainingToPick--;
            }

            $number = (int)implode('', $result);
            $total += $number;

        }

        return $total;
    }
}
