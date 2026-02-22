<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth_helper.php';

destroyAccessToken();

header('Location: login.php');
exit;
