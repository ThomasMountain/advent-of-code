<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Day1 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        $position = 50;
        $total = 0;

        foreach ($this->getLines($input) as $line) {

            $direction = Str::take($line, 1);

            $change = (int)Str::numbers($line);
            if ($direction === 'L') {
                [$position, $rotations] = $this->moveLeft($position, $change);
            } else {
                [$position, $rotations] = $this->moveRight($position, $change);
            }

            if ($position === 0) {
                $total++;
            }
        }

        return $total;
    }

    public function handleStep2(string $input): mixed
    {
        $position = 50;
        $total = 0;

        foreach ($this->getLines($input) as $line) {

            $direction = Str::take($line, 1);

            $change = (int)Str::numbers($line);
            if ($direction === 'L') {
                [$position, $rotations] = $this->moveLeft($position, $change);
            } else {
                [$position, $rotations] = $this->moveRight($position, $change);
            }
            $total = $total + $rotations;
        }

        return $total;
    }

    protected function moveLeft(int $position, int $change): array
    {
        $partial = $change % 100;
        $new = $position - $partial;
        $final = $new >= 0 ? $new : (100 + $new);

        $firstZero = $position % 100;
        $firstZero = $firstZero === 0 ? 100 : $firstZero;

        if ($firstZero > $change) {
            $hits = 0;
        } else {
            $hits = intdiv($change - $firstZero, 100) + 1;
        }
        return [
            $final, $hits
        ];
    }

    protected function moveRight(int $position, int $change): array
    {
        $partial = $change % 100;

        $new = $position + $partial;
        if ($new < 100) {
            $final = $new;
        } elseif ($new === 100) {
            $final = 0;
        } else {
            $final = $new - 100;
        }

        $firstZero = (100 - ($position % 100)) % 100;
        $firstZero = $firstZero === 0 ? 100 : $firstZero;

        $hits = $firstZero > $change ? 0 : intdiv($change - $firstZero, 100) + 1;

        return [
            $final, $hits
        ];
    }
}
