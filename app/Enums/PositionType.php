<?php

namespace App\Enums;

enum PositionType: string
{
    case PJ      = 'pj';
    case CLT     = 'clt';
    case ESTAGIO = 'estagio';

    public function label(): string
    {
        return match ($this) {
            self::PJ      => 'Pessoa Jurídica',
            self::CLT     => 'CLT',
            self::ESTAGIO => 'Estágio',
        };
    }

    public function requiresSalaryAndSchedule(): bool
    {
        return in_array($this, [self::CLT, self::ESTAGIO]);
    }

    public function minSalary(): ?float
    {
        return match ($this) {
            self::CLT => 1212.00,
            default   => null,
        };
    }

    public function maxScheduleHours(): ?int
    {
        return match ($this) {
            self::ESTAGIO => 6,
            default       => null,
        };
    }
}
