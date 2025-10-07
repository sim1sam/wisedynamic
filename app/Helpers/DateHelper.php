<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format a date with the application timezone
     *
     * @param mixed $date The date to format (Carbon instance, string, or timestamp)
     * @param string $format The format to use (default: 'M d, Y H:i')
     * @return string The formatted date
     */
    public static function formatDate($date, $format = 'M d, Y H:i')
    {
        if (!$date) {
            return '';
        }

        if (!($date instanceof Carbon)) {
            $date = Carbon::parse($date);
        }

        // Ensure the date is in the application timezone
        return $date->setTimezone(config('app.timezone'))->format($format);
    }

    /**
     * Format a date with time (hours and minutes)
     *
     * @param mixed $date The date to format
     * @return string The formatted date with time
     */
    public static function formatDateTime($date)
    {
        return self::formatDate($date, 'M d, Y H:i');
    }

    /**
     * Format a date with time including seconds
     *
     * @param mixed $date The date to format
     * @return string The formatted date with time and seconds
     */
    public static function formatDateTimeWithSeconds($date)
    {
        return self::formatDate($date, 'M d, Y H:i:s');
    }

    /**
     * Format a date with 12-hour time format
     *
     * @param mixed $date The date to format
     * @return string The formatted date with 12-hour time
     */
    public static function formatDateTime12Hour($date)
    {
        return self::formatDate($date, 'M d, Y h:i A');
    }

    /**
     * Format a date only (no time)
     *
     * @param mixed $date The date to format
     * @return string The formatted date
     */
    public static function formatDateOnly($date)
    {
        return self::formatDate($date, 'M d, Y');
    }
}
