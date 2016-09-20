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
</script>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>

<script type="text/javascript">
  VK.init({apiId: 4908734, onlyWidgets: true});
</script>
</head>
<body>

<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 20, width: "580", attach: "*"});
</script>

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
                    <li><a href="forum/index.php">Форум</a></li>				
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
	
	<table width="580px" id="poisk_tab">
	<caption>Поиск по каталогу</caption>
    <thead>
    <tr>
          <th></th>
          <th></th>
	</tr>
  </thead>
  <tbody>
	<tr>
	<td>
	
	<form action="poiskk.php">
   Марка:</br>
   <select name="auto_marka" style="width: 250px">
          <option value="">-любая-</option>
		  <option value="Audi">Audi</option>
		  <option value="BMW">BMW</option>
		  <option value="Chevrolet">Chevrolet</option>
		  <option value="Honda">Honda</option>
		  <option value="Lada">Lada</option>
		  <option value="Life">Life</option>
		  <option value="Nissan">Nissan</option>
		  <option value="Mercedes">Mercedes</option>
		  <option value="Mitsubishi">Mitsubishi</option>
		  <option value="Renault">Renault</option>
		  <option value="Reva">Reva</option>
		  <option value="Smart">Smart</option>
		  <option value="Subaru">Subaru</option>
		  <option value="Tesla">Tesla</option>
		  <option value="Volkswagen">Volkswagen</option>
   </select></br>
 <!--  Название:</br>
   <select name="auto_name">
          <option value="">-любое-</option>
               
                $query = mysql_query("SELECT auto_name FROM catalogue");
                while ($row = mysql_fetch_assoc($query)) 
				{
                    echo "<option value=".$row['auto_name'].">".$row['auto_name']."</option>";
                }
            
   </select></br>-->
    Год выпуска:</br>
   <select name="auto_year" style="width: 250px">
          <option value="">-любой-</option>
               <?php
			    $query = mysql_query("SELECT auto_year FROM catalogue");
                while ($row = mysql_fetch_assoc($query)) 
				{
                  //  echo "<option value=".$row['auto_year'].">".$row['auto_year']."</option>";
					for ($n = 2015; $n >= 2000; $n--) 
					{
                    echo "<option value=\"$n\">$n</option>\n";
                    }
                }
            ?>
   </select></br> 
       Цена, $:</br>
   <input type="text" name="auto_price" style="width: 250px"></br>
   Тип кузова:</br>
   <select name="auto_kuzov" style="width: 250px">
          <option value="">-любой-</option>
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
   <select name="auto_kpp" style="width: 250px">
          <option value="">-любая-</option>
		  <option value="Автоматическая">Автоматическая</option>
		  <option value="Механическая">Механическая</option>
		  <option value="Вариатор">Вариатор</option>
   </select></br>
      Мощность двигателя, кВт:</br>
	    <input type="text" name="auto_moshnost" style="width: 250px"></br>
      Количество дверей:</br>
   <select name="auto_dveri" style="width: 250px">
          <option value="">-любое-</option>
               <?php
                    for ($n = 8; $n >= 2; $n--) 
					{
                    echo "<option value=\"$n\">$n</option>\n";
                    }
               ?>
   </select></br>
      Количество мест:</br>
   <select name="auto_mesta" style="width: 250px">
          <option value="">-любое-</option>
               <?php
                    for ($n = 8; $n >= 2; $n--) 
					{
                    echo "<option value=\"$n\">$n</option>\n";
                    }
               ?>
   </select></br>
      Полный заряд, ч:</br>
	  <input type="text" name="auto_zaradka" style="width: 250px"></br>
      Запас хода, км:</br>
	   <input type="text" name="auto_zapas_hoda" style="width: 250px"></br>
	   
	   </td><td>
	   
      Тип привода:</br>
   <select name="auto_privod" style="width: 250px">
          <option value="">-любой-</option>
		  <option value="Передний">Передний</option>
		  <option value="Задний">Задний</option>
		  <option value="Полный">Полный</option>
   </select></br>
      Аккумулятор:</br>
   <select name="auto_batareya" style="width: 250px">
          <option value="">-любой-</option>
          <option value="Литий-железо-фосфатный">Литий-железо-фосфатный</option>
		  <option value="Литий-ионный">Литий-ионный</option>
		  <option value="Литий-полимерный">Литий-полимерный</option>
		  <option value="Литий-полимерный">Свинцово-кислотный</option>
   </select></br>
      Максимальная скорость, км/ч:</br>
	  <input type="text" name="auto_max_speed" style="width: 250px"></br>
      Производитель:</br>
   <select name="auto_proizvoditel" style="width: 250px">
          <option value="">-любой-</option>
          <option value="Россия">Россия</option>
		  <option value="Корея">Корея</option>
		  <option value="США">США</option>
		  <option value="Германия">Германия</option>
		  <option value="Франция">Франция</option>
		  <option value="Япония">Япония</option>
		  <option value="Китай">Китай</option>
		  <option value="Турция">Турция</option>
		  <option value="Индия">Индия</option>
		  <option value="Великобритания">Великобритания</option>
   </select></br>
      Масса, кг:</br>
   <input type="text" name="auto_massa" style="width: 250px"></br>
      Сегмент:</br>
   <select name="auto_segment" style="width: 250px">
          <option value="">-любой-</option>
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
	  <input type="text" name="auto_bagaznik" style="width: 250px"></br>
  <!--    Тип электродвигателя:</br>
   <select name="auto_type_dvigatel">
          <option value="">-любой-</option>
               
			   /* $query = mysql_query("SELECT auto_type_dvigatel FROM catalogue");
                while ($row = mysql_fetch_assoc($query)) 
				{
                    echo "<option value=".$row['auto_type_dvigatel'].">".$row['auto_type_dvigatel']."</option>";
                }*/
           
   </select></br>-->
      Расположение электродвигателя:</br>
   <select name="auto_raspolozenie_motora" style="width: 250px">
          <option value="">-любое-</option>
          <option value="Спереди">Спереди</option>
		  <option value="Сзади">Сзади</option>
   </select></br>
         Разгон до 100 км/ч, сек:</br>
		 <input type="text" name="auto_razgon" style="width: 250px"></br>
            Габариты, мм:</br>
   <select name="auto_gabarity" style="width: 250px">
          <option value="">-любые-</option>
               <?php
			    $query = mysql_query("SELECT auto_gabarity FROM catalogue");
                while ($row = mysql_fetch_assoc($query)) 
				{
                    echo "<option value=".$row['auto_gabarity'].">".$row['auto_gabarity']."</option>";
                }
            ?>
   </select></br>
   
   </td>
   </tr>
   </tbody>
   </table>
   
 <center><input name='submit_s' type='submit' value='Искать'></center></br>
 </form>

