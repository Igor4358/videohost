<?php

namespace App\Services\Contracts;

interface VideoValidationInterface
{
    public function extractVideoId(string $url): ?string;
    public function validateUrl(string $url): bool;
}
