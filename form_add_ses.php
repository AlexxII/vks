<!DOCTYPE HTML>                              <html>
<head>
<title> Формы </title>

<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">

<link rel="stylesheet" type="text/css" href="/css/main_style.css">
<link rel="stylesheet" type="text/css" href="/css/main_form_style.css">

<link href="/css/datepicker.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.min.css">
<link rel="stylesheet" href="/lib/awesome/css/font-awesome.min.css">

<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script> 
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<script src="/js/datepicker.min.js"></script>
<script src="/js/timepicker.js"></script>
<script src="/lastNames.js"></script>

<style>
#hide_block_spb {
	display:none;
}
#hide_block_uis {
	display:none;
}
#form_action_hide_wrap {
	display:none;
}
#form_action_hide_wrap_p {
	display:none;
}
#main_text_hide_p {
 	display:none;
}
</style>

<script>
	$(document).ready(function($){
		var ind = $('#main_select_order').find('option:selected').val();
			if (ind == 2 )
				$("#hide_block").fadeIn();
	});
</script>

<script>
	$(function ()
		{
		$.mask.definitions['H']='[012]';
		$.mask.definitions['M']='[012345]';
		$('#eITDbegintime').mask('H9:M9',{
	                            placeholder: "_",
	                            completed: function()
	                            {
	                                var val = $(this).val().split(':');
	                                if ( val[0]*1 > 23) val[0] = '23';
	                                if ( val[1]*1 > 59) val[1] = '59';
	                                $(this).val( val.join(':') );
	                                $(this).next(':input').focus();
	                            }
	                        }
		);
		$('#eITDbegintimee').mask('H9:M9',{
	                            placeholder: "_",
	                            completed: function()
	                            {
	                                var val = $(this).val().split(':');
	                                if ( val[0]*1 > 23) val[0] = '23';
	                                if ( val[1]*1 > 59) val[1] = '59';
	                                $(this).val( val.join(':') );
	                            }
	                        }
		);
		})
</script>

<script>
	$(document).ready(function(){
	$('#govRFNames').autocomplete( { source : govRF} );
});
</script>

<script>
	$(document).ready(function(){
	$('#form_input_headlow_right').autocomplete( { source : pmo} );
});

</script>

<script>
	$(document).ready(function(){
	$("#main_select_order").change(function(){
	if($(this).val() == 2) {
		$("#hide_block_spb").fadeIn();
		$("#hide_block_uis").fadeOut();
	}
	else if ($(this).val() == 5)
	{
		$("#hide_block_uis").fadeIn();
		$("#hide_block_spb").fadeOut();
	}
	else {
		$("#hide_block_spb").fadeOut();
		$("#hide_block_uis").fadeOut();
	}
	return;
	});  });
</script>


<script>
	$(document).ready(function(){
	$("#main_select_type").change(function(){
	if($(this).val() == 5) {
		$("#form_action_hide").fadeOut(0);
		$("#form_action_hide_wrap").fadeIn(0);
		$("#form_action_hide_wrap_p").fadeIn(0);
		$("#main_text_hide").fadeOut(0);
		$("#form_action_hide_p").fadeOut(0);
		$("#main_text_hide_p").fadeIn(0);
	}
	else {
		$("#form_action_hide_wrap").fadeOut(0);
		$("#form_action_hide_wrap_p").fadeOut(0);
		$("#main_text_hide_p").fadeOut(0);
		$("#form_action_hide").fadeIn(0);
		$("#form_action_hide_p").fadeIn(0);
		$("#main_text_hide").fadeIn(0);
	}
	return;
	});  });
</script>

