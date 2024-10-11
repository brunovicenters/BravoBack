# Bravo API

Bravo API is a API built with Laravel, made for an academic project, using a template database.

- [Common Models](#common-models)
- [Routes](#routes)

## Common Models

The API has some common Models that appears in our responses, so, for convenience, I will declare them here:

### Product

```json
{
    "id": Int,
    "nome": String,
    "preco": String,
    "desconto": String,
    "imagem": String
}
```

### SimpleCategory

```json
{
    "id": Int,
    "nome": String,
}
```

### Imagem

```json
{
    "url": String
}
```

### Product Details

```json
{
    "id": Int,
    "nome": String,
    "preco": String,
    "desconto": String,
    "categoria": String,
    "qtd": Int,
    "desc": String,
    "imagem": [
        Imagem
    ]
}
```

## Middlewares

Each middleware used in this project is listed below, along with a detailed explanation of its functionality and usage. To enhance readability, each middleware is represented by a unique badge. When a route includes a specific middleware, its corresponding badge will be displayed at the top of the section, providing a clear visual indication.

![AuthUser](https://img.shields.io/badge/AuthUser-yellow)

&rArr; The AuthUser Middleware checks for the presence of a header parameter named user. The value of this parameter should correspond to a valid user ID from the database.

**header:**

```json
{
    "user": Int
}
```

## Routes

Here you will see all the available routes and it's returns. The base URL is: [https://vicentedev.com.br/api/](http://vicentedev.com.br/api/)

### Home

``` / ```

**Method:**

```http
GET
```

**req:**

```json
{}
```

**res:**

- `Product` model can be found in the [Product](#product) section.

- `SimpleCategory` model can be found in the [SimpleCategory](#simplecategory) section.

```json
{
    "data": {
        "promocao": [
            Product
        ],
        "produtosMaisVendidos": [
            Product
        ],
        "categoriasMaisVendidas": [
            "categoria": SimpleCategory,
            "produtos": [
                Product
            ]
        ]
    }
}
```

### Login

``` /login ```

**Method:**

```http
POST
```

**req:**

The body of your request must follow this structure:

```json
{
    "email": String,
    "password": String
}
```

**res:**

```json
{
    "data": {
        "user": Int
    }
}
```

### Register

``` /profile ```

**Method:**

```http
POST
```

**req:**

The body of your request must follow this structure:

```json
{
    "email": String,
    "password": String,
    "password_confirmation": String,
    "cpf": String,
    "name": String
}
```

>You can also use "passwordConfirmation instead of "password_confirmation".

**res:**

```json
{
    "data": {
        "user": Int
    }
}
```

### Show Profile

![AuthUser](https://img.shields.io/badge/AuthUser-yellow)

``` /profile/{id} ```

**Method:**

```http
GET
```

**req:**

The body of your request must follow this structure:

```json
{}
```

**res:**

- `Product` model can be found in the [Product](#product) section.

```json
{
    "data": {
        "user": {
            "id": Int,
            "name": String,
            "email": String,
            "cpf": String
        },
        "compreNovamente": [
            Product
        ]
    }
}
```

### Show Product

``` /produto/{id}?user={user_id} ```

To get a more precisive available quantity, you can include the logged user's id to the query string, so you get the quantity available in the database less the quantity the user has in it's shopcart.

**Method:**

```http
GET
```

**req:**

The body of your request must follow this structure:

```json
{}
```

**res:**

- `Product Details` model can be found in the [Product Details](#product-details) section.

- `Product` model can be found in the [Product](#product) section.

```json
{
    "data": {
        "produto": {
            Product Details
        },
        "semelhantes": [
            Product
        ]
    }
}
```

### Store Cart

``` /profile ```

**Method:**

```http
POST
```

**req:**

The body of your request must follow this structure:

```json
{
    "produtoId": Int,
    "quantity": Int,
}
```

**res:**

```json
{
    "data": {
        "msg": String
    }
}
```
