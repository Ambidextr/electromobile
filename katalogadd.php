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
		</div>
		';
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
            <?php
			require_once("conect_bd.php");
               //--------------------Панель загрузки нового автомобиля ------------------------------
	if($_SESSION["SS_user_id"]==1)
	{
	 echo '<div id="pagin">
      <form action="upload_katalog.php" method="post" enctype="multipart/form-data">
      
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
   <input type="text" name="auto_name" style="width: 250px" maxlength="50" required></br>
   Кампания:</br>
   <input type="text" name="auto_marka" style="width: 250px" maxlength="50" required></br>
   Год выпуска:</br>
   <select name="auto_year" style="width: 250px" required>
          <option value="">-не выбрано-</option>';
               
			    $query = mysql_query("SELECT auto_year FROM catalogue");
                while ($row = mysql_fetch_assoc($query)) 
				{
					for ($n = 2015; $n >= 2000; $n--) 
					{
                    echo "<option value=\"$n\">$n</option>\n";
                    }
                }
            
   echo '</select></br> 
   Цена, $:</br>
   <input type="text" name="auto_price" style="width: 250px" maxlength="20" required></br>
   Тип кузова:</br>
   <select name="auto_kuzov" style="width: 250px" required>
          <option value="">-не выбрано-</option>
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
          <option value="">-любая-</option>
		  <option value="Автоматическая">Автоматическая</option>
		  <option value="Механическая">Механическая</option>
		  <option value="Вариатор">Вариатор</option>
   </select></br>
      Мощность двигателя, кВт:</br>
	    <input type="text" name="auto_moshnost" style="width: 250px" maxlength="10" required></br>
      Количество дверей:</br>
   <select name="auto_dveri" style="width: 250px" required>
          <option value="">-любое-</option>';
               
                    for ($n = 8; $n >= 2; $n--) 
					{
                    echo "<option value=\"$n\">$n</option>\n";
                    }
              echo '
   </select></br>
      Количество мест:</br>
   <select name="auto_mesta" style="width: 250px" required>
          <option value="">-любое-</option>';
              
                    for ($n = 8; $n >= 2; $n--) 
					{
                 echo "<option value=\"$n\">$n</option>\n";
                   }
               echo '
   </select></br>
      Полный заряд, ч:</br>
	  <input type="text" name="auto_zaradka" style="width: 250px" maxlength="10" required></br>
      Запас хода, км:</br>
	   <input type="text" name="auto_zapas_hoda" style="width: 250px" maxlength="10" required></br>
	   
	   </td><td>
	   
	   Категория:
</br>
<input type="text" name="auto_kategory" style="width: 250px" maxlength="30" required></br>
	   
      Тип привода:</br>
   <select name="auto_privod" style="width: 250px" required>
          <option value="">-не выбрано-</option>
		  <option value="Передний">Передний</option>
		  <option value="Задний">Задний</option>
		  <option value="Полный">Полный</option>
   </select></br>
      Аккумулятор:</br>
   <select name="auto_batareya" style="width: 250px" required>
          <option value="">-не выбрано-</option>
          <option value="Литий-железо-фосфатный">Литий-железо-фосфатный</option>
		  <option value="Литий-ионный">Литий-ионный</option>
		  <option value="Литий-полимерный">Литий-полимерный</option>
		  <option value="Свинцово-кислотный">Свинцово-кислотный</option>
   </select></br>
      Максимальная скорость, км/ч:</br>
	  <input type="text" name="auto_max_speed" style="width: 250px" maxlength="10" required></br>
      Производитель:</br>
	  <input type="text" name="auto_proizvoditel" style="width: 250px" maxlength="30" required></br>
      Масса, кг:</br>
   <input type="text" name="auto_massa" style="width: 250px" maxlength="10" required></br>
      Сегмент:</br>
   <select name="auto_segment" style="width: 250px" required>
          <option value="">-не выбрано-</option>
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
	  <input type="text" name="auto_bagaznik" style="width: 250px" maxlength="10" required></br>
      Расположение электродвигателя:</br>
 <select name="auto_raspolozenie_motora" style="width: 250px" required>
          <option value="">-любое-</option>
          <option value="Спереди">Спереди</option>
		  <option value="Сзади">Сзади</option>
   </select></br>
         Разгон до 100 км/ч, сек:</br>
		 <input type="text" name="auto_razgon" style="width: 250px" maxlength="10" required></br>
         Габариты, мм:</br>
			<input type="text" name="auto_gabarity" style="width: 250px" maxlength="10" required></br>
 
   </td>
   </tr>
   </tbody>
   </table>
   <center>Выберите изображение:</br>
	   <input type="file" name="filename" required></center></br> 
	  <p><textarea rows="20" cols="70" name="auto_text" maxlength="15000" placeholder="Введите текст обзора" required></textarea></p></br>	  
      <center><input type="submit" value="Загрузить"></center><br></div>';
	}
            ?>
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