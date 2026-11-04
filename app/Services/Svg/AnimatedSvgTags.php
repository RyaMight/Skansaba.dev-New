<?php

namespace App\Services\Svg;

use enshrined\svgSanitize\data\AllowedTags;
use enshrined\svgSanitize\data\TagInterface;

/**
 * Extends the default SVG tag whitelist with SMIL animation elements
 * (<animate>, <set>) so animated profile SVGs keep working.
 * Dangerous tags (script, foreignObject, use, etc.) remain excluded.
 */
class AnimatedSvgTags extends AllowedTags implements TagInterface
{
    /**
     * Additional SMIL animation tags that are safe to keep.
     *
     * @var array<int, string>
     */
    protected static array $extraTags = [
        'animate',
        'set',
    ];

    public static function getTags(): array
    {
        return array_merge(parent::getTags(), static::$extraTags);
    }
}
