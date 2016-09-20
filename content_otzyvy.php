<?php	
//--------------------Подключение БД ----------------------------------------------	
require_once("conect_bd.php");

//---------------------------------------------------------------------------------
//--------------------Вывод информации о статье----------------------------------	
if(!empty($_GET['ootzyvy']))
{
	$query = "SELECT * FROM otzyvy WHERE id_otzyvy = ".$_GET['ootzyvy'];
	$result = mysql_query($query)or die("Запрос не выполнен");
	if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	if ($row = mysql_fetch_assoc($result))
		{
		$sql_text_otzyvy=$row["otzyvy_text"];	
		$sql_picture_otzyvy=$row["otzyvy_picture"];
		$sql_name_otzyvy=$row["otzyvy_name"];
		$sql_autor_otzyvy=$row["otzyvy_autor"];
		$sql_data_otzyvy=$row["otzyvy_data"];	
		$sql_ocenka_otzyvy=$row["otzyvy_ocenka"];
		$sql_auto_kategory=$row["auto_kategory"];	
     
			
echo '<div id="welcome" class="post">
			<div class="title">
				<h2>'.$sql_name_otzyvy.'</h2></a></li>
			</div>
			<h3 class="date"><span>'.$sql_data_otzyvy.'</span></h3>
			<div class="meta4">
				<p><a href="katalog.php?aauto='.$sql_auto_kategory.'">'.$sql_auto_kategory.'</a></p>
			</div>
			<div class="meta2">
				<p>Оценка: '.$sql_ocenka_otzyvy.'</p>
			</div>
			<div class="meta3">
				<p>Автор: '.$sql_autor_otzyvy.'</p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_otzyvy.'" alt="" width="312" height="212" class="left" />
				<p>'.$sql_text_otzyvy.'</p>
			</div>
		</div>
				<div id="pagin">
		<div id="vk_comments"></div>
		</div>';
		}
	}
}
//---------------------------------------------------------------------------------
//--------------------Вывод Списка статей----------------------------------------	
else
{	
	// количество записей, выводимых на странице
$per_page=5;
// получаем номер страницы
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
// вычисляем первый оператор для LIMIT
$start=abs($page*$per_page);
// составляем запрос и выводим записи
// переменную $start используем, как нумератор записей.
	$query = "SELECT * FROM otzyvy WHERE otzyvy_public = 1 ORDER BY id_otzyvy DESC LIMIT $start,$per_page";
	$result = mysql_query($query)or die("Запрос не выполнен");
	if (mysql_num_rows($result)>0) //Проверка кол-ва записей
		{
			while ($row = mysql_fetch_assoc($result))
			{
		$sql_text_otzyvy=$row["otzyvy_text"];	
		$sql_picture_otzyvy=$row["otzyvy_picture"];
		$sql_name_otzyvy=$row["otzyvy_name"];
		$sql_autor_otzyvy=$row["otzyvy_autor"];
		$sql_data_otzyvy=$row["otzyvy_data"];
        $sql_id_otzyvy=$row["id_otzyvy"];
		$sql_ocenka_otzyvy=$row["otzyvy_ocenka"];
		$sql_auto_kategory=$row["auto_kategory"];

if (strlen($sql_text_otzyvy) > 570){
$cоntent = mb_substr($sql_text_otzyvy,0,570,'utf-8');
echo '<div id="welcome" class="post">
			<div class="title">
				<h2><a href="?ootzyvy='.$sql_id_otzyvy.'">'.$sql_name_otzyvy.'</a></h2>
			</div>
			<h3 class="date"><span>'.$sql_data_otzyvy.'</span></h3>
			<div class="meta4">
				<p><a href="katalog.php?aauto='.$sql_auto_kategory.'">'.$sql_auto_kategory.'</a></p>
			</div>
			<div class="meta2">
				<p>Оценка: '.$sql_ocenka_otzyvy.'</p>
			</div>
			<div class="meta3">
				<p>Автор: '.$sql_autor_otzyvy.'</p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_otzyvy.'" alt="" width="312" height="212" class="left" />
				<p>'.$cоntent.'... <a href="?ootzyvy='.$sql_id_otzyvy.'"> Читать далее</a></p>
			</div>
		</div>';
}else{
echo '<div id="welcome" class="post">
			<div class="title">
				<h2><a href="?rotzyvy='.$sql_id_otzyvy.'">'.$sql_name_otzyvy.'</a></h2>
			</div>
			<h3 class="date"><span>'.$sql_data_otzyvy.'</span></h3>
			<div class="meta4">
				<p>'.$sql_autor_otzyvy.'</p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_otzyvy.'" alt="" width="312" height="212" class="left" />
				<p>'.$sql_text_otzyvy.'... <a href="?rotzyvy='.$sql_id_otzyvy.'"> Читать далее</a></p>
			</div>
		</div>';
} 
			}
		}	
	}

//---------------------------------------------------------------------------------	
?>
