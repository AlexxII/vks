<?php

require 'scripts/db_connect.php';
require 'scripts/db_connect.php';

try
{
	$stmt = $db->prepare('SELECT * FROM sessions WHERE id = ?' );
	$stmt->execute(array($_GET['id']));
}

catch(PDOException $e)
{
	die("Error: " .$e->getMessage());
}

	$db = null;

?>