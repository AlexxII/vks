<!DOCTYPE HTML>                              <html>
<head>
<title> Подробнее </title>

<meta http-equiv="Content-Type" content="text/html" charset="utf-8">

<script type="text/javascript" src="/javascript/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.js"></script>

<link href="/css/datepicker.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="lib/awesome/css/font-awesome.min.css">

<link rel="stylesheet" href="lib/awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/css/main_style.css">
<link rel="stylesheet" type="text/css" href="/css/about_style.css">


</head>
<body>

	<?php
		require 'scripts/app_config.php';
		require 'scripts/db_connect.php';

		session_start();

		if (!isset($_SESSION['main_date_bgn'])) {
			$main_date_bgn = '2017.01.01';
			$main_date_end = '2099.12.31';
		} else {
			$main_date_bgn = $_SESSION['main_date_bgn'];
			$main_date_end = $_SESSION['main_date_end'];
		}

		if (empty($_GET['main_sort']))
			$main_sort = 'vks_date';
		else
			$main_sort = $_GET['main_sort'];
		$stmt = $db->prepare("SELECT * FROM sessions WHERE vks_date >= :prm1 AND vks_date <= :prm2");
		$stmt->execute(array(':prm1' => $main_date_bgn,
					':prm2' => $main_date_end));

		while ($row = $stmt->fetch(PDO::FETCH_LAZY))
		{
			$id = $row['id'];

			$vks_date = $row['vks_date'];

			$vks_time_start_teh = $row['vks_time_start_teh'];
			$vks_time_start_work = $row['vks_time_start_work'];

			$vks_time_end_work = $row['vks_time_end_work'];
			$vks_time_end_teh = $row['vks_time_end_teh'];
			$vks_type = $row['vks_type'];
			$vks_place = $row['vks_place'];

			$vks_subscr_msk_name = $row['vks_subscr_msk_name'];

			$vks_subscr_msk = $row['vks_subscr_msk'];
			$vks_subscr_mur = $row['vks_subscr_mur'];

			$vks_subscr_mur = $row['vks_subscr_mur'];

			$vks_order = $row['vks_order'];
			$vks_order_num = $row['vks_order_num'];

			$vks_order_num = $row['vks_order_num'];

			$vks_admin = $row['vks_admin'];
			$vks_equip = $row['vks_equip'];
			$vks_comment = $row['vks_comment'];
			$vks_z = $row['vks_z'];
		}


		?>


<div class="body_1">

	<div class="div_header">
	        <div class="div_head_left" >
			<p id="header_text" style="color:#f1f1f1"> Журнал проведения сеансов видеосвязи</p>
		</div>
		<div class="div_head_right" style="margin-left:7%">
			<a title="Таблица" href="_index.php"><i class="fa fa-home" aria-hidden="true"></i></a>
		</div>
	</div>


	<div class="form_main_div">
	  <fieldset class="main_field">
	   <legend style="color: red; font-size: 130%; font-weight:600;">Статистика проведения сеансов видеосвязи</legend>



	<table class="about_table">
		<tr>
			<td id="left_td">Продолжительность технических сеансов КВС: </td>
			<td id "right_td"><?php echo $vks_date; ?></td>
		</tr>

		<?php if ( $vks_time_start_teh !== "") : ?>		
		<tr>
			<td id="left_td">Продолжительность рабочих сеансов КВС: </td>
			<td id "right_td"><?php echo $vks_time_start_teh . '-' . $vks_time_end_teh ; ?></td>
		</tr>
		<?php endif; ?>

		<?php if ( $vks_time_start_work !== "") : ?>		
		<tr>
			<td id="left_td">Продолжительность технических сеансов ЗВС-ОГВ: </td>
			<td id "right_td"><?php echo $vks_time_start_work . '-' . $vks_time_end_work ; ?></td>
		</tr>
		<?php endif; ?>

		<tr>
			<td id="left_td">Вид видеосвязи: </td>
			<td id "right_td"><?php echo $vks_type; ?></td>
		</tr>

		<tr>
			<td id="left_td">Место проведения: </td>
			<td id "right_td"><?php echo $vks_place; ?></td>
		</tr>
		<tr>
			<td id="left_td">Пользователь: </td>
			<td id "right_td">
			<?php
			if ($vks_subscr_msk_name !== "")
				echo $vks_subscr_msk_name  . " - " . $vks_subscr_msk;  
			else 
				echo $vks_subscr_msk;  ?> 
			</td>
		</tr>
		<tr>
			<td id="left_td">Распоряжение: </td>
			<td id "right_td">
			<?php
			if ($vks_order_num !== "")
				echo $vks_order  . " - " . $vks_order_num;  
			else 
				echo $vks_order;  ?> 
			</td>
		</tr>
		<tr>
			<td id="left_td">Сотрудник СпецСвязи:</td>
			<td id "right_td"><?php echo $vks_admin; ?></td>
		</tr>
		<tr>
			<td id="left_td">Присутствовали:</td>
			<td id "right_td">
			<?php
			if ($vks_subscr_mur !== "")
				echo $vks_subscr_mur ;  
			else 
				echo "-";  ?>
			</td>
		</tr>
		<tr>
			<td id="left_td">Оборудование: </td>
			<td id "right_td"><?php echo $vks_equip; ?></td>
		</tr>

		
		<tr>
			<td id="left_td">Примечание: </td>
			<td id "right_td">
			<?php 
			if ($vks_z == 0) {
				echo "Замечаний нет </br>" . $vks_comment; }
			else {
				echo " <span style='color:red'>Есть Замечания</span> </br>" . $vks_comment; } ?></td>
		</tr>
	</table>


	  </fieldset>

</div>

</form>


</body>

</html>

