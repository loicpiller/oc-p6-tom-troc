<?php

return [
    '/' => 'HomeController@index',
    '/connexion' => 'UserController@login',
    '/inscription' => 'UserController@register',
    '/deconnexion' => 'UserController@logout',
    '/mon-compte' => 'UserController@profile',
];
