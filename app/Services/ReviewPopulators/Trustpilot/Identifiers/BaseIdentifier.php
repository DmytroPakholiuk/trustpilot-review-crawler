<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

/**
 * Identifier here is a wrapper for a regular expression. Just so that we avoid using "magical strings" who knows where
 */
abstract class BaseIdentifier
{
    public abstract function getRegex(): string;

    public function checkIfMatches(string $string): bool
    {
        return preg_match($this->getRegex(), $string);
    }

    public function getFullMatches(string $string): array
    {
        preg_match_all($this->getRegex(), $string, $matches, PREG_PATTERN_ORDER);

        return $matches[0];
    }

    public function getFirstSubMatch(string $string): ?string
    {
        return $this->getSubMatches($string) ? $this->getSubMatches($string)[0] : null;
    }

    public function getSubMatches(string $string): array
    {
        preg_match_all($this->getRegex(), $string, $matches, PREG_PATTERN_ORDER);

        return $matches[1];
    }

    public function split(string $string): array
    {
        return preg_split($this->getRegex(), $string);
    }
}
