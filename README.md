# api-datatype-cpf

[![Packagist Version](https://img.shields.io/packagist/v/elavora/api-datatype-cpf.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-cpf)
[![PHP Version](https://img.shields.io/packagist/php-v/elavora/api-datatype-cpf.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-cpf)
[![Composer Quality](https://github.com/Elavora/api-datatype-cpf/actions/workflows/quality.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-cpf/actions/workflows/quality.yml)
[![CodeQL](https://github.com/Elavora/api-datatype-cpf/actions/workflows/codeql.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-cpf/actions/workflows/codeql.yml)
[![License](https://img.shields.io/packagist/l/elavora/api-datatype-cpf.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-cpf)

DataType imutavel para validar e normalizar CPF.

## Requisitos

- PHP 8.3 ou superior.
- Demais requisitos declarados em [`composer.json`](composer.json).

## Instalacao

```bash
composer require elavora/api-datatype-cpf
```

## Inicio rapido

```php
use Elavora\Api\DataTypes\Brazil\Cpf;

$valor = Cpf::from('529.982.247-25');
$normalizado = $valor->value();
```

`$normalizado` contem `52998224725`. Sao aceitos somente 11 digitos ou a mascara exata `000.000.000-00`.

## Documentacao

Consulte o [guia de uso](docs/USO.md) para os formatos aceitos e a validacao local.
