# Magicak backend

## API documentation

```
[]: # Language: markdown
[]: # Path: README.md
[]: # Link: https://magicak-backend.magicak.com
```

### Choose domain

#### Request

```
[]: # Language: json
[]: # Path: /api/v1.0/choose-domain
[]: # Method: POST
[]: # Body:
{
    "user_website_id" : "1",,
    "domain_name" : "example.com",
    "is_temporary": 0
}
```

### Parameters

    * user_website_id: id of user website
    * domain_name: domain name
    * is_temporary: 0 or 1 (0 - primary domain, 1 - temporary domain)


#### Response
    
    {
        "code": 200,
        "response": {
            "domain_name": "temp25.magicak-sg.com"
        },
        "message": "Domain has been chosen"
    }

---

### Get user website with status is `updating_domain`

#### Request

```
[]: # Language: json
[]: # Path: /api/v1.0/user-website-updating-domain
[]: # Method: GET
```

#### Response
    
    {
        "code": 200,
        "response": [
            {
                "id": 28,
                "domain_id": 32,
                "hosting_ip": "1.2.3.11",
                "domain": {
                    "id": 32,
                    "domain_name": "business1.com"
                }
            }
        ],
        "message": "Successfully get user website with status \"updating_domain\""
    }


---

### Update website message

#### Request

```
[]: # Language: json
[]: # Path: /api/v1.0/update-website-message
[]: # Method: PUT
[]: # Body:
{
    "user_website_id" : "1",,
    "message" : "Hello world"
}
```


#### Parameters
    
    * user_website_id: id of user website
    * message: message

#### Response
    
    {
        "code": 200,
        "response": {
            "id": 1,
            "message": "hello world",
            "created_id": 5,
            "deleted_id": null,
            "updated_id": 4,
            "user_website_id": 3,
            "active": 1,
            "created_at": "2022-07-06T13:30:19.000000Z",
            "updated_at": "2022-07-16T14:09:16.000000Z",
            "deleted_at": null
        },
        "message": "Update website message success"
    }
