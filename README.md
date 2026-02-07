# burh-desafio

Esta é uma API RESTful desenvolvida em Laravel 12 para gerenciamento de vagas de emprego e candidaturas de usuários. A API permite que empresas criem vagas e usuários se candidatem a elas, com validações específicas e regras de negócio.

## Funcionalidades

- **Empresas**: Cadastro com CNPJ único, plano (Free ou Premium)
- **Usuários**: Cadastro com e-mail e CPF únicos
- **Vagas**: Criação com tipos (PJ, CLT, Estágio), salários e horários obrigatórios para CLT e Estágio
- **Candidaturas**: Usuários podem se candidatar a vagas
- **Busca**: Filtrar usuários por nome, e-mail ou CPF, retornando vagas inscritas

## Requisitos

- PHP 8.5.2
- Laravel 12
- MySQL
- Docker (opcional, via Sail)

## Instalação

1. Clone o repositório
2. Instale dependências: `composer install`
3. Configure ambiente: `cp .env.example .env`
4. Execute migrations: `php artisan migrate`
5. (Opcional) Use Docker: `./vendor/bin/sail up`

## Desenvolvimento

- Execute testes: `php artisan test`
- Formate código: `vendor/bin/pint --dirty --format agent`
- Para testar a API, importe a coleção do Postman localizada em `docs/burh.postman_collection.json`

## Endpoints da API

### Empresas
- `GET /api/v1/companies` - Listar empresas
- `POST /api/v1/companies` - Criar empresa
- `GET /api/v1/companies/{company}` - Detalhes da empresa
- `PUT /api/v1/companies/{company}` - Atualizar empresa
- `DELETE /api/v1/companies/{company}` - Deletar empresa

### Usuários
- `GET /api/v1/users` - Listar usuários
- `POST /api/v1/users` - Criar usuário
- `GET /api/v1/users/{user}` - Detalhes do usuário
- `PUT /api/v1/users/{user}` - Atualizar usuário
- `DELETE /api/v1/users/{user}` - Deletar usuário
- `GET /api/v1/users/search` - Buscar usuários (parâmetros: name, email, cpf)

### Vagas
- `GET /api/v1/positions` - Listar vagas
- `POST /api/v1/positions` - Criar vaga
- `GET /api/v1/positions/{position}` - Detalhes da vaga
- `PUT /api/v1/positions/{position}` - Atualizar vaga
- `DELETE /api/v1/positions/{position}` - Deletar vaga
- `POST /api/v1/positions/{position}/apply` - Candidatar-se à vaga

## Regras de Negócio

- Empresas Free: até 5 vagas
- Empresas Premium: até 10 vagas
- Salário mínimo CLT: R$ 1.212,00
- Horário máximo Estágio: 6 horas/dia
- Unicidade: e-mail, CPF, CNPJ

## Tecnologias

- Laravel 12
- Pest para testes
- MySQL
- Docker/Sail
- Pint para formatação de código

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