<?php
require_once("conect_bd.php");
//$sql = "select * from catalogue";
$where = array();
if (!empty($_GET["auto_marka"])) {
    $where[] = "auto_marka='".$_GET["auto_marka"]."'"; 
}
if (!empty($_GET["auto_name"])) {
    $where[] = "auto_name='".$_GET["auto_name"]."'"; 
}
if (!empty($_GET["auto_year"])) {
    $where[] = "auto_year='".$_GET["auto_year"]."'";
}
if (!empty($_GET["auto_price"])) {
    $where[] = "auto_price='".$_GET["auto_price"]."'";
}
if (!empty($_GET["auto_kuzov"])) {
    $where[] = "auto_kuzov='".$_GET["auto_kuzov"]."'";
}
if (!empty($_GET["auto_kpp"])) {
    $where[] = "auto_kpp='".$_GET["auto_kpp"]."'";
}
if (!empty($_GET["auto_moshnost"])) {
    $where[] = "auto_moshnost='".$_GET["auto_moshnost"]."'";
}
if (!empty($_GET["auto_dveri"])) {
    $where[] = "auto_dveri='".$_GET["auto_dveri"]."'";
}
if (!empty($_GET["auto_mesta"])) {
    $where[] = "auto_mesta='".$_GET["auto_mesta"]."'";
}
if (!empty($_GET["auto_zaradka"])) {
    $where[] = "auto_zaradka='".$_GET["auto_zaradka"]."'";
}
if (!empty($_GET["auto_zapas_hoda"])) {
    $where[] = "auto_zapas_hoda='".$_GET["auto_zapas_hoda"]."'";
}
if (!empty($_GET["auto_privod"])) {
    $where[] = "auto_privod='".$_GET["auto_privod"]."'";
}
if (!empty($_GET["auto_batareya"])) {
    $where[] = "auto_batareya='".$_GET["auto_batareya"]."'";
}
if (!empty($_GET["auto_max_speed"])) {
    $where[] = "auto_max_speed='".$_GET["auto_max_speed"]."'";
}
if (!empty($_GET["auto_proizvoditel"])) {
    $where[] = "auto_proizvoditel='".$_GET["auto_proizvoditel"]."'";
}
if (!empty($_GET["auto_massa"])) {
    $where[] = "auto_massa='".$_GET["auto_massa"]."'";
}
if (!empty($_GET["auto_segment"])) {
    $where[] = "auto_segment='".$_GET["auto_segment"]."'";
}
if (!empty($_GET["auto_bagaznik"])) {
    $where[] = "auto_bagaznik='".$_GET["auto_bagaznik"]."'";
}
if (!empty($_GET["auto_type_dvigatel"])) {
    $where[] = "auto_type_dvigatel='".$_GET["auto_type_dvigatel"]."'";
}
if (!empty($_GET["auto_raspolozenie_motora"])) {
    $where[] = "auto_raspolozenie_motora='".$_GET["auto_raspolozenie_motora"]."'";
}
if (!empty($_GET["auto_razgon"])) {
    $where[] = "auto_razgon='".$_GET["auto_razgon"]."'";
}
if (!empty($_GET["auto_gabarity"])) {
    $where[] = "auto_gabarity='".$_GET["auto_gabarity"]."'";
}


