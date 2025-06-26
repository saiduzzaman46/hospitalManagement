<?php
function format_availability($rawAvailability)
{
    if (empty($rawAvailability) || strpos($rawAvailability, ',') === false) {
        return $rawAvailability ?: 'Not specified';
    }

    // Split day part and time part
    [$days, $timeRange] = explode(',', $rawAvailability, 2);
    $days = trim($days);
    $timeRange = trim($timeRange);

    // Split start and end times
    $times = explode('-', $timeRange);
    if (count($times) !== 2) {
        return $rawAvailability; // return raw if time range is malformed
    }

    $startTime = trim($times[0]);
    $endTime = trim($times[1]);

    // Convert to AM/PM format
    $formattedStart = date("g:i A", strtotime($startTime));
    $formattedEnd = date("g:i A", strtotime($endTime));

    return "$days, $formattedStart - $formattedEnd";
}
