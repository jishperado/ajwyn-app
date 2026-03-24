<?php

function slugify($text)
{
    // Convert to lowercase
    $text = strtolower($text);

    // Replace non-letter or digits with hyphens
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);

    // Trim hyphens
    return trim($text, '-');
}
