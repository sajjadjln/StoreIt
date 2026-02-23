<?php

namespace App\Contracts;

use App\Models\User;

interface TokenManagerContract
{
    public function generate(User $user): string;
}