<script>
	$(document).ready(function(){
	$("#main_select_type").change(function(){
		if($(this).val() == 2) {
			$('#main_select_eq option[value="2"]').prop('selected', true);
			$('#main_select_place option[value="2"]').prop('selected', true);
			$('#main_select_sub option[value="6"]').prop('selected', true);
		} else if ($(this).val() == 3) {
			$('#main_select_eq option[value="3"]').prop('selected', true);
			$('#main_select_place option[value="3"]').prop('selected', true);
			$('#main_select_sub option[value="7"]').prop('selected', true);
		} else if ($(this).val() == 4) {
			$('#main_select_eq option[value="4"]').prop('selected', true);
			$('#main_select_place option[value="5"]').prop('selected', true);
			$('#main_select_sub option[value="3"]').prop('selected', true);
		} else if ($(this).val() == 5) {
			$('#main_select_eq option[value="2"]').prop('selected', true);
			$('#main_select_eq_p option[value="4"]').prop('selected', true);
			$('#main_select_place option[value="2"]').prop('selected', true);
			$('#main_select_place_p option[value="5"]').prop('selected', true);
			$('#main_select_sub option[value="3"]').prop('selected', true);
		} else {
			$('#main_select_eq option[value="1"]').prop('selected', true);
			$('#main_select_place option[value="1"]').prop('selected', true);
			$('#main_select_sub option[value="1"]').prop('selected', true);
		}
		return;
	 }); 
	});
</script>


</head>
<body>

<?php
	include('array_file.php');
	require 'scripts/db_connect.php';


	if ($_GET['id']==0) {

		$a = 0;
		$c = 0;
		$i = 1;
	}
	else {
		if (empty($_GET['copy'])) {
			$i = 0;
			$c = 0;
		} else {
			$i = 1;
			$c = 1;
		}
		$id = $_GET['id'];
		$a = 1;
                
		$stmt = $db->prepare('SELECT * FROM sessions WHERE id = ?' );
		$stmt->execute(array($_GET['id']));
		while ($row = $stmt->fetch(PDO::FETCH_LAZY))
		{
			$id = $row['id'];
			$vks_date = date_create($row['vks_date']);
			$vks_time_start_teh = $row['vks_time_start_teh'];
			$vks_time_start_work = $row['vks_time_start_work'];
			$vks_type = $row['vks_type'];
			$vks_place = $row['vks_place'];
			$vks_subscr_msk_name = $row['vks_subscr_msk_name'];
			$vks_subscr_msk = $row['vks_subscr_msk'];
			$vks_order = $row['vks_order'];
			$vks_order_num = $row['vks_order_num'];
			$vks_equip = $row['vks_equip'];
			$vks_comment = $row['vks_comment'];
		}
	}

?>


<?php if($i==1)	
	echo "<form action='form_add_proc.php' method='post'";
else
	echo "<form action='form_add_proc_update.php?id=" .$id. "' method='post'";
?>


