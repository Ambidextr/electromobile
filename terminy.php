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

		<div class="boxed">
			<div class="title">
				<h4>Реклама</h4>
			</div>
			<div class="content">
<a href=#><img src="/images/renault-zoe-1.jpg" width="140" height="100"></a>				
			</div>
		</div>
		
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
	<caption>Словарь терминов</caption>
<tbody>

    <tr>
    <th>Запас хода</th>
	</tr>

	<tr>
    <td>
    Один из показателей подвижности машины, характеризующийся расстоянием, которое может пройти машина на одной заправке. Запас хода машин определяется в километрах.
	</td>
	</tr>	
	
	
	<tr>
    <th>Производитель</th>
	</tr>
	
    <tr>
    <td>
	Страна, в которой расположены производственные мощности предприятия.
    </td>
	</tr>	
	
	
	<tr>
    <th>Год выпуска</th>
	</tr>
	
    <tr>
    <td>
	Дата запуска электромобиля в серийное производство.
    </td>
	</tr>	
	
    <tr>
    <th>Сегмент</th>
	</tr>
	
    <tr>
    <td>
	Классификация Европейской экономической комиссии, которая ориентирована на сегментацию целевого рынка. Рамки между сегментами размыты и не ограничиваются такими параметрами, как габариты или масса. Факторы сегментации включают также такие параметры как цену, вид, набор опций и иные параметры.
	</br></br>
	Рынок пассажирских автомобилей делится на следующие сегменты:</br>
A: 	Mini cars (микроавтомобили)</br>
B: 	Small cars (малые автомобили)</br>
C: 	Medium cars (европейский «средний класс»)</br>
D: 	Larger cars (большие семейные автомобили)</br>
E: 	Executive cars («бизнес-класс»)</br>
F: 	Luxury cars (представительские автомобили)</br>
S: 	Sport coupés (спорткупе)</br>
M: 	Multi purpose cars (минивэны и УПВ)</br>
J: 	Sports utility (SUV, «паркетники»)</br>
    </td>
	</tr>	
	
	<tr>
    <th>Время разгона</th>
	</tr>
	
    <tr>
    <td>
	Время,за которое электромобиль способен развить скорость 100 км/ч.
    </td>
	</tr>	
	
	<tr>
    <th>Полный заряд</th>
	</tr>
	
    <tr>
    <td>
	Количество времени, которое требуется для полной зарядки электромобиля.
    </td>
	</tr>
	
	
	<tr>
    <th>Аккумулятор</th>
	</tr>
	
    <tr>
    <td>
	Источник тока многоразового действия, основная специфика которого заключается в обратимости внутренних химических процессов, что обеспечивает его многократное циклическое использование (через заряд-разряд) для накопления энергии и автономного электропитания автомобиля.
    </td>
	</tr>
	
	
	<tr>
    <th>Мощность двигателя</th>
	</tr>
	
    <tr>
    <td>
	Показатель вырабатываемой двигателем работы (энергии) в единиицу времени. Пока двигатель работает, он производит работу, то есть переводит один вид энергии в другой.
    </td>
	</tr>
	
	
	<tr>
    <th>Цена</th>
	</tr>
	
    <tr>
    <td>
	Ориентировочная стоимость электромобиля.
    </td>
	</tr>
	
	
	<tr>
    <th>Расположение двигателя</th>
	</tr>
	
    <tr>
    <td>
	•	Переднемоторная компоновка</br>
