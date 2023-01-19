<?php

namespace Tests\Helpers;

class TestStr
{
    /**
     * function addRandom
     *
     * @param ?string $currentString
     * @param ?int $wordsToCreate
     *
     * @return string
     */
    public static function addRandom(?string $currentString = null, ?int $wordsToCreate = null): string
    {
        $wordsToCreate ??= rand(2, 6);

        $words[] = trim($currentString) ?? \fake()->words($wordsToCreate, true);
        $words = [... $words, ...fake()->words($wordsToCreate)];
        $words[] = rand(1000, 9999);

        return \ucwords(
            trim(
                implode(' ', $words)
            )
        );
    }
}
