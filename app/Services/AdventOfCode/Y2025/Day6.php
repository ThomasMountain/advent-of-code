<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;

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
        $grid = $this->parseGrid($input);

        $w = $grid->getWidth();
        $h = $grid->getHeight();

        // Find blank columns (columns that are all spaces)
        $isBlank = [];
        for ($x = 0; $x < $w; $x++) {
            $blank = true;
            for ($y = 0; $y < $h; $y++) {
                if ($grid->getCell($x, $y) !== ' ') { $blank = false; break; }
            }
            $isBlank[$x] = $blank;
        }

        // Group consecutive non-blank columns into problems
        $groups = [];
        $inGroup = false;
        $groupStart = 0;
        for ($x = 0; $x < $w; $x++) {
            if (!$isBlank[$x] && !$inGroup) {
                $inGroup = true;
                $groupStart = $x;
            } elseif ($isBlank[$x] && $inGroup) {
                $groups[] = [$groupStart, $x - 1];
                $inGroup = false;
            }
        }
        if ($inGroup) { $groups[] = [$groupStart, $w - 1]; }

        $grandTotal = 0;

        // Problems are read right-to-left
        $groups = array_reverse($groups);

        foreach ($groups as [$x0, $x1]) {
            // operator is the bottom cell of the leftmost column of the group
            $operator = $grid->getCell($x0, $h - 1);

            // collect columns for this problem, reading columns right-to-left
            $numbers = [];
            for ($x = $x1; $x >= $x0; $x--) {
                // build the column's digits top-to-bottom except the bottom operator row
                $digits = '';
                for ($y = 0; $y < $h - 1; $y++) {
                    $ch = $grid->getCell($x, $y);
                    if ($ch !== ' ') $digits .= $ch;
                }
                if ($digits !== '') $numbers[] = intval($digits);
            }

            if (count($numbers) === 0) continue;
            $result = array_shift($numbers);
            foreach ($numbers as $n) {
                if ($operator === '+') {
                    $result += $n;
                } elseif ($operator === '*') {
                    $result *= $n;
                } else {
                    throw new RuntimeException("Unknown operator '{$operator}' in problem starting at column {$x0}");
                }
            }

            $grandTotal += $result;
        }

        return $grandTotal;
    }

    public function cleanupSpaces($line): array
    {
        // Replace all whitespace sequences with one space
        $cleanedLine = preg_replace('/\s+/', ' ', trim($line));

        return explode(' ', $cleanedLine);
    }
}
