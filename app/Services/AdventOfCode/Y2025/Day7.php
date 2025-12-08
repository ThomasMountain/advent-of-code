<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;

class Day7 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        $grid = $this->parseGrid($input);
        $width = $grid->getWidth();

        // Get the start point
        $sx = null;
        for ($x = 0; $x < $width; $x++) {
            if ($grid->getCell($x, 0) === 'S') {
                $sx = $x;
                break;
            }
        }

        // Each beam is (x, y)
        $queue = [];
        $queue[] = [$sx, 1];   // start below S

        $seen = [];            // seen["x,y"] = true
        $splits = 0;

        while (!empty($queue)) {
            [$x, $y] = array_shift($queue);

            if (!$grid->inBounds($x, $y)) {
                // Stop the beam
                continue;
            }

            $key = "$x,$y";
            if (isset($seen[$key])) {
                // Avoid reprocessing same beam position
                continue;
            }
            $seen[$key] = true;

            $cell = $grid->getCell($x, $y);

            if ($cell === '.') {
                // Just continue downward
                $queue[] = [$x, $y + 1];
            }
            elseif ($cell === '^') {
                // Splitter: count split, stop downward, spawn left/right
                $splits++;
                $queue[] = [$x - 1, $y]; // left
                $queue[] = [$x + 1, $y]; // right
            }
            else {
                continue;
            }
        }

        return $splits;
    }

    public function handleStep2(string $input): mixed
    {
        return "TODO";
    }
}
