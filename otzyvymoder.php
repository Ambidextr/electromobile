<?php
session_start();
?>

<html>
<head>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Electromobile</title>
<meta name="keywords" content="электромобили, электрокары" />
<meta name="description" content="сайт об электромобилях" />
<link href="default.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
 
$(window).scroll(function(){
if ($(this).scrollTop() > 100) {
$('.scrollup').fadeIn();
} else {
$('.scrollup').fadeOut();
}
});
 
$('.scrollup').click(function(){
$("html, body").animate({ scrollTop: 0 }, 600);
return false;
});
 
});
</script>
</head>
<body>

<div class="wrapper">
<div class="container">
			
<div id="logo"><img src="images/background-top.jpg" alt="logo"></div>
<div id="page">
	<div id="menu">
		<ul>
			<li class="active first"><a href="index.php">Главная</a></li>
		    <li><a href="katalog.php">Каталог</a></li>
			<li><a href="gallery.php">Галерея</a></li>	
			<li><a href="otzyvy.php">Отзывы</a></li>
			<li><a href="novosti.php">Новости</a></li>
			<li><a href="sovety.php">Статьи</a></li>			
		</ul>
	</div>
	
	<div id="sidebar">
		<div id="login" class="boxed">
			<div class="title">
				<h4>Авторизация</h4>
			</div>
			<div class="content">

<?
require_once("sessions.php");
?>             
			</div>
		</div>

<?php
		if($_SESSION["SS_user_id"]==1)
		{
		echo '
		<div class="boxed">
			<div class="title">
				<h4>Admin-panel</h4>
			</div>
			<div class="content">
				<ul class="list1">	
					<li><a href="otzyvyadd.php">Добавить отзыв</a></li>
						<li><a href="otzyvydel.php">Удалить отзыв</a></li>
						<li><a href="otzyvyupd.php">Изменить отзыв</a></li>
						<li><a href="otzyvymoder.php">Премодерация</a></li>
				</ul>
			</div>
		</div>';
		}?>	
	</div>

		<div id="sidebar2">

		<div class="boxed">
			<div class="title">
				<h4>Навигация</h4>
			</div>
			<div class="content">
				<ul class="list1">
                    <li><a href="forum.php">Форум</a></li>				
					<li><a href="karta.php">Карта АЗС</a></li>
					<li><a href="poiskk.php">Поиск по каталогу</a></li>
					<li><a href="kalculator.php">Калькулятор</a></li>
					<li><a href="terminy.php">Словарь терминов</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div id="content">
	<div id="pagin">
	
		<form method="get" name="upd">
            <select name="update" style="width: 500px">
            <?php
                $query = mysql_query("SELECT id_otzyvy, otzyvy_name FROM otzyvy WHERE otzyvy_public = 0");
                while ($row = mysql_fetch_assoc($query)) 
				{
                    echo "<option value=".$row['id_otzyvy'].">".$row['otzyvy_name']."</option>";
                }
            ?>
             </select>
            <input type="submit" name="updat" value="Просмотреть">
        </form>
		
