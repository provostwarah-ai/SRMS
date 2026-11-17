<?php
session_start();
$pdo=new PDO('mysql:host=localhost;dbname=srms;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function grade($t){return $t>=70?'A':($t>=60?'B':($t>=50?'C':($t>=45?'D':($t>=40?'E':'F'))));}
function need($r){if(($_SESSION['role']??'')!==$r){header('Location: index.php');exit;}}
function token(){return $_SESSION['t']??($_SESSION['t']=bin2hex(random_bytes(16)));}
function chk(){if(!hash_equals($_SESSION['t']??'',$_POST['t']??''))die('Invalid token');}
