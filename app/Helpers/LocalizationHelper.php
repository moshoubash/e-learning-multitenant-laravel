<?php

namespace App\Helpers;

/**
 * Localization Helper
 * 
 * Provides convenient methods for accessing localized messages
 * throughout the application.
 */
class LocalizationHelper
{
    /**
     * Get a localized message by key
     * 
     * @param string $key The message key (e.g., 'auth.email')
     * @param array $replace Replacements for placeholders
     * @param string|null $locale The locale to use (uses app locale by default)
     * @return string The localized message
     */
    public static function get(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return __("messages.{$key}", $replace, $locale);
    }

    /**
     * Alias for get() method - shorter syntax
     */
    public static function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        return self::get($key, $replace, $locale);
    }

    /**
     * Get localized message in specific language
     */
    public static function transAr(string $key, array $replace = []): string
    {
        return self::get($key, $replace, 'ar');
    }

    /**
     * Get localized message in English
     */
    public static function transEn(string $key, array $replace = []): string
    {
        return self::get($key, $replace, 'en');
    }

    /**
     * Check if a localization key exists
     */
    public static function has(string $key, ?string $locale = null): bool
    {
        $locale = $locale ?? app()->getLocale();
        return trans_exists("messages.{$key}", $locale);
    }

    /**
     * Get all messages for current locale
     */
    public static function getAll(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        return trans("messages", [], $locale) ?? [];
    }

    /**
     * Get current application locale
     */
    public static function getCurrentLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Set application locale
     */
    public static function setLocale(string $locale): void
    {
        app()->setLocale($locale);
    }

    /**
     * Check if current locale is Arabic
     */
    public static function isArabic(): bool
    {
        return self::getCurrentLocale() === 'ar';
    }

    /**
     * Check if current locale is English
     */
    public static function isEnglish(): bool
    {
        return self::getCurrentLocale() === 'en';
    }

    /**
     * Get text direction for current locale (RTL for Arabic, LTR for others)
     */
    public static function getTextDirection(): string
    {
        return self::isArabic() ? 'rtl' : 'ltr';
    }

    /**
     * Get HTML lang attribute value
     */
    public static function getHtmlLang(): string
    {
        return self::getCurrentLocale();
    }
}
