# Guia de uso

O pacote aceita CPF como 11 digitos ou com a mascara completa.

```php
use Elavora\Api\DataTypes\Brazil\Cpf;

$cpf = Cpf::from('529.982.247-25');

echo $cpf->value(); // 52998224725
```

Espacos, texto adicional, mascaras parciais e valores que nao sejam `string` sao rejeitados. Sequencias repetidas e digitos verificadores incorretos tambem sao invalidos.

Para verificar uma entrada sem criar uma instancia:

```php
if (Cpf::isValid($entrada)) {
    $cpf = Cpf::from($entrada);
}
```

## Validacao do pacote

Execute os comandos a partir da raiz do clone:

```bash
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer update --no-interaction --no-progress --prefer-dist
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer check
```
