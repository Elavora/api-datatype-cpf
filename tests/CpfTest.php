<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Cpf\Tests;

use Elavora\Api\DataTypes\Brazil\Cpf;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CpfTest extends TestCase
{
    public function testValidatesCpf(): void
    {
        self::assertSame('52998224725', Cpf::from('529.982.247-25')->value());
        self::assertFalse(Cpf::isValid('111.111.111-11'));
    }

    /**
     * @return array<string, array{mixed, bool}>
     */
    public static function isValidProvider(): array
    {
        return [
            'apenas digitos' => ['52998224725', true],
            'com mascara' => ['529.982.247-25', true],
            'com espacos' => [' 529 982 247 25 ', true],
            'zeros a esquerda' => ['000.000.001-91', true],
            'inteiro' => [52998224725, true],
            'letras no meio removidas' => ['529x982y247z25', true],
            'inteiro sem zeros' => [191, false],
            'digito verificador errado' => ['52998224726', false],
            'todos iguais' => ['111.111.111-11', false],
            '10 digitos' => ['5299822472', false],
            '12 digitos' => ['529982247250', false],
            'vazio' => ['', false],
            'null' => [null, false],
            'false' => [false, false],
            'sem digitos' => ['abc', false],
        ];
    }

    #[DataProvider('isValidProvider')]
    public function testIsValid(mixed $value, bool $expected): void
    {
        self::assertSame($expected, Cpf::isValid($value));
    }

    /**
     * @return array<string, array{mixed, string}>
     */
    public static function normalizadoProvider(): array
    {
        return [
            'apenas digitos' => ['52998224725', '52998224725'],
            'com mascara' => ['529.982.247-25', '52998224725'],
            'com espacos' => [' 529 982 247 25 ', '52998224725'],
            'zeros a esquerda ficam' => ['000.000.001-91', '00000000191'],
            'inteiro vira string' => [52998224725, '52998224725'],
        ];
    }

    #[DataProvider('normalizadoProvider')]
    public function testFromNormalizaSoOsDigitos(mixed $value, string $expected): void
    {
        $cpf = Cpf::from($value);

        self::assertSame($expected, $cpf->value());
        self::assertSame($expected, (string) $cpf);
        self::assertSame('"' . $expected . '"', json_encode($cpf));
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function entradaInvalidaProvider(): array
    {
        return [
            'inteiro sem zeros' => [191],
            'digito verificador errado' => ['52998224726'],
            'todos iguais' => ['111.111.111-11'],
            '10 digitos' => ['5299822472'],
            '12 digitos' => ['529982247250'],
            'vazio' => [''],
            'null' => [null],
            'false' => [false],
            'sem digitos' => ['abc'],
        ];
    }

    #[DataProvider('entradaInvalidaProvider')]
    public function testFromComEntradaInvalidaLancaExcecao(mixed $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valor invalido para Elavora\Api\DataTypes\Brazil\Cpf.');

        Cpf::from($value);
    }

    public function testConstrutorNaoEPublico(): void
    {
        $construtor = (new \ReflectionClass(Cpf::class))->getConstructor();

        self::assertNotNull($construtor);
        self::assertFalse($construtor->isPublic());
    }
}
