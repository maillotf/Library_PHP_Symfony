# Introduction about Symfony 6 API REST with docker

## About

https://flavienmaillot.medium.com/introduction-à-lapi-rest-avec-symfony-6-db-sous-docker-5bee1c2d1745

## Install et start the project

```console
git clone ...

cd library_php_symfony

docker-compose up -d --build

docker exec -it php-fpm bash

composer update

symfony console doctrine:database:create

symfony console make:migration

symfony console doctrine:migrations:migrate
```

The dependencies updated, use the following link :
http://localhost/

# API

The REST API described below.

> Permission : None

## Create author

### Request

`POST /author`


    curl --request POST \
    --url http://localhost/author \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --data '{
	    "firstname":"Flavien",
	    "lastname":"Maillot"
    }'

**Parameters**

| Field | Type | Description |
|--|--|--|
| firstname | String | Author firstname |
| lastname | String | Author lastname |

```json
{
 "firstname": "Flavien",
 "lastname": "Maillot"
}
```

### Response

    HTTP/1.1 201 Created
    Server: nginx/1.23.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Location: /author/3
    Cache-Control: no-cache, private
    Date: Thu, 17 Nov 2022 16:42:40 GMT
    X-Robots-Tag: noindex

    {"id":1,"firstname":"Flavien","lastname":"Maillot","books":[]}

```json
{
	"id": 1,
	"firstname": "Flavien",
	"lastname": "Maillot",
	"books": []
}
```

## List authors

### Request

`GET /authors`


    curl --request GET \
    --url http://localhost/authors \
    --header 'Accept: application/json'

**Parameters**

| Field | Type | Description |
|--|--|--|
| firstname | String | Author firstname |
| lastname | String | Author lastname |

```json
{
 "firstname": "Flavien",
 "lastname": "Maillot"
}
```

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.23.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Cache-Control: no-cache, private
    Date: Sun, 20 Nov 2022 15:09:40 GMT
    X-Robots-Tag: noindex

    {"id":1,"firstname":"Flavien","lastname":"Maillot","books":[{"id":1,"title":"The title","publishedDate":"2020-08-19T00:00:00+00:00","author":1}]}

```json
{
    "id":1,
    "firstname":"Flavien",
    "lastname":"Maillot",
    "books":[
        {
            "id":1,
            "title":"The title",
            "publishedDate":"2020-08-19T00:00:00+00:00",
            "author":1
        }
    ]
}
```

## Delete an author

### Request

`GET /author/{authorId}`


    curl --request DELETE \
    --url http://localhost/author/1 \
    --header 'Accept: application/json'

**Parameters**

| Field | Type | Description |
|--|--|--|
| authorId | int | Author id |

### Response

    HTTP/1.1 204 No Content
    Server: nginx/1.23.2
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Cache-Control: no-cache, private
    Date: Thu, 17 Nov 2022 14:12:14 GMT
    X-Robots-Tag: noindex



## Create book

### Request

`POST /book`


    curl --request POST \
    --url http://localhost/book \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --data '{
        "title":"The title",
        "publishedDate":"2020-08-19",
        "author": {"id":1}
    }'

**Parameters**

| Field | Type | Description |
|--|--|--|
| title | String | Book title |
| publishedDate | Date | Book published date |
| author | Object | Author object with id attribute only |

```json
    {
        "title":"The title",
        "publishedDate":"2020-08-19",
        "author": {"id":1}
    }
```

### Response

    HTTP/1.1 201 Created
    Server: nginx/1.23.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Location: /book/9
    Cache-Control: no-cache, private
    Date: Thu, 17 Nov 2022 16:42:53 GMT
    X-Robots-Tag: noindex

    null

```json
null
```

## Details of book

### Request

`GET /book/{bookId}`


    curl --request GET \
    --url http://localhost/book/1 \
    --header 'Accept: application/json'

**Parameters**

| Field | Type | Description |
|--|--|--|
| bookId | Int | Book id |


### Response

    HTTP/1.1 200 OK
    Server: nginx/1.23.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Cache-Control: no-cache, private
    Date: Sun, 20 Nov 2022 15:09:40 GMT
    X-Robots-Tag: noindex

    {"id": 1,"title": "The title","publishedDate": "2020-08-19T00:00:00+00:00","author": {"id": 1,"firstname": "Flavien","lastname": "Maillot"}}

```json
{
	"id": 1,
	"title": "The title",
	"publishedDate": "2020-08-19T00:00:00+00:00",
	"author": {
		"id": 1,
		"firstname": "Flavien",
		"lastname": "Maillot"
	}
}
```

## List books

### Request

`GET /books`


    curl --request GET \
    --url http://localhost/books \
    --header 'Accept: application/json'

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.23.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Cache-Control: no-cache, private
    Date: Fri, 18 Nov 2022 08:30:00 GMT
    X-Robots-Tag: noindex

    [{"id":1,"title":"The title","publishedDate":"2020-08-19T00:00:00+00:00"},{"id":2,"title":"The title 2","publishedDate":"2020-08-19T00:00:00+00:00"}]

```json
[
    {
        "id":1,
        "title":"The title",
        "publishedDate":"2020-08-19T00:00:00+00:00"
    },
    {
        "id":2,
        "title":"The title 2",
        "publishedDate":"2020-08-19T00:00:00+00:00"
    }
]
```

## Delete a book

### Request

`GET /book/{bookId}`


    curl --request DELETE \
    --url http://localhost/book/1 \
    --header 'Accept: application/json'

**Parameters**

| Field | Type | Description |
|--|--|--|
| bookId | int | Book id |

### Response

    HTTP/1.1 204 No Content
    Server: nginx/1.23.2
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Cache-Control: no-cache, private
    Date: Thu, 17 Nov 2022 14:12:14 GMT
    X-Robots-Tag: noindex


## List books filtered by author

### Request

`GET /books/author/authorId`


    curl --request GET \
    --url http://localhost/books/author/1 \
    --header 'Accept: application/json'

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.23.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Cache-Control: no-cache, private
    Date: Fri, 18 Nov 2022 08:30:00 GMT
    X-Robots-Tag: noindex

    [{"id":1,"title":"The title","publishedDate":"2020-08-19T00:00:00+00:00"}]

```json
[
    {
        "id":1,
        "title":"The title",
        "publishedDate":"2020-08-19T00:00:00+00:00"
    }
]
```

## List available routes

### Request

`GET /`


    curl --request GET \
    --url http://localhost/ \
    --header 'Accept: application/json'

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.23.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/8.1.12
    Cache-Control: no-cache, private
    Date: Sun, 20 Nov 2022 15:01:51 GMT
    X-Robots-Tag: noindex

    [{"name":"author_create","method":"POST","scheme":"ANY","host":"ANT","path":"/author"},{"name":"authors_all","method":"GET","scheme":"ANY","host":"ANT","path":"/authors"},...]

```json
[
    {
        "name":"author_create",
        "method":"POST",
        "scheme":"ANY",
        "host":"ANT",
        "path":"/author"
    },
    {
        "name":"authors_all",
        "method":"GET",
        "scheme":"ANY",
        "host":"ANT",
        "path":"/authors"
    },
    ...
]
```