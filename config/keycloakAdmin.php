<?php

$baseUrl = env(
    'KEYCLOAK_ADMIN_BASE_URL_REALM',
    'http://localhost:8080/auth/admin/realms/master'
);

return [

    'client' => [
        'id' => env('KEYCLOAK_ADMIN_CLIENT_ID', null),
        'secret' => env('KEYCLOAK_ADMIN_CLIENT_SECRET', null),
        'id_client' => env('KEYCLOAK_ADMIN_ID_CLIENT', null),
        'username' => env('KEYCLOAK_ADMIN_USERNAME', null),
        'password' => env('KEYCLOAK_ADMIN_PASSWORD', null)
    ],


    'api' => [

        'client' => [

            'token' => env('KEYCLOAK_ADMIN_BASE_URL', 'localhost:8080') . '/realms/' . env('KEYCLOAK_ADMIN_REALM_LOGIN', 'master') . '/protocol/openid-connect/token',

            'create' => [
                'api' => "{$baseUrl}/clients",
                'method' => 'post'
            ],
            'all' => [
                'api' => "{$baseUrl}/clients",
                'method' => 'get'
            ],
            'get' => [
                'api' => "{$baseUrl}/clients/{id}",
                'method' => 'get'
            ],
            'update' => [
                'api' => "{$baseUrl}/clients/{id}",
                'method' => 'put'
            ],
            'delete' => [
                'api' => "{$baseUrl}/clients/{id}",
                'method' => 'delete'
            ]

        ],


        'client_roles' => [

            'create' => [
                'api' => "{$baseUrl}/clients/{id}/roles",
                'method' => 'post'
            ],
            'all' => [
                'api' => "{$baseUrl}/clients/{id}/roles",
                'method' => 'get'
            ],
            'getByName' => [
                'api' => "{$baseUrl}/clients/{id}/roles/{role}",
                'method' => 'get'
            ],
            'update' => [
                'update' => "{$baseUrl}/clients/{id}/roles/{role}",
                'method' => 'post'
            ],
            'delete' => [
                'update' => "{$baseUrl}/clients/{id}/roles/{role}",
                'method' => 'delete'
            ],

        ],



        'user' => [
            'create' => [
                'method' => 'post',
                'api' => "{$baseUrl}/users"
            ],
            'all' => [
                'api' => "{$baseUrl}/users",
                'method' => 'get'
            ],
            'get' => [
                'api' => "{$baseUrl}/users/{id}",
                'method' => 'get'
            ],
            'getUser' => [
                'api' => "{$baseUrl}/users?username={username}",
                'method' => 'get'
            ],
            'update' => [
                'api' => "{$baseUrl}/users/{id}",
                'method' => 'put'
            ],

            'delete' => [
                'api' => "{$baseUrl}/users/{id}",
                'method' => 'delete'
            ],

            'groups' => [
                'api' => "{$baseUrl}/users/{id}/groups",
                'method' => 'get'
            ],
            'addToGroup' => [
                'api' => "{$baseUrl}/users/{id}/groups/{groupId}",
                'method' => 'put'
            ],
            'deleteFromGroup' => [
                'api' => "{$baseUrl}/users/{id}/groups/{groupId}",
                'method' => 'delete'
            ],
            'removeTOTP' => [
                'api' => "{$baseUrl}/users/{id}/remove-totp",
                'method' => 'put'
            ],
            'setTemporaryPassword' => [
                'api' => "{$baseUrl}/users/{id}/reset-password",
                'method' => 'put'
            ],
            'verifyByEmail' => [
                'api' => "{$baseUrl}/users/{id}/send-verify-email",
                'method' => 'put'
            ],
            'roleMappings' => [
                'api' => "{$baseUrl}/users/{id}/role-mappings",
                'method' => 'get'
            ],
            'addRealmRoles' => [
                'api' => "{$baseUrl}/users/{id}/role-mappings/realm",
                'method' => 'post'
            ],
            'getRealmRoles' => [
                'api' => "{$baseUrl}/users/{id}/role-mappings/realm",
                'method' => 'get'
            ],
            'deleteRealmRoles' => [
                'api' => "{$baseUrl}/users/{id}/role-mappings/realm",
                'method' => 'delete'
            ],
            'getAvailableRealmRoles' => [
                'api' => "{$baseUrl}/users/{id}/role-mappings/realm/available",
                'method' => 'get'
            ],
            'getEffectiveRealmRoles' => [
                'api' => "{$baseUrl}/users/{id}/role-mappings/realm/composite",
                'method' => 'get'
            ],
            'sessions' => [
                'api' => "{$baseUrl}/users/{id}/sessions",
                'method' => 'get'
            ],
        ],


        'role' => [
            'create' => [
                'api' => "{$baseUrl}/roles",
                'method' => 'post'
            ],
            'all' => [
                'api' => "{$baseUrl}/roles",
                'method' => 'get'
            ],
            'get' => [
                'api' => "{$baseUrl}/roles-by-id/{id}",
                'method' => 'get'
            ],
            'getByName' => [
                'api' => "{$baseUrl}/roles/{role}",
                'method' => 'get'
            ],
            'update' => [
                'api' => "{$baseUrl}/roles-by-id/{id}",
                'method' => 'put'
            ],
            'updateByName' => [
                'api' => "{$baseUrl}/roles/{role}",
                'method' => 'put'
            ],
            'delete' => [
                'api' => "{$baseUrl}/roles-by-id/{id}",
                'method' => 'delete'
            ],
            'deleteByName' => [
                'api' => "{$baseUrl}/roles/{role}",
                'method' => 'delete'
            ],
        ],

        'group' => [
            'create' => [
                'api' => "{$baseUrl}/groups",
                'method' => 'post'
            ],
            'all' => [
                'api' => "{$baseUrl}/groups",
                'method' => 'get'
            ],
            'get' => [
                'api' => "{$baseUrl}/groups/{id}",
                'method' => 'get'
            ],
            'counts' => [
                'api' => "{$baseUrl}/groups/count",
                'method' => 'get'
            ],
            'update' => [
                'api' => "{$baseUrl}/groups/{id}",
                'method' => 'put'
            ],
            'delete' => [
                'api' => "{$baseUrl}/groups/{id}",
                'method' => 'delete'
            ],
            'members' => [
                'api' => "{$baseUrl}/groups/{id}/members",
                'method' => 'get'
            ],
            'roleMapping' => [
                'api' => "{$baseUrl}/groups/{id}/role-mappings/clients/{clientId}",
                'method' => 'post'
            ],
            'children' => [
                'api' => "{$baseUrl}/groups/{id}/children",
                'method' => 'post'
            ]
        ]
    ]


];
