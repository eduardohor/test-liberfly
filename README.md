# TESTE PARA VAGA DE DESENVOLVEDOR - PHP Laravel - Back-End (Liberfly)

### Back-end feito com laravel

### Arquitetura 

- PHP 8.1.14
- Laravel 9.52.4
- Composer 2.4.4

### Instalação - WINDOWS
```sh
git clone https://github.com/eduardohor/test-liberfly.git
```

```sh
cd test-liberfly
```

- Instalar as dependências

```sh
composer install
```

- Duplicar o arquivo **.env.example** e renomear a copia para **.env**
```sh
cp .env.example .env
```

- Alterar os dados de banco no arquivo .env para os referente ao seu banco local

- Logo depois execute o comando abaixo para gerar uma nova chave
```PHP
php artisan key:generate
```
- Criar as tabelas no banco 

```sh
php artisan migrate
```

- Povoar o banco 

```sh
php artisan db:seed
```

- Gerar chave secreta do JWT Token no arquivo .env 

```sh
php artisan jwt:secret
```

- Subir o servidor

```sh
php artisan serve
```

- Verificar se a aplicação está online acessando

```sh
http://localhost:8000
```

- Gerar documentação com Swagger

```sh
php artisan swagger
```

 - Acessar documentação com Swagger

```sh
http://localhost:8000/api-documentation
```

- Acessar endpoints

No endpoin 'api/register' crie um novo usuário. 

Depois em 'api/login' Faça o login com usuário criado. Na resposta copie o Token de "access_token" e cole em Authorize para poder ter acesso aos outros endpoints. 






