<?php

namespace App\Services\Svg;

use enshrined\svgSanitize\data\AllowedAttributes;
use enshrined\svgSanitize\data\AttributeInterface;

/**
 * Extends the default SVG attribute whitelist with SMIL animation
 * attributes (from, to, calcMode, additive, ...) that the default
 * list strips, which silently breaks animated SVGs.
 *
 * Note: values/from/to on SMIL elements are animation payload data —
 * they are not executed as script. As a hardening measure, SvgService
 * additionally removes any animation targeting href attributes.
 */
class AnimatedSvgAttributes extends AllowedAttributes implements AttributeInterface
{
    /**
     * Additional SMIL animation attributes that are safe to keep.
     *
     * @var array<int, string>
     */
    protected static array $extraAttributes = [
        'from',
        'to',
        'by',
        'values',
        'calcMode',
        'additive',
        'attributeName',
        'attributeType',
        'keyTimes',
        'keySplines',
        'keyPoints',
        'repeatCount',
        'repeatDur',
        'path',
    ];

    public static function getAttributes(): array
    {
        return array_merge(parent::getAttributes(), static::$extraAttributes);
    }
}
