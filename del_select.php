<?php

require 'scripts/db_connect.php';
require 'scripts/db_connect.php';

try
{
	$stmt = $db->prepare('DELETE FROM sessions WHERE id = ?' );
	$stmt->execute(array($_GET['id']));

	$del_flag = 1;

	$query = $db->prepare("UPDATE sesback SET del_flag=? WHERE id=?");
	$query->execute(array($del_flag,
				$_GET['id']));

}

catch(PDOException $e)
{
	die("Error: " .$e->getMessage());
}

	$db = null;

	header("Location: /_index.php");

?>