<?php
//------------------------------------------------------------------------------------
//загрузка файла
//------------------------------------------------------------------------------------
   if($_FILES["filename"]["size"] > 1024*3*1024)
   {
     echo ("Размер файла превышает три мегабайта");
     exit;
   }
   // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
     // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
     move_uploaded_file($_FILES["filename"]["tmp_name"], "images/".$_FILES["filename"]["name"]);

	
	require_once("conect_bd.php");
//------------------------------------------------------------------------------------
//Добавление в базу данных нового авто
//------------------------------------------------------------------------------------	
//	$strSQL = "INSERT INTO catalogue(auto_name,auto_picture,auto_text,auto_year,auto_price) VALUES('".$_POST['name_auto']."','".$_FILES["filename"]["name"]."','".$_POST['text_auto']."','".$_POST['year_auto']."','".$_POST['price_auto']."')"; 

	$strSQL = "INSERT INTO catalogue SET auto_name='".$_POST['auto_name']."',auto_marka='".$_POST['auto_marka']."',auto_year='".$_POST['auto_year']."',auto_kuzov='".$_POST['auto_kuzov']."',auto_price='".$_POST['auto_price']."',auto_text='".$_POST['auto_text']."',auto_kpp='".$_POST['auto_kpp']."',auto_dveri='".$_POST['auto_dveri']."',auto_mesta='".$_POST['auto_mesta']."',auto_moshnost='".$_POST['auto_moshnost']."',auto_zaradka='".$_POST['auto_zaradka']."',auto_zapas_hoda='".$_POST['auto_zapas_hoda']."',auto_privod='".$_POST['auto_privod']."',auto_batareya='".$_POST['auto_batareya']."',auto_max_speed='".$_POST['auto_max_speed']."',auto_proizvoditel='".$_POST['auto_proizvoditel']."',auto_massa='".$_POST['auto_massa']."',auto_gabarity='".$_POST['auto_gabarity']."',auto_segment='".$_POST['auto_segment']."',auto_bagaznik='".$_POST['auto_bagaznik']."',auto_raspolozenie_motora='".$_POST['auto_raspolozenie_motora']."',auto_razgon='".$_POST['auto_razgon']."',auto_kategory='".$_POST['auto_kategory']."',auto_picture='".$_FILES["filename"]["name"]."'";
	
	mysql_query($strSQL) or die(mysql_error());

}
    
   else 
   {
      echo("Ошибка загрузки файла");
   }
   header('Location: succes.php');
?>