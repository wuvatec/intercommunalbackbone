<?php

// Todo: refactor env variables
$sep = '/';
$base_url = dirname(__DIR__) . $sep;

require $base_url . 'database/rb-mysql.php';

//for both mysql or mariaDB
R::setup('mysql:host=localhost;dbname=qikcbvaf_interkredit','qikcbvaf_interkredit', 'qwenty22');