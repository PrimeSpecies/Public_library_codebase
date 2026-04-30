<?php

use App\Controllers\DocumentController;
$router->post('/register', 'AuthController@register');
// routes/web.php
$router->post('upload-doc', [DocumentController::class, 'upload']);