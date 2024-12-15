<?php
require_once __DIR__ . '/app/routes/indexRoutes.php';

// Made by the dev team at @carrier / ~ Tele @byte_array / ~ 
session_start();
$indexRoutes = new IndexRoutes();
$indexRoutes->index();

