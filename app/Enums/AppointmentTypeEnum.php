<?php

namespace App\Enums;

use App\Enums\Traits\EnumValuesTrait;

enum AppointmentTypeEnum: string
{
    use EnumValuesTrait;

    case SmallText = 'small_text';
    case LargeText = 'large_text';
    case SingleCheck = 'single_check';
    case MultipleCheck = 'multiple_check';
    case SingleCheckChoices = 'single_check_choices';
    case SingleCheckWithText = 'single_check_with_text';
    case DoubleSmallText = 'double_small_text';

    // Method to return the human-readable label for each field type
    public function label(): string
    {
        return match ($this) {
            self::SmallText => 'Small Text Input',
            self::LargeText => 'Large Text Input',
            self::SingleCheck => 'Single Check (One Option)',
            self::MultipleCheck => 'Multiple Check (Multiple Options)',
            self::SingleCheckChoices => 'Single Check with Choices (Yes/No)',
            self::SingleCheckWithText => 'Single Check with Text Input',
            self::DoubleSmallText => 'Double Small Text Inputs (Two Inputs)',
        };
    }

    // Static method to get the options for the Select field
    public static function options(): array
    {
        return array_map(fn($enum) => $enum->label(), self::cases());
    }

    // Method to return the option template for each field type
    public function defaultOptionsTemplate(): array
    {
        return match ($this) {
            self::SmallText, self::LargeText => [
                'key' => 'Enter a Question...',
            ],
            self::SingleCheck => [
                'options' => ['Option 1', 'Option 2', 'Option 3'],
            ],
            self::MultipleCheck => [
                'options' => ['Option A', 'Option B', 'Option C'],
            ],
            self::SingleCheckChoices => [
                'options' => ['Yes', 'No'],
                'choices' => ['Choice 1', 'Choice 2'],
            ],
            self::SingleCheckWithText => [
                'options' => ['Yes', 'No'],
                'key' => 'Enter additional text...',
            ],
            self::DoubleSmallText => [
                'first_key' => 'First small input...',
                'second_key' => 'Second small input...',
            ],
        };
    }

    // Static method to retrieve default templates for all types
    public static function templates(): array
    {
        $templates = [];
        foreach (self::cases() as $case) {
            $templates[$case->value] = json_encode($case->defaultOptionsTemplate(), JSON_PRETTY_PRINT);
        }
        return $templates;
    }
}
