<?php
require_once('condb/include.php');
$_SESSION["user_id"] = null;
$_SESSION["username"] = null;
$_SESSION["role"] = null;
redirect_to("login.php");