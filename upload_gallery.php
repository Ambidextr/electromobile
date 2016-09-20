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
//Добавление в базу данных новой информации НОВОСТЕЙ
//------------------------------------------------------------------------------------	
	$strSQL = "INSERT INTO gallery(photo_auto,photo_path,auto_kategory,photo_main) VALUES('".$_POST['name']."','".$_FILES["filename"]["name"]."','".$_POST['kategory']."','".$_POST['main']."')"; 
	mysql_query($strSQL) or die(mysql_error());
}
    
   else 
   {
      echo("Ошибка загрузки файла");
   }
   header('Location: succes.php');
?>