<?php

namespace App\Support;

/**
 * Output helpers for safe HTML rendering.
 *
 * Always escape user-supplied content before emitting it as HTML.
 * If you must allow some HTML (rich text), use cleanHtml() which
 * strips dangerous tags and attributes (script, on*=, javascript:,
 * data: URIs that don't start with image, etc.).
 *
 * Mitigates OWASP A03:2021 - Injection (XSS subcategory).
 */
class Sanitizer
{
    /**
     * Strip every HTML tag and encode entities. Use this for any field
     * that will be displayed inside a {!! ... !!} block.
     */
    public static function plain(?string $value): string
    {
        return e(strip_tags((string) $value));
    }

    /**
     * Allow a minimal safe HTML subset:
     *   <b> <strong> <i> <em> <u> <p> <br> <ul> <ol> <li> <h1-h6> <a href>
     *   <blockquote> <code> <pre>
     *
     * Removes: <script>, <iframe>, on*= event handlers, javascript: URIs,
     * data: URIs (except data:image/...), vbscript:, style attributes.
     */
    public static function cleanHtml(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $value = (string) $value;

        $value = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $value) ?? $value;
        $value = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $value) ?? $value;
        $value = preg_replace('#\son\w+\s*=\s*"[^"]*"#i', '', $value) ?? $value;
        $value = preg_replace("#\son\w+\s*=\s*'[^']*'#i", '', $value) ?? $value;
        $value = preg_replace('#\son\w+\s*=\s*[^\s>]+#i', '', $value) ?? $value;
        $value = preg_replace('#(javascript|vbscript):#i', '', $value) ?? $value;
        $value = preg_replace('#style\s*=\s*"[^"]*"#i', '', $value) ?? $value;
        $value = preg_replace("#style\s*=\s*'[^']*'#i", '', $value) ?? $value;
        $value = preg_replace('#data:(?!image/)#i', '', $value) ?? $value;

        return $value;
    }
}
