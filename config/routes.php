<?php

return [
    '/' => 'HomeController@index',
    '/connexion' => 'UserController@login',
    '/inscription' => 'UserController@register',
    '/deconnexion' => 'UserController@logout',
    '/mon-compte' => 'UserController@profile',
    '/livres-echangeables' => 'BookController@index',
    '/livre/nouveau' => 'BookController@create',
    '/livre/{id}' => 'BookController@bookDetails',
    '/livre/{id}/edition' => 'BookController@edit',
    '/livre/{id}/supprimer' => 'BookController@delete',
    'profile/{id}' => 'UserController@publicProfile',
    'messages/{contactId}' => 'MessagingController@index',
    'messages' => 'MessagingController@index',

    'send-message/{receiverId}' => 'MessagingController@send',
    'update-picture' => 'UserController@updatePicture',
    'update-profile' => 'UserController@updateProfile',
];
