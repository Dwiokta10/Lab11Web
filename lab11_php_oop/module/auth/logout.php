<?php

$baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : '';

unset($_SESSION['auth_user']);

header('Location: ' . $baseUrl . '/auth/login?logged_out=1');
exit;
