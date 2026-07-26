<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Cpf\Tests;

use Elavora\Api\DataTypes\Brazil\Cpf;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CpfFormatTest extends TestCase
{
    public function testAcceptsCanonicalAndFormattedCpf(): void
    {
        self::assertSame('52998224725', Cpf::from('52998224725')->value());
        self::assertSame('52998224725', Cpf::from('529.982.247-25')->value());
        self::assertSame('01234567890', Cpf::from('012.345.678-90')->value());
    }

    #[DataProvider('invalidValues')]
    public function testRejectsValuesOutsideSupportedFormats(mixed $value): void
    {
        self::assertFalse(Cpf::isValid($value));
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function invalidValues(): iterable
    {
        yield 'invalid check digit' => ['529.982.247-24'];
        yield 'repeated digits' => ['111.111.111-11'];
        yield 'text around value' => ['prefixo 529.982.247-25 sufixo'];
        yield 'leading space' => [' 529.982.247-25'];
        yield 'trailing space' => ['529.982.247-25 '];
        yield 'partial mask' => ['529.982247-25'];
        yield 'unexpected symbol' => ['529@982.247-25'];
        yield 'integer' => [52998224725];
        yield 'array' => [['529.982.247-25']];
        yield 'object' => [new class {}];
        yield 'null' => [null];
    }
}
