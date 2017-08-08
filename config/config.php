<?php

Config::set('site_name', 'Films');

//Routes. Route name => method prefix

Config::set('routes', array(
    'default' => ''
));

Config::set('default_route', 'default');
Config::set('default_controller', 'films');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'filmsDB');