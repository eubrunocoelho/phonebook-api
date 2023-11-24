# Phonebook API

## **Resumo**
**API REST** desenvolvida à fim de práticar programação em PHP sem o uso de dependências externas, somente o PHP nativo e o banco de dados MySQL.
A **Phonebook API** é um sistema de lista telefônica onde os contatos são disponibilizados apenas para seus próprios usuários, onde o usuário pode editar, cadastrar, deletar e visualizar seus contatos e telefones.

## **Sumário**
- [**Rotas**](#rotas)
- [**Requisições**](#requisições)
- [**Tecnologias**](#tecnologias)

## **Rotas**
### Autenticação

- **POST** /register
- **POST** /login

### Contatos

- **POST** /contacts
- **GET** /contacts
- **GET** /contacts/**{_id_}**
- **PUT** /contacts/**{_id_}**
- **DELETE** /contacts/**{_id_}**

### Telefones

- **POST** /phones/**{_contactId_}**
- **PUT** /phones/**{_id_}**
- **DELETE** /phones/**{_id_}**

## **Requisições**
### Autenticação
_A autenticação HTTP é feita usando o sistema de autorização **Bearer Token**._

- **POST** /register _"username" é válido somente se iniciar por letras ou _ "underscore" seguidos por caracteres de A a Z_
```JSON
{
    "username": "eubrunocoelho",
    "email": "eu.brunocoelho94@gmail.com",
    "password": "12345678"
}
```

- **POST** /login _"username" pode ser o próprio "username" ou o "email"_
```JSON
{
    "username": "eu.brunocoelho94@gmail.com",
    "password": "12345678"
}
```

### Contatos

- **POST** /contacts _"name" é válido somente para nomes convencionais sem pontos, o campo "email" é opcional_
```JSON
{
    "name": "Bruno Coelho",
    "email": "eu.brunocoelho94@gmail.com"
}
```

- **GET** /contacts

- **GET** /contacts/**{_id_}**

- **PUT** /contacts/**{_id_}** _"name" é válido somente para nomes convencionais sem pontos, o campo "email" é opcional_
```JSON
{
    "name": "Bruno Coelho",
    "email": "eu.brunocoelho94@gmail.com"
}
```

- **DELETE** /contacts/**{_id_}**

### Telefones

- **POST** /phones/**{_contactId_}** _"phone_number" é válido somente se o número informado possuir o formato (DDD) 9999-6666 ou (DDD) 9999-6666, o campo de "description" é opcional_
```JSON
{
    "phone_number": "(41) 99999-6666",
    "description": "Celular"
}
```

- **PUT** /phones/**{_id_}** _"phone_number" é válido somente se o número informado possuir o formato (DDD) 9999-6666 ou (DDD) 9999-6666, o campo de "description" é opcional_
```JSON
{
    "phone_number": "(41) 99999-6666",
    "description": "Celular"
}
```

## **Tecnologias**
### Construção
- **PHP 8.1.x**
- **MySQL 8.0.30**
- **JSON**

### IDEs
- **Visual Studio Code**
- **Insomnia**

---

Criado por <a href="https://linktr.ee/eubrunocoelho">Bruno Coelho</a>.
