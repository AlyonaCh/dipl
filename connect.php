<?php
define('DB_DRIVER', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'achernyaeva');
define('DB_USER', 'achernyaeva');
define('DB_PASS', 'neto1743');
  $connect_str=DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME;
  $db=new PDO($connect_str,DB_USER,DB_PASS);
  //require_once 'vendor/autoload.php';
  /*Twig_Autoloader::register();
  $loader = new Twig_Loader_Filesystem('template');
  $twig = new Twig_Environment($loader);
  $template = $twig->loadTemplate('list.php');*/
  ?>
