<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Day2 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        $matches = [];

        $csvSeparated = $this->getCsv($input);
        foreach ($csvSeparated as $line) {
            [$first, $last] = explode('-', $line);
            $range = range($first, $last);
            foreach ($range as $index => $value) {
                if ($this->isInvalidDueToRepetition($value)) {
                    $matches[] = $value;
                }
            }
        }

        return array_sum($matches);
    }

    public function handleStep2(string $input): mixed
    {
        $matches = [];

        $csvSeparated = $this->getCsv($input);
        foreach ($csvSeparated as $line) {
            [$first, $last] = explode('-', $line);

            $range = range($first, $last);
            foreach ($range as $index => $value) {
                if ($this->isInValidDueToMultipleRepetitions($value)) {
                    $matches[] = $value;
                }
            }
        }

        return array_sum($matches);
    }

    private function isInvalidDueToRepetition(mixed $value): bool
    {
        $str = (string)$value;
        $len = strlen($str);

        if ($len % 2 !== 0) {
            return false;
        }

        $half = $len / 2;

        return substr($str, 0, $half) === substr($str, $half);
    }

    private function isInValidDueToMultipleRepetitions($value): bool
    {
        $value = (string)$value;
        $length = strlen($value);

        if ($length === 1) {
            return false;
        }

        for ($chunkSize = 1; $chunkSize <= $length / 2; $chunkSize++) {

            if ($length % $chunkSize !== 0) {
                continue;
            }

            $chunk = substr($value, 0, $chunkSize);

            $reconstructed = str_repeat($chunk, $length / $chunkSize);

            if ($reconstructed === $value) {
                return true;
            }
        }

        return false;

    }

    public function hasOddLength($length): bool
    {
        return $length % 2 !== 0;
    }
}