if (isset($_GET['submit_s'])) 
{

if (empty($where)) echo '<center>Задан пустой поисковый запрос</center>';

//if (count($where)) 
	else
{
	$sql = "SELECT * FROM catalogue WHERE " . implode(" and ", $where);
    $result = mysql_query($sql)or die(mysql_error());
	echo '<table width="580px" id="poisk_tab2">
	<caption>Результаты поиска</caption>
    <thead>
    <tr>
          <th></th>
	</tr>
  </thead>
  <tbody>
	';
	
	$num_rows = mysql_num_rows($result);
if ($num_rows == 0) {
    echo '<tr><td>Результатов нет</td></tr></tbody></table>';
}
	else{
	
	while ($row = mysql_fetch_array($result))
		{
$sql_auto_name=$row["auto_name"];
$sql_auto_kategory=$row["auto_kategory"];

echo '<tr><td><a href="katalog.php?aauto='.$sql_auto_kategory.'">'.$sql_auto_name.'</a></td></tr>';
}

echo' </tbody></table>';
/*if (empty($result))
{
	echo "Результатов нет";
}*/

/*else
{
	echo "Результатов нет";
}*/
}
} //
} //
/*$sql = "SELECT * FROM catalogue WHERE auto_marka='".$_GET['auto_marka']."'";
$result = mysql_query($sql)or die(mysql_error());

	if ($row = mysql_fetch_assoc($result))
		{
$sql_auto_marka=$row["auto_marka"];
echo '<h2><center>'.$sql_auto_marka.'</center></h2>';
}*/

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