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
		
		<?php
		if($_SESSION["SS_user_id"]>1)
		{
		echo '
		<div class="boxed">
			<div class="title">
				<h4>Добавление отзывов</h4>
			</div>
			<div class="content">
				<ul class="list1">	
					<li><a href="otzyvyadd.php">Добавить отзыв</a></li>					    
				</ul>
			</div>
		</div>';
		}?>	
		<?php
		if($_SESSION["SS_user_id"]==0)
		{
		echo '
		<div class="boxed">
			<div class="title">
				<h4>Добавление отзывов</h4>
			</div>
			<div class="content">
				<ul class="list1">	
					<li>Чтобы добавить свой отзыв на сайт, необходимо зарегистрироваться</li>					    
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
<?	
require_once("content_otzyvy.php");
?>	
<div id="pagin">
<?php
ini_set('display_errors','Off');
$q="(SELECT count(*) FROM `otzyvy`)";
$res=mysql_query($q);
$row=mysql_fetch_row($res);
$total_rows=$row[0];

$num_pages=ceil($total_rows/$per_page);

for($i=1;$i<=$num_pages;$i++) {
  if ($i-1 == $page) {
    echo $i." ";
  } else {
    echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'">'.$i."</a> ";
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