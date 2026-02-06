<?php

namespace App\Enums;

enum CompanyPlan: string
{
    case FREE    = 'free';
    case PREMIUM = 'premium';

    public function label(): string
    {
        return match ($this) {
            self::FREE    => 'Plano Gratuito',
            self::PREMIUM => 'Plano Premium',
        };
    }

    public function jobLimit(): int
    {
        return match ($this) {
            self::FREE    => 5,
            self::PREMIUM => 10,
        };
    }
}
