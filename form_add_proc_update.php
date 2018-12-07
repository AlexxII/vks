<?php

require 'scripts/db_connect.php';

	$vks_date = date('Y.m.d', strtotime($_POST['vks_date']));
	$p_create = date("Y-m-d H:i:s");

	$vks_time_start_teh = $_POST['vks_time_start_teh'];
		
	$vks_time_start_work = $_POST['vks_time_start_work'];

	$vks_subscr_msk_name = (!empty($_POST['vks_subscr_msk_name']) ? $_POST['vks_subscr_msk_name'] : "");

	if (!empty($_POST['vks_order_num2']))
		$vks_order_num = (!empty($_POST['vks_order_num2']) ? $_POST['vks_order_num2'] : "");
	else
		$vks_order_num = (!empty($_POST['vks_order_num']) ? $_POST['vks_order_num'] : "");

	$vks_comment = (!empty($_POST['vks_comment']) ? $_POST['vks_comment'] : "");

	$sql = "UPDATE sessions SET vks_date=?, 
					vks_time_start_teh=?, 
					vks_time_start_work=?, 
					vks_type=?,
					vks_place=?,
					vks_subscr_msk=?, 
					vks_subscr_msk_name=?, 
					vks_order=?, 
					vks_order_num=?, 
					vks_equip=?, 
					vks_comment=?
					WHERE id=?";

	$query = $db->prepare($sql);
	$query->execute(array($vks_date, 
					$vks_time_start_teh, 
					$vks_time_start_work, 
					$_POST['vks_type'], 
					$_POST['vks_place'],
					$_POST['vks_subscr_msk'], 
					$vks_subscr_msk_name,
					$_POST['vks_order'], 
					$vks_order_num, 
					$_POST['vks_equip'],
					$vks_comment,

					$_GET['id']  ));


	$db = null;
	echo  "<p style='size:30px; color:red'>Запись исправлена";
	header("Location: /_index.php");
?>