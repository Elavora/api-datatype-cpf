<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Cpf\Tests;

use Elavora\Api\DataTypes\Brazil\Cpf;
use Elavora\Api\Framework\Contracts\DescribesType;
use PHPUnit\Framework\TestCase;

/**
 * @see docs/USO.md#documentação-do-tipo-options
 */
final class DocumentedTypeTest extends TestCase
{
    private const ALLOWED_KEYS = ['type', 'format', 'mask', 'pattern', 'enum', 'example', 'description'];

    public function testImplementsDescribesType(): void
    {
        self::assertInstanceOf(DescribesType::class, new Cpf('52998224725'));
    }

    public function testDocumentedTypeUsesOnlyAllowedKeys(): void
    {
        $documented = Cpf::documentedType();

        self::assertArrayHasKey('type', $documented);
        self::assertSame('string', $documented['type']);
        self::assertSame([], array_diff(array_keys($documented), self::ALLOWED_KEYS));
    }

    public function testExampleIsValid(): void
    {
        $documented = Cpf::documentedType();

        self::assertArrayHasKey('example', $documented);
        self::assertTrue(Cpf::isValid($documented['example']));
    }

    public function testPatternMatchesExampleAndPlainDigits(): void
    {
        $documented = Cpf::documentedType();

        self::assertArrayHasKey('pattern', $documented);
        $pattern = '~' . $documented['pattern'] . '~';
        self::assertSame(1, preg_match($pattern, (string) $documented['example']));
        self::assertSame(1, preg_match($pattern, $this->digits((string) $documented['example'])));
    }

    private function digits(string $value): string
    {
        return (string) preg_replace('/\D/', '', $value);
    }
}
