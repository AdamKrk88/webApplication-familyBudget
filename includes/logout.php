<?php
session_start();
require 'autoloader.php';
Authorization::destroySessionCompletely();
Url::redirect('/');