# Matrix Multiplication

App was built in Laravel Lumen. Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax.

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Installing ⚙️

* Pull the repo and run migration with 
```php artisan migrate```
* To Run tests, cd to the project folder and run
```vendor/bin/phpunit```
* Also, don't forget to update your `phpunit.xml` file with your test DB name, else, your working DB would be cleared after each test.

## Deployment ⛵️

Deployed on heroku [here](https://matrix-multiplier.herokuapp.com/) *(albeit all api routes are still showing a 404 😭 - currently working on fixing that)*

## Assumptions 🤔

*1. No views - This is a strictly API based system*

*2. Database is required to store Auth*

*3. Basic Auth is used for authentication i.e Authorization => Bearer Encoded base64 of Username:Password*


## Documentation 📖

App has 3 endpoints:

```POST /signup``` : To request for a username and password. This would be used for a Basic Auth on the other endpoints

Request:
```
{
	"account_name" : "Big Man Corp",
	"email_address" :"bmc@exampple.com"
}
```

Response:
```
{
    "status": "success",
    "data": {
        "username": "0c5ecf",
        "password": "334bc03c"
    }
}
```

```POST /matrix``` : To take in the multiplicand and multiplier

Request:
```
{
	"multiplicand" : [[1,2,3], [4,5,6]],
	"multiplier" : [[1,1,1,1],[1,1,1,1],[1,1,1,1]]
}
```

Response:
```
{
    "status": "success",
    "data": {
        "id": 1,
        "matrix_product": [
            [
                6,
                6,
                6,
                6
            ],
            [
                15,
                15,
                15,
                15
            ]
        ],
        "transformed_product": [
            [
                "F",
                "F",
                "F",
                "F"
            ],
            [
                "O",
                "O",
                "O",
                "O"
            ]
        ]
    }
}
```

```GET /matrix/{id}``` : To get a previously computed matrix

Response:
```
{
    "status": "success",
    "data": {
        "id": 1,
        "matrix_product": [
            [
                6,
                6,
                6,
                6
            ],
            [
                15,
                15,
                15,
                15
            ]
        ],
        "transformed_product": [
            [
                "F",
                "F",
                "F",
                "F"
            ],
            [
                "O",
                "O",
                "O",
                "O"
            ]
        ],
        "creator": {
            "account_name": "Big Man Corp"
        },
        "created_at": "2021-04-26 14:20:11",
        "updated_at": "2021-04-26 14:20:11"
    }
}
```

Custom errors are in the format :
```
{
    "status": "error",
    "message": "Invalid credentials"
}
```
Auto-generated Validator errors (with status code : 422) are in the format :
```
{
    "email_address": [
        "The email_address field is required"
    ]
}
```

## Improvements 🎉

* Add email feature to the /signup endpoint to notify the users of their Auth Keys
* An endpoint to refresh username and/or password based on request
* Upgrade the Design Pattern from MVC
* Heroku to work properly on all API routes! 
