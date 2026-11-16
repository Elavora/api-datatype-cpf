<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Brazil;

use Elavora\Api\DataTypes\AbstractDataType;
use Elavora\Api\Framework\Contracts\DescribesType;

final readonly class Cpf extends AbstractDataType implements DescribesType
{
    /**
     * Descreve o tipo para a documentacao publica (OPTIONS).
     *
     * @return array<string, mixed>
     */
    public static function documentedType(): array
    {
        return [
            'type' => 'string',
            'mask' => '000.000.000-00',
            'pattern' => '^(\d{3}\.\d{3}\.\d{3}-\d{2}|\d{11})$',
            'example' => '529.982.247-25',
            'description' => 'CPF com ou sem mascara; value() devolve apenas os 11 digitos.',
        ];
    }

    /**
     * Verifica se o valor e um CPF valido.
     */
    public static function isValid(mixed $value): bool
    {
        $digits = self::digits($value);
        if (strlen($digits) !== 11 || preg_match('/^(\d)\1{10}$/', $digits) === 1) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += (int) $digits[$i] * (($t + 1) - $i);
            }

            $checkDigit = ((10 * $sum) % 11) % 10;
            if ((int) $digits[$t] !== $checkDigit) {
                return false;
            }
        }

        return true;
    }

    protected static function normalize(mixed $value): string
    {
        return self::digits($value);
    }

    private static function digits(mixed $value): string
    {
        return preg_replace('/\D/', '', (string) $value) ?? '';
    }
}
