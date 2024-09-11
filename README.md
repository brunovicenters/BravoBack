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

## Routes

Here you will see all the available routes and it's returns. The base URL is: [http://0.0.0.0:8000/api/](http://0.0.0.0:8000/api/)

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
