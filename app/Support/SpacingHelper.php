<?php

namespace App\Support;

class SpacingHelper
{
    /**
     * Flip a Tailwind spacing utility (mr-*, ml-*) to its opposite side.
     */
    public static function flip(string $class): string
    {
        $parts = preg_split('/\s+/', trim($class));
        $flipped = array_map(fn ($p) => self::flipClass($p), $parts);
        return implode(' ', $flipped);
    }

    /**
     * Flip a class only if locale is RTL. Otherwise return as-is.
     */
    public static function flipFor(string $class, string $locale): string
    {
        return $locale === 'ar' ? self::flip($class) : $class;
    }

    protected static function flipClass(string $class): string
    {
        if (str_starts_with($class, 'mr-')) {
            return 'ml-' . substr($class, 3);
        }
        if (str_starts_with($class, 'ml-')) {
            return 'mr-' . substr($class, 3);
        }
        if ($class === 'mr' || $class === 'ml') {
            return $class === 'mr' ? 'ml' : 'mr';
        }
        if ($class === 'text-left') {
            return 'text-right';
        }
        if ($class === 'text-right') {
            return 'text-left';
        }
        return $class;
    }
}
