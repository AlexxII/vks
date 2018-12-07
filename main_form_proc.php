<?php

require 'scripts/db_connect.php';

	

	$vks_date = date('Y.m.d', strtotime($_POST['vks_date']));
	$p_create = date("Y-m-d H:i:s");

	if (empty($_POST['vks_time_start_teh']) or empty($_POST['vks_time_end_teh']))
	{
		$vks_time_start_teh = "";
		$vks_time_end_teh = "";	
		$vks_duration_teh = 0;
	}
		
	else {
		$vks_time_start_teh = $_POST['vks_time_start_teh'];
		$vks_time_end_teh = $_POST['vks_time_end_teh'];
		$vks_duration_teh = ((date('H', strtotime($vks_time_end_teh))*60) + date('i', strtotime($vks_time_end_teh))) - ((date('H', strtotime($vks_time_start_teh))*60) + date('i', strtotime($vks_time_start_teh)));
	}

	if (empty($_POST['vks_time_start_work']) or empty($_POST['vks_time_end_work']))
	{
		$vks_time_start_work = "";
		$vks_time_end_work = "";	
		$vks_duration_work = 0;
	}
		
	else {
		$vks_time_start_work = $_POST['vks_time_start_work'];
		$vks_time_end_work = $_POST['vks_time_end_work'];
		$vks_duration_work = ((date('H', strtotime($vks_time_end_work))*60) + date('i', strtotime($vks_time_end_work))) - ((date('H', strtotime($vks_time_start_work))*60) + date('i', strtotime($vks_time_start_work)));
	}

	$vks_subscr_msk_name = (!empty($_POST['vks_subscr_msk_name']) ? $_POST['vks_subscr_msk_name'] : "");

	if (!empty($_POST['vks_order_num2']))
		$vks_order_num = (!empty($_POST['vks_order_num2']) ? $_POST['vks_order_num2'] : "");
	else
		$vks_order_num = (!empty($_POST['vks_order_num']) ? $_POST['vks_order_num'] : "");


	$vks_subscr_mur = (!empty($_POST['vks_subscr_mur']) ? $_POST['vks_subscr_mur'] : "");


	if (!empty($_POST['vks_subscr_mur_p']))	
		$vks_subscr_mur_p = (!empty($_POST['vks_subscr_mur_p']) ? $_POST['vks_subscr_mur_p'] : "");

	$vks_comment = $_POST['vks_comment'];
	$coming = 0;

	$sql = "INSERT INTO sessions (vks_date, p_create, vks_time_start_teh, vks_time_end_teh, vks_duration_teh, vks_time_start_work, vks_time_end_work, vks_duration_work,
					vks_type, vks_place, vks_subscr_msk, vks_subscr_msk_name, vks_order, vks_order_num, 
					vks_admin, vks_subscr_mur, vks_equip, vks_z, vks_comment, coming) VALUES (:vks_date, :p_create, :vks_time_start_teh,
					:vks_time_end_teh, :vks_duration_teh, :vks_time_start_work, :vks_time_end_work, :vks_duration_work, :vks_type, :vks_place, :vks_subscr_msk, 
					:vks_subscr_msk_name, :vks_order, :vks_order_num, :vks_admin, :vks_subscr_mur, :vks_equip, :vks_z, :vks_comment, :coming)";
	
	$query = $db->prepare($sql);
	$query->execute(array(':vks_date' => $vks_date,
				':p_create' => $p_create,
				':vks_time_start_teh' => $vks_time_start_teh,
				':vks_time_end_teh' => $vks_time_end_teh,
				':vks_duration_teh' => $vks_duration_teh,
				':vks_time_start_work' => $vks_time_start_work,
				':vks_time_end_work' => $vks_time_end_work,
				':vks_duration_work' => $vks_duration_work,
				':vks_type' => $_POST['vks_type'],
				':vks_place' => $_POST['vks_place'],
				':vks_subscr_msk' => $_POST['vks_subscr_msk'],
				':vks_subscr_msk_name' => $vks_subscr_msk_name,
				':vks_order' => $_POST['vks_order'],
				':vks_order_num' => $vks_order_num,
				':vks_admin' => $_POST['vks_admin'],
				':vks_subscr_mur' => $vks_subscr_mur,
				':vks_equip' => $_POST['vks_equip'],
				':vks_z' => $_POST['vks_z'],
				':vks_comment' => $vks_comment,
				':coming' => $coming));
	$id_b = $db->lastInsertId();

	if ($_POST['vks_type'] == 5)
		$query->execute(array(':vks_date' => $vks_date,
					':p_create' => $p_create,
					':vks_time_start_teh' => $vks_time_start_teh,
					':vks_time_end_teh' => $vks_time_end_teh,
					':vks_duration_teh' => $vks_duration_teh,
					':vks_time_start_work' => $vks_time_start_work,
					':vks_time_end_work' => $vks_time_end_work,
					':vks_duration_work' => $vks_duration_work,
					':vks_type' => $_POST['vks_type'],
					':vks_place' => $_POST['vks_place_p'],
					':vks_subscr_msk' => $_POST['vks_subscr_msk'],
					':vks_subscr_msk_name' => $vks_subscr_msk_name,
					':vks_order' => $_POST['vks_order'],
					':vks_order_num' => $vks_order_num,
					':vks_admin' => $_POST['vks_admin_p'],
					':vks_subscr_mur' => $vks_subscr_mur_p,
					':vks_equip' => $_POST['vks_equip_p'],
					':vks_z' => $_POST['vks_z'],
					':vks_comment' => $vks_comment,
					':coming' => $coming));
		$id_b_p = $db->lastInsertId();
		

	$sql = "INSERT INTO sesback (id, vks_date, p_create, vks_time_start_teh, vks_time_end_teh, vks_duration_teh, vks_time_start_work, vks_time_end_work, vks_duration_work,
					vks_type, vks_place, vks_subscr_msk, vks_subscr_msk_name, vks_order, vks_order_num, 
					vks_admin, vks_subscr_mur, vks_equip, vks_z, vks_comment, coming) VALUES (:id, :vks_date, :p_create, :vks_time_start_teh,
					:vks_time_end_teh, :vks_duration_teh, :vks_time_start_work, :vks_time_end_work, :vks_duration_work, :vks_type, :vks_place, :vks_subscr_msk, 
					:vks_subscr_msk_name, :vks_order, :vks_order_num, :vks_admin, :vks_subscr_mur, :vks_equip, :vks_z, :vks_comment, :coming)";
	
	$query = $db->prepare($sql);
	$query->execute(array(':id' => $id_b,
				':vks_date' => $vks_date,
				':p_create' => $p_create,
				':vks_time_start_teh' => $vks_time_start_teh,
				':vks_time_end_teh' => $vks_time_end_teh,
				':vks_duration_teh' => $vks_duration_teh,
				':vks_time_start_work' => $vks_time_start_work,
				':vks_time_end_work' => $vks_time_end_work,
				':vks_duration_work' => $vks_duration_work,
				':vks_type' => $_POST['vks_type'],
				':vks_place' => $_POST['vks_place'],
				':vks_subscr_msk' => $_POST['vks_subscr_msk'],
				':vks_subscr_msk_name' => $vks_subscr_msk_name,
				':vks_order' => $_POST['vks_order'],
				':vks_order_num' => $vks_order_num,
				':vks_admin' => $_POST['vks_admin'],
				':vks_subscr_mur' => $vks_subscr_mur,
				':vks_equip' => $_POST['vks_equip'],
				':vks_z' => $_POST['vks_z'],
				':vks_comment' => $vks_comment,
				':coming' => $coming));

	if ($_POST['vks_type'] == 5)
		$query->execute(array(':id' => $id_b_p,
					':vks_date' => $vks_date,
					':p_create' => $p_create,
					':vks_time_start_teh' => $vks_time_start_teh,
					':vks_time_end_teh' => $vks_time_end_teh,
					':vks_duration_teh' => $vks_duration_teh,
					':vks_time_start_work' => $vks_time_start_work,
					':vks_time_end_work' => $vks_time_end_work,
					':vks_duration_work' => $vks_duration_work,
					':vks_type' => $_POST['vks_type'],
					':vks_place' => $_POST['vks_place_p'],
					':vks_subscr_msk' => $_POST['vks_subscr_msk'],
					':vks_subscr_msk_name' => $vks_subscr_msk_name,
					':vks_order' => $_POST['vks_order'],
					':vks_order_num' => $vks_order_num,
					':vks_admin' => $_POST['vks_admin_p'],
					':vks_subscr_mur' => $vks_subscr_mur_p,
					':vks_equip' => $_POST['vks_equip_p'],
					':vks_z' => $_POST['vks_z'],
					':vks_comment' => $vks_comment,
					':coming' => $coming));
		

	echo  "<p style='size:30px; color:red'>Запись добавлена";
	
	$db = null;
	
	header("Location: /_index.php");

//	header('Refresh: 1.5; /_index.php', true, 303);

?>