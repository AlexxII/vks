<!DOCTYPE HTML>                              <html>
<head>
<title> Подробнее </title>

<meta http-equiv="Content-Type" content="text/html" charset="utf-8">

<link rel="stylesheet" href="lib/awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/css/about_style.css">
<link rel="stylesheet" type="text/css" href="/css/main_style.css">

</head>
<body>

	<?php

		include('array_file.php');

		require 'scripts/db_connect.php';

		$stmt = $db->prepare('SELECT * FROM sessions WHERE id = ?' );
	
		$stmt->execute(array($_GET['id']));

		while ($row = $stmt->fetch(PDO::FETCH_LAZY))
		{
			$id = $row['id'];

			$vks_date = date_create($row['vks_date']);

			$vks_time_start_teh = $row['vks_time_start_teh'];
			$vks_time_start_work = $row['vks_time_start_work'];
			$vks_time_end_work = $row['vks_time_end_work'];
			$vks_time_end_teh = $row['vks_time_end_teh'];
			$vks_subscr_msk_name = $row['vks_subscr_msk_name'];


			$vks_type = $arVksType[$row['vks_type']];
			$vks_place = $arVksPlace[$row['vks_place']];
			$vks_subscr_msk = $arVksSubscr[$row['vks_subscr_msk']];
			$vks_order = $row['vks_order'];
			$vks_admin = $arVksAdmin[$row['vks_admin']];
			$vks_equip = $arVksEquip[$row['vks_equip']];


			$vks_subscr_mur = $row['vks_subscr_mur'];
			$vks_order_num = $row['vks_order_num'];
			$vks_comment = $row['vks_comment'];
			$vks_z = $row['vks_z'];
		}


		?>


<div class="body_1">

	<div class="div_header">
	        <div class="div_head_left" >
			<p id="header_text" style="color:#f1f1f1"> Журнал проведения сеансов видеосвязи</p>
		</div>
		<div class="div_head_right" style="margin-left:5%">
			<a title="Удалить" href="del_select.php?id=<?php echo $id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
		</div>
		<div class="div_head_right">
			<a title="Редактировать" href="main_form.php?id=<?php echo $id; ?>&a=1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
		</div>
		<div class="div_head_right">
			<a title="Таблица" href="_index.php"><i class="fa fa-home" aria-hidden="true"></i></a>
		</div>
	</div>


	<div class="form_main_div">
	  <fieldset class="main_field">
	   <legend style="color: red; font-size: 130%; font-weight:600;"> Сеанс видеосвязи от <?php echo date_format($vks_date, 'd.m.Y'); ?></legend>

	<table class="about_table">
		<tr>
			<td id="left_td">Дата проведения видеосеанса: </td>
			<td id "right_td"><?php echo date_format($vks_date, 'd.m.Y'); ?></td>
		</tr>

		<?php if ( $vks_time_start_teh !== "") : ?>		
		<tr>
			<td id="left_td">Время проведения технического сеанса: </td>
			<td id "right_td"><?php echo $vks_time_start_teh . '-' . $vks_time_end_teh ; ?></td>
		</tr>
		<?php endif; ?>

		<?php if ( $vks_time_start_work !== "") : ?>		
		<tr>
			<td id="left_td">Время проведения рабочего сеанса: </td>
			<td id "right_td"><?php echo $vks_time_start_work . '-' . $vks_time_end_work ; ?></td>
		</tr>
		<?php endif; ?>

		
		<?php 
		if ($vks_time_start_teh == NULL && $vks_time_start_work == NULL)                                        
			echo " <div style='color:red; padding-left:95px; letter-spacing:4px;'>ВНИМАНИЕ - осутствуют сведения о времени проведения сеансов "; ?>

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
			if ($vks_order == 2)
				echo $arVksOrder[$vks_order]  . " - " . "9/4/19/5-" .$vks_order_num. "оа";  
			else 
				echo $arVksOrder[$vks_order];  ?> 
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


			<?php $db = null ?>

	  </fieldset>

</div>

</form>


</body>

</html>

