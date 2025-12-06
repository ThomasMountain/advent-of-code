<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Day5 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        $totalAvailable = 0;

        [$freshArray, $availableArray] = explode(PHP_EOL . PHP_EOL, $input);

        foreach (explode(PHP_EOL, $availableArray) as $availableItem) {
            foreach (explode(PHP_EOL, $freshArray) as $item) {
                [$start, $end] = explode('-', $item);
                if ($availableItem >= $start && $availableItem <= $end) {
                    $totalAvailable++;
                    continue 2;
                }
            }
        }

        return $totalAvailable;
    }

    public function handleStep2(string $input): mixed
    {
        $total = 0;

        [$ranges, $availableArray] = explode(PHP_EOL . PHP_EOL, $input);
        $ranges = explode(PHP_EOL, $ranges);

        $cleanRanges = [];
        foreach ($ranges as $r) {
            list($start, $end) = array_map('intval', explode('-', $r));
            $cleanRanges[] = [$start, $end];
        }

        usort($cleanRanges, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        $merged = [];
        foreach ($cleanRanges as $range) {
            if (empty($merged) || (int)$range[0] > (int)$merged[count($merged) - 1][1] + 1) {
                $merged[] = $range;
            } else {
                $merged[count($merged) - 1][1] =
                    max($merged[count($merged) - 1][1], $range[1]);
            }
        }

        foreach ($merged as $r) {
            $total += $r[1] - $r[0] + 1;
        }

        return $total;
    }
}
