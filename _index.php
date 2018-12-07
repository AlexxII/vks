<!DOCTYPE HTML>                               	
<html>
<head>
<title> Основная таблица </title>

<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1.5, minimum-scale=0.5" />

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/js/datepicker.min.js"></script>

<link href="/css/datepicker.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="lib/awesome/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="/css/_rez_table_style.css">
<link rel="stylesheet" type="text/css" href="/css/main_style.css">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

</head>
<body>

		<?php
			include('array_file.php');
			require 'scripts/db_connect.php';

			session_start();
			
			if (empty($_POST['vks_date_range'])) {
				if (!isset($_SESSION['main_date_bgn'])) {
					$main_date_bgn = '2017.'. '12' . '.01';
					$main_date_end = '2099.12.31';
				} else {
					$main_date_bgn = $_SESSION['main_date_bgn'];
					$main_date_end = $_SESSION['main_date_end'];
				}
			}
			else {
				$main_date_bgn = (substr($_POST['vks_date_range'],6,4) . '.' . substr($_POST['vks_date_range'],3,2) . 
							'.' . substr($_POST['vks_date_range'],0,2));
				$_SESSION['main_date_bgn'] = $main_date_bgn;
				$main_date_end = (substr($_POST['vks_date_range'],19,4) . '.' . substr($_POST['vks_date_range'],16,2) . 
							'.' . substr($_POST['vks_date_range'],13,2));
				$_SESSION['main_date_end'] = $main_date_end;
			}
				
			$num_vks_kvst = countvks(3, 'vks_time_start_teh', $main_date_bgn, $main_date_end);			// колич. тех. квс
			$num_vks_kvsw = countvks(3, 'vks_time_start_work', $main_date_bgn, $main_date_end);			// колич. рвб. квс
			$num_vks_zogvt = countvks(2, 'vks_time_start_teh', $main_date_bgn, $main_date_end);			// колич. тех. звс огв
			$num_vks_zogvw = countvks(2, 'vks_time_start_work', $main_date_bgn, $main_date_end);		// колич. рвб. звс огв
			$num_vks_zprt = countvks(4, 'vks_time_start_teh', $main_date_bgn, $main_date_end);			// колич. тех. звс приемн
			$num_vks_zprw = countvks(4, 'vks_time_start_work', $main_date_bgn, $main_date_end);			// колич. тех. звс приемн
			$num_vks_zzzt = (countvks(5, 'vks_time_start_teh', $main_date_bgn, $main_date_end))/2;		// колич. тех. ЗВС-ЗВС (прием)
			$num_vks_zzzw = (countvks(5, 'vks_time_start_work', $main_date_bgn, $main_date_end))/2;		// колич. тех. ЗВС ЗВС (прием)
			$num_vks_ptsmk = countvksEx(10, $main_date_bgn, $main_date_end);							// колич. птс-мк

			$num_vks_kvssum = countvkssum(3, $main_date_bgn, $main_date_end);							// общее кол-во квс
			$num_vks_zogvsum = countvkssum(2, $main_date_bgn, $main_date_end);							// общее кол-во звс огв
			$num_vks_zprsum = countvkssum(4, $main_date_bgn, $main_date_end);							// общее кол-во звс приемн
			$num_vks_zoutsum = countvkssum(6, $main_date_bgn, $main_date_end);							// кол-во звс на выезде
			$num_vks_zzzsum = countvkssum(5, $main_date_bgn, $main_date_end);							// кол-во звс-звс (прием)
			$num_vks_razb = countrazb($main_date_bgn, $main_date_end);									// кол-во разбегов
			$num_vks_zsum = $num_vks_zogvsum + $num_vks_zprsum + $num_vks_zoutsum + $num_vks_zzzsum;	// общее кол-во звс
			
			$duration_vks = duration(3, $main_date_bgn, $main_date_end);								// продолжительность сеансов КВС
			$duration_vks_teh = $duration_vks[0];
			$duration_vks_work = $duration_vks[1];

			$duration_zvs = duration(2, $main_date_bgn, $main_date_end);								// продолжительность сеансов ЗВС
			$duration_zvs_teh = $duration_zvs[0];
			$duration_zvs_work = $duration_zvs[1];
			                                                                        	
			$duration_zvsp = duration(4, $main_date_bgn, $main_date_end);								// продолжительность сеансов ЗВС-приемн
			$duration_zvsp_teh = $duration_zvsp[0];	
			$duration_zvsp_work = $duration_zvsp[1];
			

