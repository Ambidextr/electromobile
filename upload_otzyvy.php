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
//Добавление в базу данных новой статьи
//------------------------------------------------------------------------------------	
	$strSQL = "INSERT INTO otzyvy(otzyvy_name,otzyvy_picture,otzyvy_text,otzyvy_autor,otzyvy_data,auto_kategory,otzyvy_ocenka,user_id) VALUES('".$_POST['name_otzyvy']."','".$_FILES["filename"]["name"]."','".$_POST['text_otzyvy']."','".$_POST['autor']."','".$_POST['time']."','".$_POST['auto']."','".$_POST['ocenka']."','".$_POST['autor_id']."')"; 

	mysql_query($strSQL) or die(mysql_error());

}
    
   else 
   {
      echo("Ошибка загрузки файла");
   }
   header('Location: succes.php');
?>