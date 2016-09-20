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
						echo '<div class="boxed">
			<div class="title">
				<h4>Admin-panel</h4>
			</div>
			<div class="content">
				<ul class="list1">	
					
						<li><a href="novostiadd.php">Добавить новость</a></li>
						<li><a href="novostidel.php">Удалить новость</a></li>
						<li><a href="novostiupd.php">Изменить новость</a></li>
						
				</ul>
			</div>
		</div>';
					}
?>
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
                $query = mysql_query("SELECT id_news, news_name FROM news");
                while ($row = mysql_fetch_assoc($query)) 
				{
                    echo "<option value=".$row['id_news'].">".$row['news_name']."</option>";
                }
            ?>
             </select>
            <input type="submit" name="updat" value="Редактировать">
        </form>
		
<?
require_once("conect_bd.php");
$q="SELECT * FROM news WHERE id_news = " .$_GET['update'];
$result=mysql_query($q) or die("");
if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	if ($row = mysql_fetch_assoc($result))
		{
		$sql_text_news=$row["news_text"];	
		$sql_picture_news=$row["news_picture"];
		$sql_name_news=$row["news_name"];
		$sql_aftar_news=$row["news_aftar"];
		$sql_data_news=$row["news_data"];	
		$sql_id_news=$row["id_news"];
		$sql_auto_kategory=$row["auto_kategory"];
?>
<form name="form1" method="post" enctype="multipart/form-data">
<label>
Дата создания:</br>
<input type="date" name="date" value=<?php echo ''.$sql_data_news.'';?> required>
</br>
Изображение:</br>
<input type="file" name="filename"><br>
Электромобиль:</br>
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
<?php echo ''.$sql_name_news.'';?>
</textarea></br>
Текст:</br>
<textarea name="text" style="height: 300px; width: 500px" maxlength="15000" required>
<?php echo ''.$sql_text_news.'';?>
</textarea></br>
</label>
<input name="id" type="hidden" id="id" value=<?php echo ''.$sql_id_news.'';?>>
<input name="edit" type="hidden" id="edit" />
<p>
<label>
<input type="submit" name="submit2" value="Сохранить" />
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
$upd_query = mysql_query("UPDATE news SET news_name='".$_POST['name_news']."', news_text='".$_POST['text']."', news_picture='".$_FILES["filename"]["name"]."', news_data='".$_POST['date']."', auto_kategory='".$_POST['auto']."' WHERE id_news='".$_POST['id']."'") or die(mysql_error());
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
$upd_query = mysql_query("UPDATE news SET news_name='".$_POST['name_news']."', news_text='".$_POST['text']."', news_data='".$_POST['date']."', auto_kategory='".$_POST['auto']."' WHERE id_news='".$_POST['id']."'") or die(mysql_error());
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