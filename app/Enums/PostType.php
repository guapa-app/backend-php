<?php

namespace App\Enums;

enum PostType: string
{
    case Blog = 'blog';
    case Review = 'review';
    case Vote = 'vote';
    case Question = 'question';
    case Story = 'story';


    public static function availableForCreateByUser(): array
    {
        return [
            self::Review->value,
            self::Vote->value,
            self::Question->value,
            self::Story->value,
        ];
    }
}