function duration ($var1, $var2 = '2017.01.01', $var3 = '2099.12.31') {
	global $db;
	$stmt = $db->prepare("SELECT vks_duration_teh, vks_duration_work FROM sessions WHERE vks_type = :param AND 
				vks_date >= :pram AND vks_date <= :pram2 AND coming != 1 ");
	$stmt->execute(array(':param' => $var1,
				':pram' => $var2,
				':pram2' => $var3 ));
	$v1=0;
	$v2=0;
	$ret = array();
	foreach ($stmt as $row) {
		$v1 += $row['vks_duration_teh'];
		$v2 += $row['vks_duration_work'];
	}
		$ret[0] = $v1;
		$ret[1] = $v2;
		return $ret;
	}

	
function countrazb ($var2 = '2017.01.01', $var3 = '2099.12.31') {
	global $db;
	$stmt = $db->prepare("SELECT count(*) FROM sessions WHERE (vks_order = 5 OR vks_order = 6) AND 
				vks_date >= :pram AND vks_date <= :pram2 AND coming != 1");
	$stmt->execute(array(':pram' => $var2,
				':pram2' => $var3 ));
	return $stmt->fetchColumn();
	}

function countvkssum ($var1, $var2 = '2017.01.01', $var3 = '2099.12.31') {
	global $db;
	$stmt = $db->prepare("SELECT count(*) FROM sessions WHERE vks_type = :param AND 
				(vks_duration_teh != 0 OR vks_duration_work != 0) AND vks_date >= :pram AND vks_date <= :pram2 AND coming != 1 ");
	$stmt->execute(array(':param' => $var1,
				':pram' => $var2,
				':pram2' => $var3 ));
	return $stmt->fetchColumn();
	}


function countvks ($var1, $param2, $var2 = '2017.01.01', $var3 = '2099.12.31') {
	global $db;
	$stmt = $db->prepare("SELECT count(*) FROM sessions WHERE vks_type = :param AND 
				{$param2} != '' AND vks_date >= :pram AND vks_date <= :pram2 AND coming != 1");
	$stmt->execute(array(':param' => $var1,
				':pram' => $var2,
				':pram2' => $var3 ));
	return $stmt->fetchColumn();
	}

function countvksEx ($var1, $var2 = '2017.01.01', $var3 = '2099.12.31') {
	global $db;
	$stmt = $db->prepare("SELECT count(*) FROM sessions WHERE vks_order = :param AND 
				vks_date >= :pram AND vks_date <= :pram2 AND coming != 1");
	$stmt->execute(array(':param' => $var1, 
				':pram' => $var2,
				':pram2' => $var3 ));
	return $stmt->fetchColumn();
	}
		?>


<div class="body_1">

	<div class="div_header">
<!--		<div style="width:30px; height:44px" id="logo"><img style="width:30px; height:44px" src="images/logo.gif" /></div> 		-->

	        <div class="div_head_left" >
			<p id="header_text" style="color:#f1f1f1"> Журнал проведения сеансов видеосвязи</p>
		</div>
		<div class="div_head_right" style="margin-left:5%" >
			<a title="Добавить предстоящий сеанс" href="form_add_ses.php?id=0"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></a>
		</div>															
		<div class="div_head_right">
			<a title="Добавить прошедший сеанс" href="main_form.php?id=0"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></a>
		</div>
	</div>

	<div class="div_navigation">
		<div class="wrap">
			<div>
				<span title="<?php echo "Продолжительность: " . $duration_vks_teh ." мин" ?>">Кол-во техн.сеансов КВС:</span> 
					<span id="nav_res"><?php echo $num_vks_kvst; ?> </span></br> 
				<span title="<?php echo "Продолжительность: " . $duration_vks_work ." мин" ?>">Кол-во рабоч.сеансов КВС:</span> 
					<span id="nav_res"><?php echo $num_vks_kvsw; ?> </span></br>  
				<span>Общее кол-во сеансов КВС:</span> <span id="nav_res"><?php echo $num_vks_kvssum; ?> </span>  
			</div>

			<div>
				<span title="<?php echo "Продолжительность: " . $duration_zvs_teh ." мин" ?>">Кол-во техн.сеансов ЗВС-ОГВ:</span> 
					<span id="nav_res"><?php echo $num_vks_zogvt+$num_vks_zzzt; ?> </span></br> 
				<span title="<?php echo "Продолжительность: " . $duration_zvs_work ." мин" ?>">Кол-во рабоч.сеансов ЗВС-ОГВ:</span> 
					<span id="nav_res"><?php echo $num_vks_zogvw+$num_vks_zzzw; ?> </span></br> 
				<span>Общее кол-во сеансов ЗВС-ОГВ:</span> <span id="nav_res"><?php echo $num_vks_zogvsum+($num_vks_zzzsum/2); ?> </span> 
			</div>

			<div>
				<span title="<?php echo "Продолжительность: " . $duration_zvsp_teh ." мин" ?>">Кол-во тех.сеансов ЗВС-Приемн:</span> 
					<span id="nav_res"><?php echo $num_vks_zprt+$num_vks_zzzt; ?> </span></br> 
				<span title="<?php echo "Продолжительность: " . $duration_zvsp_work ." мин" ?>">Кол-во рабоч.сеансов ЗВС-Приемн:</span> 
					<span id="nav_res"><?php echo $num_vks_zprw+$num_vks_zzzw; ?> </span></br> 
				<span>Общее кол-во сеансов ЗВС-Приемн:</span> <span id="nav_res"><?php echo $num_vks_zprsum+($num_vks_zzzsum/2); ?> </span> 
			</div>

			<div>
				<span>Кол-во тренировок ПТС-МК:</span> <span id="nav_res"><?php echo $num_vks_ptsmk; ?> </span></br> 
				<span>Кол-во тренировок "Разбег":</span> <span id="nav_res"><?php echo $num_vks_razb; ?> </span></br> 
				<span>Общее кол-во сеансов ЗВС":</span> <span id="nav_res"><?php echo $num_vks_zsum; ?> </span> 
			</div>
		</div>


		<div class="div_period">
				<span style="font-family:'FontAwesome', Verdana serif; letter-spacing:4px; color:red; font-size:25px">
					<i style="color:#6E7B8B; font-size:25px; padding-right:4px" class="fa fa-calendar" aria-hidden="true"></i>Период</span>
				<form  style="padding-top:5px"action="_index.php" method="post"> 
					<input style="width:180px; font-size:15px; height:20px" type="text" data-range="true" 
						data-multiple-dates-separator=" - " class="datepicker-here" name="vks_date_range" placeholder="Выберите период"/>
					<input type="submit" name="submit" value="..">
				</form>
		</div>
	</div>                                                                                                       


</div>


<div class="main_tabl_div">
		
		<table id="main_tabl">
		<thead>
			<tr>
				<th id="table_header_text" style="width:2.5%">№ п/п</th>
				<th id="table_header_text" style="width:6%"><a title="Сортировка по дате" href="_index.php?main_sort=vks_date">Дата сеанса</th>
				<th id="table_header_text" style="width:8%">Время проведения</br></th>
				<th id="table_header_text" style="width:7%"><a title="Сортировка по виду" href="_index.php?main_sort=vks_type">Вид сеанса</th>
				<th id="table_header_text" style="width:12%"><a title="Сортировка по месту проведения" href="_index.php?main_sort=vks_place">Место проведения</th>
				<th id="table_header_text" style="width:17%"><a title="Сортировка по абоненту" href="_index.php?main_sort=vks_subscr_msk">Абонент</th>
				<th id="table_header_text" style="width:16%"><a title="Сортировка по распоряжению" href="_index.php?main_sort=vks_order">Распоряжение</th>
				<th id="table_header_text" style="width:8.5%">Сотрудник СпецСвязи</th>
				<th id="table_header_text" style="width:15%">Примечание</th>
				<th id="table_header_text" style="width:7%" ><i class="fa fa-cogs" aria-hidden="true" style="color:white; font-size:25px" ></i></th>
			</tr>
		<thead>


		<?php

			if (empty($_GET['main_sort']))
				$main_sort = 'vks_date';
			else
				$main_sort = $_GET['main_sort'];

/*				$stmt = $db->prepare("SELECT id, vks_date, vks_time_start_teh, vks_time_end_teh, vks_time_start_work, vks_time_end_work,
						vks_type, vks_subscr_msk, vks_subscr_msk_name, vks_order, vks_order_num, vks_admin, vks_subscr_mur,
						vks_equip, vks_comment, vks_z, coming, vks_duration_teh, vks_duration_work,
						group_concat(case when vks_type=5 then vks_place else vks_place end) as vks_place,
						group_concat(case when vks_type=5 then vks_admin else vks_admin end) as vks_admin,
						group_concat(DISTINCT case when vks_type=5 then vks_subscr_mur else vks_subscr_mur end SEPARATOR ' ') as vks_subscr_mur
						FROM uchet.sessions GROUP BY vks_type having vks_type != 6");
*/
				$stmt = $db->prepare("SELECT * FROM sessions WHERE vks_date >= :prm1 AND vks_date <= :prm2 ORDER BY {$main_sort} ");
				$stmt->execute(array(':prm1' => $main_date_bgn,
						':prm2' => $main_date_end));

				$i = 0;
				foreach ($stmt as $row) :  ?>

			<tr <?php 

				if ($row['coming'] == 1 && $row['vks_date'] >= date('Y-m-d')) 
					echo "style='background: #fefcea; background: linear-gradient(to top, #e6ecff, #b3c6ff)'";
				elseif ($row['coming'] == 1 &&  $row['vks_date'] < date('Y-m-d'))				
					echo "style='background: #fefcea; background: linear-gradient(to top, #ffe6e6, #ffb3b3)'";

						?>>	
				<?php $i++; 
				$vks_z = $row['vks_z'];
				$vks_date = date_create($row['vks_date']);
				$vks_comment = $row['vks_comment'];
				$vks_subscr_msk_name = $row['vks_subscr_msk_name'];
	
 						?>
				<td id="table_td_text"><?php echo $i ?> </td>
				<td id="table_td_text"><?php 


					if ($row['coming'] == 1 &&  $row['vks_date'] <= date('Y-m-d'))
						echo date_format($vks_date, 'd.m.Y') . '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:red; font-size:15px; margin-left:5px"></i>'; 
					else
						echo date_format($vks_date, 'd.m.Y');

				?></td>
				<td id="table_td_text"><?php 
					if ($row['coming'] == 1 )
						echo $row['vks_time_start_teh'] . ' / т';
					else {
						echo $row['vks_time_start_teh'] . '-' . $row['vks_time_end_teh'] . ' / т';
							if ($row['vks_duration_teh'] <= 0 && $row['vks_time_start_teh']!=0) 
								echo '<span style="color:red"> !</span>'; 
					}
						?> 
				
				<br><?php 
					if ($row['coming'] == 1 )
						echo $row['vks_time_start_work'] . ' / <span style="font-size:11px">Р</span>';
					else {
						echo $row['vks_time_start_work'] . '-' . $row['vks_time_end_work'] . ' / <span style="font-size:11px">Р</span>';
							if($row['vks_duration_work'] <= 0 && $row['vks_time_start_work']!=0) 
								echo '<span style="color:red"> !</span>'; 
					}
						?> 
										 
				</td>
				<td id="table_td_text"><?php echo $arVksType[$row['vks_type']] ?></td>
				<td id="table_td_text"><?php// echo $arVksPlace[$row['vks_place']] ?></td>
				<td id="table_td_text">
				<?php 
				if ($vks_subscr_msk_name == "") {
					echo $arVksSubscr[$row['vks_subscr_msk']]; }
				else {
					echo $row['vks_subscr_msk_name'] . "<br>" . $arVksSubscr[$row['vks_subscr_msk']]; } ?> </td>
				<td id="table_td_text"><?php 
					echo $arVksOrder[$row['vks_order']] ?> <br> <?php
					if ($row['vks_order'] == 2)
						echo '9/4/19/5-'. $row['vks_order_num'] . 'оа';
					elseif ($row['vks_order'] == 5)
						echo '9/4/9/11-'. $row['vks_order_num'] . 'оа';
				?></td>
				<td id="table_td_text"><?php

				$j = 2;

				$str = preg_replace("/[^0-9]/", ' ', $row['vks_admin']);

				echo $str;
//				$ar = array($row['vks_admin']);
				echo count($str);

//				for ($j = 0; $j < count($row['vks_admin'][$j])-1; $j++ )
//					echo $arVksAdmin[$row['vks_admin'][$j]] . ", "; 

									?></td>
				<td id="table_td_text">
				<?php
				if ($row['vks_subscr_mur'] !== "")
					echo "Присутствовали: " . $row['vks_subscr_mur'] . "</br>";
				if ($vks_z == 0) {
					echo "Замечаний нет </br>" . $vks_comment; }
				else {
					echo " <span style='color:red'>Есть замечания </span></br>" . $vks_comment; } ?>
				</td>

				<?php if ( $row['coming'] !== "1") : ?>		
				<td id="table_td_text"> <a title="Удалить" href="del_select.php?id=<?php echo $row['id'] ?>"> 
								<i class="fa fa-trash-o" aria-hidden="true"></i></a>
							<a title="Редактировать" href="main_form.php?id=<?php echo $row['id'] ?>&a=1"> 
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a title="Обзор" href="about_form.php?id=<?php echo $row['id'] ?>"> 
								<i class="fa fa-info" aria-hidden="true"></i></a>
							<a title="Копировать запись" href="main_form.php?id=<?php echo $row['id'] ?>&copy=1"> 
								<i class="fa fa-files-o" aria-hidden="true"></i></a>
				</td>
				
				<?php else : ?>

				<td id="table_td_text"> <a title="Удалить" href="del_select.php?id=<?php echo $row['id'] ?>"> 
								<i class="fa fa-trash-o" aria-hidden="true"></i></a>
							<a title="Редактировать предстоящий сеанс" href="form_add_ses.php?id=<?php echo $row['id'] ?>"> 
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a title="Обзор" href="about_form_add.php?id=<?php echo $row['id'] ?>"> 
								<i class="fa fa-info" aria-hidden="true"></i></a>
							<a title="Подтвердить состоявшийся сеанс" href="main_form.php?id=<?php echo $row['id'] ?>"> 
								<i class="fa fa-calendar-check-o" aria-hidden="true"></i></a>
				</td>

				<?php endif; ?>

			</tr>
			<?php endforeach;

			$pdo = null;

					?>
		</table>
			<a id="goup" href="#"><i class="fa fa-arrow-up" style="font-size:35px; color:#cccccc" aria-hidden="true"></i></a>		


</div>
</body>
</html>