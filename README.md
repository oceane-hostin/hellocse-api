## Authentication

- Api route : /api/login/
- Method : POST
- Body : 
```json 
{
    "username": "admin",
    "password": "password"
}
```
- Responses :   

| HTTP Code | Body response | Meaning                    |
|-----------| --- |----------------------------|
| 200       | `{"token": "2K9qbcjMWuSfqbLPWHHpup8mD0uQvh0tZx9PNEnxS1c16ede6","token_type": "Bearer"}` | Logged in succesfully |
| 401       | `{"message": "Unauthorized"}` | Wrong credentials provided |

## List all profiles

- Api route : /api/profiles/
- Method : GET
- Body : No body

### Without authentication :   
- will always list active profile  
- field status is not visible
- Example : 
```json 
[
    {
        "id": 1,
        "firstname": "John",
        "lastname": "Doe",
        "image": "https://picsum.photos/200/300",
        "created_at": "2024-07-14T12:55:11.000000Z",
        "updated_at": "2024-07-14T12:55:11.000000Z"
    }
]
```

### With authentication : 
- will return all profiles
- filed status is accessible
- Example : 
```json
[
    {
        "id": 1,
        "firstname": "Jack",
        "lastname": "Green",
        "image": "https://picsum.photos/200/300",
        "status": "1",
        "created_at": "2024-07-14T13:59:03.000000Z",
        "updated_at": "2024-07-14T14:08:51.000000Z"
    },
    {
        "id": 2,
        "firstname": "Jane",
        "lastname": "Doe",
        "image": "https://picsum.photos/200/300",
        "status": "0",
        "created_at": "2024-07-14T13:59:03.000000Z",
        "updated_at": "2024-07-14T13:59:03.000000Z"
    },
    {
        "id": 3,
        "firstname": "Mary",
        "lastname": "Green",
        "image": "https://picsum.photos/200/300",
        "status": "2",
        "created_at": "2024-07-14T13:59:03.000000Z",
        "updated_at": "2024-07-14T13:59:03.000000Z"
    },
    {
        "id": 4,
        "firstname": "Test",
        "lastname": "Test",
        "image": "https://picsum.photos/200/300",
        "status": "2",
        "created_at": "2024-07-14T14:03:16.000000Z",
        "updated_at": "2024-07-14T14:03:16.000000Z"
    }
]
```




## Get one profile

- Api route : /api/profile/{id}
- Method : GET
- Body : No body

### Without authentication :
Responses :

| HTTP Code | Body response                | Meaning                                                                           |
|-----------|------------------------------|-----------------------------------------------------------------------------------|
| 200       | Json of entity               | The profile exist and is active                                                   |
| 403       | Json with error message  | The profile existe but is not active, and can be accessed only by logged in admin |
| 404       | Json with error message      | The profile doesn't exist                                                         |

### With authentication : 
Responses :

| HTTP Code | Body response                | Meaning                                                                           |
|-----------|------------------------------|-----------------------------------------------------------------------------------|
| 200       | Json of entity               | The profile exist                                                  |
| 401       | Json with error message  | Wrong / no bearer token provided |
| 404       | Json with error message      | The profile doesn't exist                                                         |

## Create a profile

- Api route : /api/profiles/
- Method : POST
- Authentication must be provided ad a bearer token
- Body :
```json
{
    "firstname": "Test",
    "lastname": "Test",
    "image": "https://picsum.photos/200/300"
}
```
- Responses :

| HTTP Code | Body response                 | Meaning                          |
|-----------|-------------------------------|----------------------------------|
| 200       | Json of created entity        | Creation successful             |
| 401       | Json with error message | Wrong / no bearer token provided |
| 422 | Json with details of errors | Body provided not corresponding to validation rules |

## Update a profile

- Api route : /api/profile/{id}
- Method : PUT
- Authentication must be provided ad a bearer token
- Body :
```json
{
    "firstname": "Jack",
    "lastname": "Green",
    "status": 1
}
```
- Responses :

| HTTP Code | Body response                 | Meaning                                             |
|-----------|-------------------------------|-----------------------------------------------------|
| 200       | Json of updated entity        | Updated successful                                  |
| 401       | Json with error message | Wrong / no bearer token provided                    |
| 422 | Json with details of errors   | Body provided not corresponding to validation rules |

## Delete a profile

- Api route : /api/profile/{id}
- Method : DELETE
- Authentication must be provided ad a bearer token
- Responses :

| HTTP Code | Body response                 | Meaning                                               |
|-----------|-------------------------------|-------------------------------------------------------|
| 200       | Json with success message     | Deleted successful                                    |
| 401       | Json with error message | Wrong / no bearer token provided                      |
| 404 | Json with error message       | Profile targeted doesn't exist and couln'd be deleted |
