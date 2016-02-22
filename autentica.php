<?php
session_start();
include 'inc/autenticacao.php';

if(!isset($_POST['login-user']) || !isset($_POST['login-password']))
	header('location: index.php');

$auth = new Autenticacao();

$auth->user	= isset($_POST['login-user']) ? $_POST['login-user'] : '';
$auth->pass	= isset($_POST['login-password']) ? $_POST['login-password'] : '';

$_SESSION['user'] = $auth->ValidaLogin();

if($_SESSION['user']!=null)
	header('location: dashboard.php');
else{
	header('location: ./?message=Usuario não validado');
}

?>