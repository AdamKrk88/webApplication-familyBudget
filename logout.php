<?php
session_start();
require 'includes/autoloader.php';
Authorization::checkAuthorization();

Authorization::destroySessionCompletely();
Url::redirect('/');