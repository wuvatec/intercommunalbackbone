<?php
require_once '../../config/app.php';

$user = R::dispense( 'admins' );

$user->username = 'admin';
$user->password = password_hash('pass', PASSWORD_DEFAULT, ['cost' => 12]);
$user->currency = 'USD';
$user->region = 'North_America_Region';
$user->domain = 'wtbintl.com';
$user->website_name = 'Western Trade Bank';
$user->is_active = 1;

R::store($user);

echo 'Admin created';