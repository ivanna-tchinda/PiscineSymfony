<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    'home' => [[], ['_controller' => 'App\\ex00\\Controller\\DefaultController::index'], [], [['text', '/ex00']], [], [], []],
    'create_table' => [[], ['_controller' => 'App\\ex00\\Controller\\DefaultController::createTable'], [], [['text', '/create_table']], [], [], []],
    'App\ex00\Controller\DefaultController::index' => [[], ['_controller' => 'App\\ex00\\Controller\\DefaultController::index'], [], [['text', '/ex00']], [], [], []],
    'App\ex00\Controller\DefaultController::createTable' => [[], ['_controller' => 'App\\ex00\\Controller\\DefaultController::createTable'], [], [['text', '/create_table']], [], [], []],
];