Двигатель расположен спереди. Ведущие колёса могут быть как задние, так и передние.</br></br>
•	Заднемоторная компоновка</br>
Двигатель расположен сзади, ведущие колёса задние. Двигатель и трансмиссия объединены в один компактный агрегат, однако здесь ведущие и управляемые колёса разделены, что упрощает конструкцию.</br></br>
•	Центральномоторная (среднемоторная) компоновка</br>
Двигатель расположен сзади, но перед задней осью. Это улучшает баланс нагрузки на переднюю и заднюю ось; кроме того, уменьшается момент инерции при вращении вокруг вертикальной оси, что улучшает поворачиваемость. </br></br>

    </td>
	</tr>
	
	<tr>
    <th>Привод</th>
	</tr>
	
    <tr>
    <td>
	Привод представляет собой одну из важных компонентов современного транспортного средства. При помощи этой конструкции крутящий момент передается от двигательного агрегата на передние автомобильные колеса. Различают переднеприводный, заднеприводный и полноприводный транспорт. В первом случае энергия от двиателяпередается только на передние колеса, во втором – на задние. Что касается полноприводного автомобиля, то в этом случае крутящий момент, то есть, энергия моторараспределяется на все колеса. 
    </td>
	</tr>
	
	
	<tr>
    <th>КПП</th>
	</tr>
	
    <tr>
    <td>
	Главное предназначение коробок передач – передавать крутящий момент от двигателя к колесам и изменять величину тяги в зависимости от условий движения. На современных автомобилях применяются КПП самых разнообразных конструкций – механические, роботизированные, автоматические, бесступенчатые (вариаторы).
    </br></br>
	•	Механическая коробка управляется ручным способом. </br>

•	В автоматическая коробке переключение ступеней скорости происходит в автоматическом режиме, а вот команда на начало движения или движение задним ходом требует команды водителя. </br>

•	Роботизированная коробка передач изготовлена на основе МКПП, но с автоматическим управлением. Управление «роботом» может даже подстраиваться под стиль вождения. 
</br>
•	Вариатор – кпп, в которой передач нет вообще. Передаточное отношение меняется плавно и бесступенчато в зависимости от дорожных условий. </br>


	</td>
	</tr>
	
	
	<tr>
    <th>Тип кузова</th>
	</tr>
	
    <tr>
    <td>
	Автомобили могут иметь множество вариантов кузовов:</br></br>
	Закрытые</br></br>
•	Седан: наиболее распространённый тип кузова, может быть двух- или четырёхдверным, в редких исключениях могут иметь пять дверей (с учетом багажника). Отличительная особенность — наличие двух рядов полноразмерных (то есть пригодных для достаточно комфортного размещения взрослых людей) сидений и отсутствие дверцы в задней стенке.</br>
•	Универсал: обычно двухобъёмный, пяти- или реже трёхдверный грузопассажирский кузов на основе седана с дверью в задке, задний свес как у седана или длиннее. </br>
•	Хетчбэк: обычно двухобъёмный грузопассажирский кузов, с тремя или пятью дверьми, родственен универсалу, но отличается меньшей длиной заднего свеса, соответственно, менее грузоподъёмен. </br>
•	Купе: двухдверный трёхобъёмный кузов, с одним рядом сидений, либо с задним сидением ограниченной вместимости (детским, или для краткого, неудобного размещения взрослых пассажиров); часто с выраженным спортивным обликом, но встречаются и люксовые (представительские) купе, которые обеспечивают максимум комфорта водителю и пассажиру на переднем сидении</br>
•	Лимузин: закрытый кузов легкового автомобиля высшего класса на основе седана с удлинённой колёсной базой и перегородкой за передним сидением. Следует отличать от простого длиннобазного седана без перегородки. </br>
•	Минивэн: обычно однообъёмный, либо двухобъёмный с полукапотной компоновкой, кузов, промежуточный вариант между универсалом и микроавтобусом.</br>
•	Лифтбэк: хетчбек с длинным, как у седана, задним свесом; может иметь два объёма и покатую крышу, как у большинства хетчбеков, либо три объёма.</br>
•	Фастбэк: относится к различным типам автомобильных кузовов, имеющих особую покатую форму крыши, плавно, без ступеньки, переходящей в крышку багажника. </br>
</br>Открытые</br></br>
•	Кабриолет: открытый автомобильный кузов, двух- или четырёхдверный, обычно с мягкой или жёсткой складной крышей, имеющий подъёмные боковые стёкла; в сложенном положении крыша размещается в багажнике или в пространстве между багажником и пассажирами.</br>
•	Родстер: двухместный кузов со складываемым мягким верхом.</br>
•	Фаэтон: четырёхдверный автомобильный кузов с мягкой складной крышей на пять-шесть посадочных мест без боковых подъёмных стёкол. </br>
•	Спайдер: открытый двухдверный автомобильный кузов. В отличие от родстера, верхняя кромка лобового стекла находится значительно ниже глаз водителя (точнее, пилота) или отсутствует вовсе. </br>

    </td>
	</tr>
	
   </tbody>
   </table></br>
	  
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