<form name="forma1" id="mainform">
<div class="body_1">
	<div class="div_header">
	        <div class="div_head_left" >
			<p id="header_text" style="color:#f1f1f1"> Журнал проведения сеансов видеосвязи</p>
		</div>
		<div class="div_head_right" style="margin-left:8%" >
			<a title="Таблица" href="_index.php" style="color:#f1f1f1"><i class="fa fa-home" aria-hidden="true"></i></a>
		</div>
	</div>
	<div class="form_main_div">
	  <fieldset class="main_field">
	   <legend style="color: red; font-size: 130%; font-weight:600"> Добавление предстоящего сеанса видеосвязи </legend>
		<div id="form_action" >
			<span id="main_text" style="margin-left:20px">Дата проведения сеанса:</span> 		                         
			<input type="text" id="form_input" required class="datepicker-here" data-position="bottom left" 
				name="vks_date" placeholder="Дата.." value=<?php if ($a==1) echo date_format($vks_date, 'd.m.Y'); ?>> 
		</div>
		<div id="form_action_wrap">
			<fieldset id="field_1" style="border-color: #fff; border-style: none">

				 <div id="form_action_group" style="text-align:center; padding-right:250px">
					<span id="main_text">Время начала тех.сеанса:</span> 		                         
					<input type="text" id="eITDbegintime" name="vks_time_start_teh" placeholder="Время.." 
						value=<?php if($a==1) echo $vks_time_start_teh ?>>
			         </div>	
				 <div id="form_action_group" style="text-align:center; padding-right:250px">
					<span id="main_text">Время начала раб.сеанса:</span> 		                         
					<input type="text" id="eITDbegintimee" name="vks_time_start_work" placeholder="Время.." 
						value=<?php if($a==1) echo $vks_time_start_work ?>> 
			         </div>	
			</fieldset>
		</div>
		<div id="form_action">
			<span id="main_text">Выберите вид Видеосвязи:</span> 		                         			
				<select  id="main_select_type" name="vks_type">
					<?php
						$ar_size = count($arVksType);	
						for ($i=1; $i<=$ar_size; $i++) {
							echo "<option value=" .$i. " ";
								if ($a == 1 && $vks_type == $i)
									echo "selected";
							echo ">" .$arVksType[$i]. "</option>";
						};
					?>
				</select>
		</div>
			<div id="form_action_hide">
				<span id="main_text">Выберите место проведения:</span> 		                         
					<select id="main_select_place" name="vks_place">
						<?php
							$ar_size = count($arVksPlace);	
							for ($i=1; $i<=$ar_size; $i++) {
								echo "<option value=" .$i. " ";
									if ($a == 1 && $vks_place == $i)
										echo "selected";
								echo ">" .$arVksPlace[$i]. "</option>";
							};
						?>  	
					</select>
			</div>
			<div id="form_action_hide_wrap">
				 <div id="form_action_gr_left">
					<span id="main_text">Выберите студию ответчика:</span> 		                         
						<select id="main_select_place" name="vks_place">
							<?php
								$ar_size = count($arVksPlace);	
								for ($i=1; $i<=$ar_size; $i++) {
									echo "<option value=" .$i. " ";
										if ($a == 1 && $vks_place == $i)
											echo "selected";
									echo ">" .$arVksPlace[$i]. "</option>";
								};
							?>  	
						</select>
				</div>
				 <div id="form_action_gr_right">
						<select id="main_select_place_p" name="vks_place_p">
							<?php
								$ar_size = count($arVksPlace);	
								for ($i=1; $i<=$ar_size; $i++) {
									echo "<option value=" .$i. " ";
										if ($a == 1 && $vks_place == $i)
											echo "selected";
									echo ">" .$arVksPlace[$i]. "</option>";
								};
							?>  	
						</select>
					<span id="main_text_right">Выберите студию приема</span><br>
				</div>
			</div>
			<div id="div_wrap">
				<div id="form_action_gr_left">
						<span id="main_text_hide">Выберите пользователя:</span> 		                         
						<span id="main_text_hide_p">Выберите ведущего прием:</span> 		                         
							<select id="main_select_sub" name="vks_subscr_msk">
								<?php
									$ar_size = count($arVksSubscr);	
										for ($i=1; $i<=$ar_size; $i++) {
											echo "<option value=" .$i. " ";
											if ($a == 1 && $vks_subscr_msk == $i)
												echo "selected";
										echo ">" .$arVksSubscr[$i]. "</option>";
									};
								?>
							</select>
				</div>
				 <div id="form_action_gr_right">
						<input type="text" id="govRFNames" name="vks_subscr_msk_name" placeholder="ФИО" 
							value=<?php if($a==1) echo $vks_subscr_msk_name ?>> 
						<span id="main_text_right" style="margin-right:120px">Фамилия</span><br>
				</div>	
			</div>


			<div id="div_wrap">
				<div id="form_action_gr_left">
					<span id="main_text">Распоряжение:</span>
						<select id="main_select_order" name="vks_order">
				                        <?php
				                            $ar_size = count($arVksOrder);
				                            for ($i=1; $i<=$ar_size; $i++) {
				                                echo "<option value=" .$i. " ";
                                				    if ($a == 1 && $vks_order == $i)
				                                        echo "selected";
				                                echo ">" .$arVksOrder[$i]. "</option>";
				                            };
				                        ?>
					</select>
				</div>
				<div id="form_action_gr_right">
						<span id="hide_block_spb" style="padding-left:40px">
							<span style="font-size:22px">9/4/19/5-</span>
							<input name="vks_order_num" type="text"  style="width:40px; height:20px; font-size:18px; margin-left:5px" id="form_input_right"
								value=<?php if($a == 1) echo $vks_order_num ?>>
							<span id="main_text_right">№ распоряжения</span>
						</span>
		
						<span id="hide_block_uis" style="padding-left:40px">
							<span style="font-size:22px">9/4/9/11-</span>
							<input name="vks_order_num2" type="text" style="width:40px; height:20px; font-size:18px; margin-left:5px" id="form_input_right"
								value=<?php if($a == 1) echo $vks_order_num ?>>
							<span id="main_text_right">№ распоряжения</span>
						</span>
				</div>
			</div>
			<div id="form_action_hide_p">
				<span id="main_text">Оборудование:</span> 		                         			
					<select id="main_select_eq" name="vks_equip">
						<?php
							$ar_size = count($arVksEquip);	
							for ($i=1; $i<=$ar_size; $i++) {
								echo "<option value=" .$i. " ";
									if ($a==1 && $vks_equip == $i)
										echo "selected";
								echo ">" .$arVksEquip[$i]. "</option>";
							};
						?>
					</select>
			</div>
			<div id="form_action_hide_wrap_p">
				 <div id="form_action_gr_left">
					<span id="main_text">Оборудование 1ой студии:</span> 		                         
						<select id="main_select_eq" name="vks_equip">
							<?php
								$ar_size = count($arVksEquip);	
								for ($i=1; $i<=$ar_size; $i++) {
									echo "<option value=" .$i. " ";
										if ($a==1 && $vks_equip == $i)
											echo "selected";
									echo ">" .$arVksEquip[$i]. "</option>";
								};
							?>
						</select>
				</div>
				 <div id="form_action_gr_right">
						<select id="main_select_eq_p" name="vks_equip_p">
							<?php
								$ar_size = count($arVksEquip);	
								for ($i=1; $i<=$ar_size; $i++) {
									echo "<option value=" .$i. " ";
										if ($a==1 && $vks_equip == $i)
											echo "selected";
									echo ">" .$arVksEquip[$i]. "</option>";
								};
							?>
						</select>
					<span id="main_text_right">Оборудование 2ой студии</span><br>
				</div>
			</div>
		
		<div id="form_action" style="width:40%; margin-top:10px; margin-left:60px">
			<span id="main_text">Замечания:</span>

			<input style="width:20px; height:10px" type="radio" name="vks_z" value=0
				<?php if (($a == 1 && $vks_z==0) | empty($_GET['copy'])) echo 'checked' ?>> НЕТ
			<input style="width:20px; height:10px" type="radio" name="vks_z" value=1
				<?php if ($a == 1 && $vks_z==1) echo 'checked' ?>> ЕСТЬ
		</div>

		<div id="form_action" style="margin-top:10px">
			<span id="main_text">Примечание:</span> 		                         			
			<textarea name="vks_comment" rows="2" cols="35" style="font-family:tahoma; font-size:12px; margin-left:20px">
				<?php if ($a == 1) echo $vks_comment ?></textarea> 
		</div>
		
		<div id="form_action" style="margin-top:10px">
			<?php if ( $a == 1 | $c != 0 ) : ?>		
				<input class="button_google" type="submit" name="submit" value="Обновить">
			<?php else :?>
				<input class="button_google" type="submit" name="submit" value="Сохранить">

			<?php endif; ?>    
			<?php if ( $a !== 1) : ?>		
				<input class="button_google" type="reset" name="reset" value="Очистить">
			<?php endif; ?>    
		</div>


<script>
	$("form").submit(function(event){
		$(':input').each(function(){
			ret = 0;
			if ($(this).val() == '1') {
				event.preventDefault();
				$(this).css('border','1px solid red');
				return;
			} else {
				ret = 1;
			}
//			console.log(ret);
		return;
		});
		if (ret == 1)
			$('form').unbind('submit')
		return;
	});
</script>

<script>
	$(':input').change(function(){
		$(this).each(function(){
			$(this).css('border', '1px solid grey');
		});
	});

</script>

<script>
	$("#main_select_type").change(function(){
		$('#main_select_eq').css('border', '1px solid grey');
		$('#main_select_place').css('border', '1px solid grey');
		$('#main_select_sub').css('border', '1px solid grey');
	});
</script>

	</fieldset>

</div>

</form>


</body>

</html>

                                                                                                                                                                    