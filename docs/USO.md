# Guia de uso

DataType CPF para aplicações Elavora API.

## Instalação

```bash
composer require elavora/api-datatype-cpf
```

## Quando usar

- Validar e normalizar valores antes de chegar na regra de negócio.
- Evitar passar strings soltas entre services, DTOs e persistencia.
- Reutilizar a mesma validacao em controllers, comandos e testes.

## Exemplo rápido

```php
use Elavora\Api\DataTypes\Brazil\Cpf;

$cpf = Cpf::from('529.982.247-25');

$cpf->value();                  // '52998224725'
(string) $cpf;                  // '52998224725'
json_encode($cpf);              // '"52998224725"'
Cpf::isValid('52998224725');  // true
```

O construtor não é público: crie sempre por `Cpf::from()`.

## Versão e compatibilidade

| Item | Valor |
|---|---|
| Última versão antes do upgrade | `0.2.0` (tag legada `v0.2.0`) |
| Versão do upgrade | `1.0.0` |
| PHP | `>=8.3` |
| `elavora/api-datatype-core` | `^2.0` |

As versões seguintes saem do fluxo central de release: veja [Versões e release](AUTOMACAO.md#versões-e-release).
A versão `1.0.0` atualiza o pacote para o `elavora/api-datatype-core` `^2.0` (que traz o framework 2) e exige PHP 8.3
ou superior.

**Quebra de compatibilidade:** no `elavora/api-datatype-core` 2 o construtor de `AbstractDataType` é
`final protected`, então `new Cpf('52998224725')` deixa de funcionar fora da própria classe. Troque por
`Cpf::from('52998224725')`. `Cpf::isValid()` e `value()` continuam iguais.

### `composer.json`

O `composer.json` do pacote deve ter:

- `require.php` = `">=8.3"` e `require.elavora/api-datatype-core` = `"^2.0"`
- `extra.branch-alias.dev-main` = `"1.0-dev"`
- `autoload.psr-4` = `"Elavora\\Api\\DataTypes\\Brazil\\": "src/"` (sem mudança)
- `require-dev.phpunit/phpunit` = `"^10.5 || ^11.0 || ^12.0"` (sem mudança)
- `scripts.test` = `"phpunit -c phpunit.xml"` (sem mudança)
- `require-dev`, `scripts` e `config` de qualidade (`elavora/code-standards`, PHPStan, `lint`, `analyse`, `check`):
  veja [`composer.json` em Automação](AUTOMACAO.md#composerjson)
- nenhum `repositories` (nem `path`, nem `vcs`)
- `composer validate --strict --no-check-publish` sem erros.

## Criação e entrada inválida

`Elavora\Api\DataTypes\Brazil\Cpf::from(mixed $value): static` valida o valor com `isValid()` e devolve um `Cpf`
com o valor normalizado (só os 11 dígitos). Se o valor for inválido, lança `\InvalidArgumentException` com a mensagem
`Valor invalido para Elavora\Api\DataTypes\Brazil\Cpf.`

```php
use Elavora\Api\DataTypes\Brazil\Cpf;

try {
    Cpf::from('52998224726'); // dígito verificador errado
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); // Valor invalido para Elavora\Api\DataTypes\Brazil\Cpf.
}
```

Métodos da instância: `value(): string|int|bool|float|null` (aqui sempre `string`), `jsonSerialize()` (mesmo valor) e
`__toString()`.

## Validação do CPF

`Elavora\Api\DataTypes\Brazil\Cpf::isValid(mixed $value): bool` não lança exceção. O valor é convertido em texto e
tudo o que não é dígito é removido. Ele é válido quando sobram exatamente 11 dígitos, não são todos iguais e os dois
últimos são os dígitos verificadores corretos (módulo 11, com os pesos do CPF). A normalização guarda só os dígitos.

| Entrada | `isValid` | `from()` / valor normalizado |
|---|---|---|
| `'52998224725'` | `true` | `52998224725` |
| `'529.982.247-25'` | `true` | `52998224725` |
| `' 529 982 247 25 '` | `true` | `52998224725` |
| `'000.000.001-91'` | `true` | `00000000191` (zeros à esquerda ficam) |
| `52998224725` (`int`) | `true` | `52998224725` |
| `191` (`int`, perdeu os zeros) | `false` | lança `InvalidArgumentException` |
| `'52998224726'` | `false` | lança `InvalidArgumentException` (dígito verificador errado) |
| `'111.111.111-11'` | `false` | lança `InvalidArgumentException` (todos os dígitos iguais) |
| `'5299822472'` (10 dígitos) | `false` | lança `InvalidArgumentException` |
| `'529982247250'` (12 dígitos) | `false` | lança `InvalidArgumentException` |
| `''`, `null`, `false` | `false` | lança `InvalidArgumentException` |
| `'abc'` (sem dígitos) | `false` | lança `InvalidArgumentException` |

Detalhes que a tabela mostra:

- A limpeza remove qualquer caractere que não seja dígito, não só `.` e `-`. Por isso uma entrada com letras no meio
  (`'529x982y247z25'`) também é válida. Se a aplicação precisa recusar texto estranho, filtre antes de chamar o pacote.
- O valor normalizado é sempre `string`, mesmo quando a entrada é `int`. O CPF `00000000191` passado como `int`
  chega como `191` e é recusado, então use `string` para CPFs que começam com zero.
- Arrays e objetos sem `__toString()` não são entrada suportada: o pacote não os trata como CPF inválido de forma
  garantida (a conversão para texto pode lançar `Error`). Trate o tipo antes.

Contraexemplo (comportamento que não vale mais): `new Cpf('52998224725')` funcionava no `elavora/api-datatype-core`
`^0.1` e falha no `^2.0`, porque o construtor é `final protected`.

## Principais pontos de entrada

- `Elavora\Api\DataTypes\Brazil\Cpf` (`final readonly`, herda de `Elavora\Api\DataTypes\AbstractDataType`)

## Dependências de runtime

- `elavora/api-datatype-core` `^2.0`

## Validação do pacote

O passo a passo para rodar lint, PHPStan e testes em container está em [Desenvolvimento e testes](DESENVOLVIMENTO.md).
Na aplicação consumidora, rode os testes dela depois de instalar o pacote.

## Observações

- Mantenha regras de produto fora deste pacote.
- Prefira configurar extensões no bootstrap da aplicação.
- Instale apenas os módulos que a aplicação realmente usa.
