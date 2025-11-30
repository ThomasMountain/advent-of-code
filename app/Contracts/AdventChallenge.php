<?php

namespace App\Contracts;

interface AdventChallenge
{
    public function handleStep1(string $input): mixed;

    public function handleStep2(string $input): mixed;
}
