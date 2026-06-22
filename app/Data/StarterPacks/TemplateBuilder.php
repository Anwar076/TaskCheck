<?php

namespace App\Data\StarterPacks;

final class TemplateBuilder
{
    /**
     * @param  array<int, string|array<string, mixed>>  $items
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public static function checklist(
        string $name,
        string $frequencyLabel,
        string $frequencyType,
        array $items,
        array $options = [],
    ): array {
        $tasks = [];
        $photoOnFail = (bool) ($options['photo_on_fail'] ?? false);
        $category = $options['category'] ?? 'food_safety';

        foreach ($items as $item) {
            if (is_string($item)) {
                $tasks[] = self::yesNoTask($item, $photoOnFail);
                continue;
            }

            $tasks[] = $item;
        }

        if (! empty($options['deviation_action'])) {
            $tasks[] = [
                'title' => 'Afwijking: omschrijf herstelactie',
                'description' => $options['deviation_action'],
                'required_proof_type' => 'text',
                'validation_rules' => [
                    'answer_type' => 'text',
                    'required_on_fail' => true,
                ],
            ];
        }

        return [
            'name' => $name,
            'description' => $options['description'] ?? "Compliance-checklist: {$name}. Gebaseerd op HACCP-principes en NVWA-richtlijnen.",
            'icon' => $options['icon'] ?? 'clipboard-outline',
            'frequency_label' => $frequencyLabel,
            'frequency_type' => $frequencyType,
            'category' => $category,
            'source_basis' => $options['source_basis'] ?? 'NVWA / HACCP',
            'tasks' => $tasks,
        ];
    }

    public static function yesNoTask(string $question, bool $photoOnFail = false): array
    {
        return [
            'title' => $question,
            'required_proof_type' => $photoOnFail ? 'photo' : 'none',
            'validation_rules' => [
                'answer_type' => 'yes_no',
                'requires_photo_on_fail' => $photoOnFail,
            ],
        ];
    }

    public static function temperatureTask(string $label, float $max = 7.0, bool $critical = true): array
    {
        return [
            'title' => $label,
            'required_proof_type' => 'photo',
            'validation_rules' => [
                'answer_type' => 'temperature',
                'metric' => 'temperature',
                'max' => $max,
                'comparison' => 'lte',
                'unit' => '°C',
                'critical' => $critical,
            ],
        ];
    }

    public static function freezerTask(string $label, float $max = -18.0): array
    {
        return self::temperatureTask($label, $max, true);
    }
}
