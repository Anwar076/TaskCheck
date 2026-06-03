<?php

namespace App\Helpers;

class MetricValidationHelper
{
    public static function buildFromFormData(array $taskData): ?array
    {
        $metricType = $taskData['metric_type'] ?? null;
        if (!$metricType) {
            return null;
        }

        $unit = trim((string) ($taskData['metric_unit'] ?? ''));
        if ($unit === '') {
            $unit = $metricType === 'ph' ? 'pH' : '°C';
        }

        $min = isset($taskData['metric_min']) && $taskData['metric_min'] !== '' && $taskData['metric_min'] !== null
            ? (float) $taskData['metric_min']
            : null;
        $max = isset($taskData['metric_max']) && $taskData['metric_max'] !== '' && $taskData['metric_max'] !== null
            ? (float) $taskData['metric_max']
            : null;

        return [
            'metric' => $metricType,
            'unit' => $unit,
            'min' => $min,
            'max' => $max,
            'comparison' => $taskData['metric_comparison'] ?? 'lte',
        ];
    }

    public static function parseValueFromProofText(?string $proofText): ?float
    {
        if (!$proofText) {
            return null;
        }

        if (!preg_match('/-?\d+(?:[.,]\d+)?/', $proofText, $matches)) {
            return null;
        }

        return (float) str_replace(',', '.', $matches[0]);
    }

    public static function isDeviation(?array $validationRules, ?string $proofText): bool
    {
        if (!is_array($validationRules) || empty($validationRules['metric'])) {
            return false;
        }

        $value = self::parseValueFromProofText($proofText);
        if ($value === null) {
            return false;
        }

        $min = isset($validationRules['min']) && is_numeric($validationRules['min'])
            ? (float) $validationRules['min']
            : null;
        $max = isset($validationRules['max']) && is_numeric($validationRules['max'])
            ? (float) $validationRules['max']
            : null;
        $comparison = $validationRules['comparison'] ?? 'lte';

        if ($min !== null && $value < $min) {
            return true;
        }

        if ($max !== null) {
            if ($comparison === 'lt') {
                return $value >= $max;
            }

            if ($comparison === 'lte') {
                return $value > $max;
            }

            return $value > $max;
        }

        return false;
    }

    public static function isWithinRange(?array $validationRules, float $value): bool
    {
        return !self::isDeviation($validationRules, (string) $value);
    }

    /**
     * @return array<string, string> Laravel validation keys => messages
     */
    public static function validateFormData(array $taskData, string $keyPrefix = ''): array
    {
        $metricType = $taskData['metric_type'] ?? null;
        if (!$metricType) {
            return [];
        }

        $field = static fn (string $name) => $keyPrefix !== '' ? "{$keyPrefix}.{$name}" : $name;

        $errors = [];
        $unit = trim((string) ($taskData['metric_unit'] ?? ''));
        if ($unit === '') {
            $errors[$field('metric_unit')] = 'Eenheid is verplicht bij een meting.';
        }

        $min = $taskData['metric_min'] ?? null;
        $max = $taskData['metric_max'] ?? null;
        $hasMin = $min !== null && $min !== '';
        $hasMax = $max !== null && $max !== '';

        if (!$hasMin && !$hasMax) {
            $errors[$field('metric_min')] = 'Vul minimaal een minimum of maximum norm in.';
        }

        if ($hasMin && $hasMax && (float) $min > (float) $max) {
            $errors[$field('metric_max')] = 'Maximum moet groter of gelijk zijn aan minimum.';
        }

        return $errors;
    }
}
