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
                        <li><a href="katalogadd.php">Добавить авто</a></li>
						<li><a href="katalogdel.php">Удалить авто</a></li>
						<li><a href="katalogupd.php">Изменить авто</a></li>
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
            <select name="update" style="width: 565px">
            <?php
                $query = mysql_query("SELECT id_auto, auto_name FROM catalogue");
                while ($row = mysql_fetch_assoc($query)) 
				{
                    echo "<option value=".$row['id_auto'].">".$row['auto_name']."</option>";
                }
            ?>
             </select>
            <center><input type="submit" name="updat" value="Редактировать"></center></br>
        </form>
		
<?
require_once("conect_bd.php");
$q="SELECT * FROM catalogue WHERE id_auto = " .$_GET['update'];
$result=mysql_query($q) or die("");
if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	if ($row = mysql_fetch_assoc($result))
		{
	    $sql_id_auto=$row["id_auto"];		
?>
<form name="form1" method="post" enctype="multipart/form-data">
<label>
  <?php
			require_once("conect_bd.php");
               //--------------------Панель загрузки нового автомобиля ------------------------------
	if($_SESSION["SS_user_id"]==1)
	{
	 echo '
	  	<table width="580px" id="poisk_tab">
	<caption>Форма для загрузки</caption>
    <thead>
    <tr>
          <th></th>
          <th></th>
	</tr>
  </thead>
  <tbody>
	<tr>
	<td>
   Название:</br>
   <input type="text" name="auto_name" value="'.$row['auto_name'].'" style="width: 250px" maxlength="50" required></br>
   Кампания:</br>
   <input type="text" name="auto_marka" value="'.$row['auto_marka'].'" style="width: 250px" maxlength="50" required></br>
   Год выпуска:</br>
   <input type="text" name="auto_year" value="'.$row['auto_year'].'" style="width: 250px" required></br>
   Цена, $:</br>
   <input type="text" name="auto_price" value="'.$row['auto_price'].'" style="width: 250px" maxlength="20" required></br>
   Тип кузова:</br>
   <select name="auto_kuzov" style="width: 250px" required>
          <option value="'.$row['auto_kuzov'].'">'.$row['auto_kuzov'].'</option>
		  <option value="Универсал">Универсал</option>
		  <option value="Внедорожник">Внедорожник</option>
		  <option value="Хэтчбек">Хэтчбек</option>
		  <option value="Седан">Седан</option>
		  <option value="Купе">Купе</option>
		  <option value="Фастбэк">Фастбэк</option>
		  <option value="Кабриолет">Кабриолет</option>
		  <option value="Пикап">Пикап</option>
		  <option value="Фургон">Фургон</option>
		  <option value="Лимузин">Лимузин</option>
		  <option value="Родстер">Родстер</option>
		  <option value="Минивэн">Минивэн</option>
   </select></br>
      Тип КПП:</br>
   <select name="auto_kpp" style="width: 250px" required>
          <option value="'.$row['auto_kpp'].'">'.$row['auto_kpp'].'</option>
		  <option value="Автоматическая">Автоматическая</option>
		  <option value="Механическая">Механическая</option>
		  <option value="Вариатор">Вариатор</option>
   </select></br>
      Мощность двигателя, кВт:</br>
	    <input type="text" name="auto_moshnost" value="'.$row['auto_moshnost'].'" style="width: 250px" maxlength="10" required></br>
      Количество дверей:</br>
   <select name="auto_dveri" style="width: 250px" required>
          <option value="'.$row['auto_dveri'].'">'.$row['auto_dveri'].'</option>';
               
                    for ($n = 8; $n >= 2; $n--) 
					{
                    echo "<option value=\"$n\">$n</option>\n";
                    }
              echo '
   </select></br>
      Количество мест:</br>
   <select name="auto_mesta" style="width: 250px" required>
          <option value="'.$row['auto_mesta'].'">'.$row['auto_mesta'].'</option>';
              
                    for ($n = 8; $n >= 2; $n--) 
					{
                 echo "<option value=\"$n\">$n</option>\n";
                   }
               echo '
   </select></br>
      Полный заряд, ч:</br>
	  <input type="text" name="auto_zaradka" value="'.$row['auto_zaradka'].'" style="width: 250px" maxlength="10" required></br>
      Запас хода, км:</br>
	   <input type="text" name="auto_zapas_hoda" value="'.$row['auto_zapas_hoda'].'" style="width: 250px" maxlength="10" required></br>
	   
	   </td><td>
	   
	   Категория:
</br>
<input type="text" name="auto_kategory" value="'.$row['auto_kategory'].'" style="width: 250px" maxlength="30" required></br>
	   
      Тип привода:</br>
   <select name="auto_privod" style="width: 250px" required>
          <option value="'.$row['auto_privod'].'">'.$row['auto_privod'].'</option>
		  <option value="Передний">Передний</option>
		  <option value="Задний">Задний</option>
		  <option value="Полный">Полный</option>
   </select></br>
      Аккумулятор:</br>
   <select name="auto_batareya" style="width: 250px" required>
          <option value="'.$row['auto_batareya'].'">'.$row['auto_batareya'].'</option>
          <option value="Литий-железо-фосфатный">Литий-железо-фосфатный</option>
		  <option value="Литий-ионный">Литий-ионный</option>
		  <option value="Литий-полимерный">Литий-полимерный</option>
		  <option value="Свинцово-кислотный">Свинцово-кислотный</option>
   </select></br>
      Максимальная скорость, км/ч:</br>
	  <input type="text" name="auto_max_speed" value="'.$row['auto_max_speed'].'" style="width: 250px" maxlength="10" required></br>
      Производитель:</br>
	  <input type="text" name="auto_proizvoditel" value="'.$row['auto_proizvoditel'].'" style="width: 250px" maxlength="30" required></br>
      Масса, кг:</br>
   <input type="text" name="auto_massa" value="'.$row['auto_massa'].'" style="width: 250px" maxlength="10" required></br>
      Сегмент:</br>
   <select name="auto_segment" style="width: 250px" required>
          <option value="'.$row['auto_segment'].'">'.$row['auto_segment'].'</option>
          <option value="A">A</option>
		  <option value="B">B</option>
		  <option value="C">C</option>
		  <option value="D">D</option>
		  <option value="E">E</option>
		  <option value="F">F</option>
		  <option value="S">S</option>
		  <option value="M">M</option>
		  <option value="J">J</option>
   </select></br>
      Объём багажника, л:</br>
	  <input type="text" name="auto_bagaznik" value="'.$row['auto_bagaznik'].'" style="width: 250px" maxlength="10" required></br>
      Расположение электродвигателя:</br>
 <select name="auto_raspolozenie_motora" style="width: 250px" required>
          <option value="'.$row['auto_raspolozenie_motora'].'">'.$row['auto_raspolozenie_motora'].'</option>
          <option value="Спереди">Спереди</option>
		  <option value="Сзади">Сзади</option>
   </select></br>
         Разгон до 100 км/ч, сек:</br>
		 <input type="text" name="auto_razgon" value="'.$row['auto_razgon'].'" style="width: 250px" maxlength="10" required></br>
            Габариты, мм:</br>
			<input type="text" name="auto_gabarity" value="'.$row['auto_gabarity'].'" style="width: 250px" maxlength="10" required></br>
 
   </td>
   </tr>
   </tbody>
   </table>
   <center>Выберите изображение:</br>
	   <input type="file" name="filename"></center></br> 
	  <p><textarea rows="20" cols="70" name="auto_text" maxlength="15000" placeholder="Введите текст обзора" required>'.$row['auto_text'].'</textarea></p></br>	  
  ';
	}
            ?>

</label>
<input name="id" type="hidden" id="id" value=<?php echo ''.$sql_id_auto.'';?>>
<input name="edit" type="hidden" id="edit" />
<p>
<label>
<center><input type="submit" name="submit2" value="Сохранить" /></center>
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
$upd_query = mysql_query("UPDATE catalogue SET auto_name='".$_POST['auto_name']."',auto_marka='".$_POST['auto_marka']."',auto_year='".$_POST['auto_year']."',auto_kuzov='".$_POST['auto_kuzov']."',auto_price='".$_POST['auto_price']."',auto_text='".$_POST['auto_text']."',auto_kpp='".$_POST['auto_kpp']."',auto_dveri='".$_POST['auto_dveri']."',auto_mesta='".$_POST['auto_mesta']."',auto_moshnost='".$_POST['auto_moshnost']."',auto_zaradka='".$_POST['auto_zaradka']."',auto_zapas_hoda='".$_POST['auto_zapas_hoda']."',auto_privod='".$_POST['auto_privod']."',auto_batareya='".$_POST['auto_batareya']."',auto_max_speed='".$_POST['auto_max_speed']."',auto_proizvoditel='".$_POST['auto_proizvoditel']."',auto_massa='".$_POST['auto_massa']."',auto_gabarity='".$_POST['auto_gabarity']."',auto_segment='".$_POST['auto_segment']."',auto_bagaznik='".$_POST['auto_bagaznik']."',auto_raspolozenie_motora='".$_POST['auto_raspolozenie_motora']."',auto_razgon='".$_POST['auto_razgon']."',auto_kategory='".$_POST['auto_kategory']."',auto_picture='".$_FILES["filename"]["name"]."' WHERE id_auto='".$_POST['id']."'") or die(mysql_error());
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
	$upd_query1 = mysql_query("UPDATE catalogue SET auto_name='".$_POST['auto_name']."',auto_marka='".$_POST['auto_marka']."',auto_year='".$_POST['auto_year']."',auto_kuzov='".$_POST['auto_kuzov']."',auto_price='".$_POST['auto_price']."',auto_text='".$_POST['auto_text']."',auto_kpp='".$_POST['auto_kpp']."',auto_dveri='".$_POST['auto_dveri']."',auto_mesta='".$_POST['auto_mesta']."',auto_moshnost='".$_POST['auto_moshnost']."',auto_zaradka='".$_POST['auto_zaradka']."',auto_zapas_hoda='".$_POST['auto_zapas_hoda']."',auto_privod='".$_POST['auto_privod']."',auto_batareya='".$_POST['auto_batareya']."',auto_max_speed='".$_POST['auto_max_speed']."',auto_proizvoditel='".$_POST['auto_proizvoditel']."',auto_massa='".$_POST['auto_massa']."',auto_gabarity='".$_POST['auto_gabarity']."',auto_segment='".$_POST['auto_segment']."',auto_bagaznik='".$_POST['auto_bagaznik']."',auto_raspolozenie_motora='".$_POST['auto_raspolozenie_motora']."',auto_razgon='".$_POST['auto_razgon']."',auto_kategory='".$_POST['auto_kategory']."' WHERE id_auto='".$_POST['id']."'") or die(mysql_error());
        if ($upd_query1) 
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