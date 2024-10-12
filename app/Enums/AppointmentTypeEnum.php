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
    case Number = 'number';
    case DoubleSmallNumber = 'double_small_number';

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
            self::Number => 'Number Input',
            self::DoubleSmallNumber => 'Double Small Number Inputs (Two Inputs)',
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
            self::SmallText => [
                'type' => 'string',
                'rules' => [
                    'required' => true,
                    'max' => 255,
                    'min' => 3
                ]
            ],
             self::LargeText => [
                'type' => 'textarea',
                'rules' => [
                    'required' => true,
                    'max' => 400,
                    'min' => 5
                ]
            ],
            self::SingleCheck => [
                'options' => [
                    ['label' => 'Yes', 'value' => true],
                    ['label' => 'No', 'value' => false]
                ],
                'rules' => [
                    'required' => true,
                ]
            ],
            self::MultipleCheck => [
                'options' => ['Option A', 'Option B', 'Option C'],
                'type' => 'checkboxes',
                'rules' => [
                    'required' => true,
                    'min' => 1
                ]
            ],
            self::SingleCheckChoices => [
                'options' => [
                    ['label' => 'Yes', 'value' => true],
                    ['label' => 'No', 'value' => false]
                ],
                'options_rules' => [
                    'required' => true,
                ],
                'choices' => ['Choice 1', 'Choice 2'],
                'choices_rules' => [
                    'required' => true,
                    'min' => 1
                ]
            ],
            self::SingleCheckWithText => [
                'type' => 'checkbox_with_text',
                'options' => [
                    ['label' => 'Yes', 'value' => true], // true option ,
                    ['label' => 'No', 'value' => false]
                ],
                'options_rules' => [
                    'required' => true,
                ],
                'key' => 'Enter additional text...',
                'text_rules'=> [
                    'required' => true,
                    'max' => 200,
                    'min' => 5
                ]
            ],
            self::DoubleSmallText => [
                'first' => [
                    'label' => 'First small input',
                    'type' => 'text',
                    'rules' => [
                        'required' => true,
                        'max' => 100,
                        'min' => 2
                    ]
                ],
                'second' => [
                    'label' => 'Second small input...',
                    'type' => 'text',
                    'rules' => [
                        'required' => true,
                        'max' => 100,
                        'min' => 2
                    ]
                ]
            ],
            self::Number => [
                'type' => 'number',
                'rules' => [
                    'required' => true,
                    'max' => 10000,
                    'min' => 0
                ]
            ],
            self::DoubleSmallNumber => [
                'first' => [
                    'label' => 'First small input',
                    'type' => 'number',
                    'rules' => [
                        'required' => true,
                        'max' => 10000,
                        'min' => 0
                    ]
                ],
                'second' => [
                    'label' => 'Second small input...',
                    'type' => 'number',
                    'rules' => [
                        'required' => true,
                        'max' => 10000,
                        'min' => 2
                    ]
                ]
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
