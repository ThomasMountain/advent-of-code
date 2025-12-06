<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Day6 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        $cleanedUpArray = [];
        foreach ($this->getLines($input) as $line) {
            $cleanedUpArray = $this->cleanupSpaces($line);
            $data[] = $cleanedUpArray;
        }

        $rows = count($data) - 1;          // last row = operators
        $cols = count($data[0]);

        $results = [];

        for ($col = 0; $col < $cols; $col++) {
            $operator = $data[$rows][$col];
            $operands = [];

            for ($row = 0; $row < $rows; $row++) {
                $operands[] = (int)$data[$row][$col];
            }

            if ($operator === '*') {
                $value = array_product($operands);
            } elseif ($operator === '+') {
                $value = array_sum($operands);
            } else {
                throw new Exception("Unknown operator: {$operator}");
            }

            $results[$col] = $value;
        }

        return array_sum($results);
    }

    public function handleStep2(string $input): mixed
    {
        return "TODO";
    }

    public function cleanupSpaces($line): array
    {
        // Replace all whitespace sequences with one space
        $cleanedLine = preg_replace('/\s+/', ' ', trim($line));

        return explode(' ', $cleanedLine);
    }
}
