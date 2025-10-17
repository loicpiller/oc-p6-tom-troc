<?php

return [
    '/' => 'HomeController@index',
    '/connexion' => 'UserController@login',
    '/inscription' => 'UserController@register',
    '/deconnexion' => 'UserController@logout',
];