<?
require_once("conect_bd.php");
$q="SELECT * FROM otzyvy WHERE id_otzyvy = " .$_GET['update'];
$result=mysql_query($q) or die("");
if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	if ($row = mysql_fetch_assoc($result))
		{
		$sql_text_otzyvy=$row["otzyvy_text"];	
		$sql_picture_otzyvy=$row["otzyvy_picture"];
		$sql_name_otzyvy=$row["otzyvy_name"];
		$sql_autor_otzyvy=$row["otzyvy_autor"];
		$sql_data_otzyvy=$row["otzyvy_data"];
        $sql_id_otzyvy=$row["id_otzyvy"];
		$sql_auto_kategory=$row["auto_kategory"];
		$sql_ocenka_otzyvy=$row["otzyvy_ocenka"];
		$sql_user_id=$row["user_id"];
?>
<form name="form1" method="post" enctype="multipart/form-data">
<label>
Дата создания:</br>
<input type="date" name="date" value=<?php echo ''.$sql_data_otzyvy.'';?> required>
</br>
Изображение:</br>
<input type="file" name="filename"><br>
Автор: <? echo ''.$sql_autor_otzyvy.' (id пользователя: '.$sql_user_id.')';?>
</br>
Оценка:</br>
	   <select name="ocenka" style="width: 150px">
	   <option value="<?php echo ''.$sql_ocenka_otzyvy.'';?>"><?php echo ''.$sql_ocenka_otzyvy.'';?></option>
          <option value="5">5</option>
		  <option value="4">4</option>
		  <option value="3">3</option>
		  <option value="2">2</option>
		  <option value="1">1</option>
      </select></br>
Электромобиль:
</br>
<select name="auto" style="width: 500px">';
<option value="<?php echo ''.$sql_auto_kategory.'';?>"><?php echo ''.$sql_auto_kategory.'';?></option>
<option value="Без категории">Без категории</option>
           <?
                $query = mysql_query("SELECT auto_kategory, auto_name FROM catalogue");
                while ($row = mysql_fetch_assoc($query)) 
				{
                    echo "<option value=".$row['auto_kategory'].">".$row['auto_name']."</option>";
                }
				?>
          </select></br>
Название:</br>
<textarea name="name_news" style="width: 500px" maxlength="150" required>
<?php echo ''.$sql_name_otzyvy.'';?>
</textarea></br>
Текст:</br>
<textarea name="text" style="height: 300px; width: 500px" maxlength="15000" required>
<?php echo ''.$sql_text_otzyvy.'';?>
</textarea></br>
Разрешить публикацию?</br>
<select name="public" style="width: 500px">';
<option value="1">Да</option>
<option value="0">Нет</option>
          </select></br>
</label>
<input name="id" type="hidden" id="id" value=<?php echo ''.$sql_id_otzyvy.'';?>>
<input name="edit" type="hidden" id="edit" />
<p>
<label>
<input type="submit" name="submit2" value="Опубликовать" />
</label>
</p>
</form>
<?php
}}
//Редактируем данные:
if (isset($_POST['submit2'])) 
{
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
}
if($_FILES["filename"]["size"] > 0)
{
$upd_query = mysql_query("UPDATE otzyvy SET otzyvy_name='".$_POST['name_news']."', otzyvy_text='".$_POST['text']."', otzyvy_picture='".$_FILES["filename"]["name"]."', otzyvy_data='".$_POST['date']."', auto_kategory='".$_POST['auto']."', otzyvy_public='".$_POST['public']."', otzyvy_ocenka='".$_POST['ocenka']."' WHERE id_otzyvy='".$_POST['id']."'") or die(mysql_error());
        if ($upd_query) 
		{
			echo "Обновление прошло успешно";
        }
		else 
		{
			echo "Произошла ошибка, вернитесь и повторите снова";
        }
}
else
{
$upd_query = mysql_query("UPDATE otzyvy SET otzyvy_name='".$_POST['name_news']."', otzyvy_text='".$_POST['text']."', otzyvy_data='".$_POST['date']."', auto_kategory='".$_POST['auto']."', otzyvy_public='".$_POST['public']."', otzyvy_ocenka='".$_POST['ocenka']."' WHERE id_otzyvy='".$_POST['id']."'") or die(mysql_error());
        if ($upd_query) 
		{
			echo "Обновление прошло успешно";
        }
		else 
		{
			echo "Произошла ошибка, вернитесь и повторите снова";
        }
}
}
?>
</div>
	</div>
	</div>
	<!-- end #sidebar -->
	<div id="extra" style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->
<div id="footer">
	<p id="legal">Copyright © Гончарова Е.К. - Автомобильный информационный web-ресурс. Копирование материалов допускается только с разрешения администрации.</p>	
</div>
</div>
</div>

<a href="#" class="scrollup"></a>

<div class="share42init" data-top1="240" data-top2="240" data-margin="0"></div>
<script type="text/javascript" src="share42/share42.js"></script>

</body>
</html>