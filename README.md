## Instalação

Primeiramente, vá para a branch `luiz-fernando-borges-vieira` 
```bash
git checkout luiz-fernando-borges-vieira
```

Utilize o arquivo Makefile no projeto para uma instalação mais ágil ou siga os próximos passos:

```bash
docker compose up -d --build
```

```bash
cp .env.example .env
```

Entre no terminal do container da aplicação com:
```bash
docker compose exec -it app bash
```

Execute os próximos comandos dentro do container da aplicação:
```bash
composer install
```

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

A aplicação deverá estar disponível em http://localhost:8008

### Documentação

Os endpoints no arquivo openapi.yml no projeto. Utilize o visualizador de sua IDE de preferência ou sua o arquivo no site [Swagger Editor](https://editor.swagger.io/)
