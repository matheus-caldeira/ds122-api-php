# Docker - Container PHP + MYSQL

Essa é uma aplicação exemplo para criar um container PHP + MYSQL.
  
Após ter instalado o Docker e Docker Compose, você pode iniciar o ambiente com:

```
#importate definir as variaeis user: math uid: 1000
docker-compose build
docker-compose up -d
```
A base URL default é: `localhost:3000/index.php`.

É possível fazer testes de requisição com o arquivo `Insomnia.json`, disposto no projeto.

Para acessar as imagems a base url é: `localhost:3000/images/{image}`
