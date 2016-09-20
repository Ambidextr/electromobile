<?php	
//---------------------------------------------------------------------------------
//--------------------Подключение БД ----------------------------------------------	
require_once("conect_bd.php");

//---------------------------------------------------------------------------------
//--------------------Вывод информации о новости----------------------------------	
if(!empty($_GET['nnews']))
{
	$query = "SELECT * FROM news WHERE id_news = ".$_GET['nnews'];
	$result = mysql_query($query)or die("Запрос не выполнен");
	if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	if ($row = mysql_fetch_assoc($result))
		{
		$sql_text_news=$row["news_text"];	
		$sql_picture_news=$row["news_picture"];
		$sql_name_news=$row["news_name"];
		$sql_auto_kategory=$row["auto_kategory"];
		$sql_data_news=$row["news_data"];	
		
echo '<div id="welcome" class="post">
			<div class="title">
				<h2>'.$sql_name_news.'</h2></a></li>
			</div>
			<h3 class="date"><span>'.$sql_data_news.'</span></h3>
			<div class="meta">
				<p><a href="katalog.php?aauto='.$sql_auto_kategory.'">'.$sql_auto_kategory.'</a></p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_news.'" alt="" width="312" height="212" class="left" />
				<p>'.$sql_text_news.'</p>
			</div>
		</div>
		<div id="pagin">
		<div id="vk_comments"></div>
		</div>';
		}
	}
}
//---------------------------------------------------------------------------------
//--------------------Вывод Списка новостей----------------------------------------	
else
{
	// количество записей, выводимых на странице
$per_page=3;
// получаем номер страницы
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
// вычисляем первый оператор для LIMIT
$start=abs($page*$per_page);
// составляем запрос и выводим записи
// переменную $start используем, как нумератор записей.
	$q = "SELECT * FROM news ORDER BY id_news DESC LIMIT $start,$per_page";
	$res = mysql_query($q)or die("Запрос не выполнен");
	if (mysql_num_rows($res)>0) //Проверка кол-ва записей
		{
			while ($row = mysql_fetch_assoc($res))
			{
		$sql_text_news=$row["news_text"];	
		$sql_picture_news=$row["news_picture"];
		$sql_name_news=$row["news_name"];
		$sql_data_news=$row["news_data"];
        $sql_id_news=$row["id_news"];
		$sql_auto_kategory=$row["auto_kategory"];
			//	echo '<li><a href="?nnews='.$sql_id_news.'">'.$sql_name_news.'><p>'.$sql_text_news.'</p></a>';

if (strlen($sql_text_news) > 570){
$cоntent = mb_substr($sql_text_news,0,570,'utf-8');
//echo "\n\n$sql_name_news{$cоntent} ... Читать далее";
//echo '<li><a href="?nnews='.$sql_id_news.'">'.$sql_name_news.'</a><p>'.$cоntent.'... <a href="?nnews='.$sql_id_news.'"> Читать далее</p></a>';
echo '<div id="welcome" class="post">
			<div class="title">
				<h2><a href="?nnews='.$sql_id_news.'">'.$sql_name_news.'</a></h2>
			</div>
			<h3 class="date"><span>'.$sql_data_news.'</span></h3>
			<div class="meta">
				<p><a href="katalog.php?aauto='.$sql_auto_kategory.'">'.$sql_auto_kategory.'</a></p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_news.'" alt="" width="312" height="212" class="left" />
				<p>'.$cоntent.'... <a href="?nnews='.$sql_id_news.'"> Читать далее</a></p>
			</div>
		</div>';
}
else
{
echo '<div id="welcome" class="post">
			<div class="title">
				<h2><a href="?nnews='.$sql_id_news.'">'.$sql_name_news.'</a></h2>
			</div>
			<h3 class="date"><span>'.$sql_data_news.'</span></h3>
			<div class="meta">
				<p>'.$sql_aftar_news.'</p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_news.'" alt="" width="312" height="212" class="left" />
				<p>'.$sql_text_news.'</p>
			</div>
		</div>';
} 
			}
		}	
	//}
	
	
	
	//---------------------------------------------------------------------------------
//--------------------Вывод информации о новости----------------------------------	
if(!empty($_GET['ppars']))
{
	$query = "SELECT * FROM parser WHERE id_pars = ".$_GET['ppars'];
	$result = mysql_query($query)or die("Запрос не выполнен");
	if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	if ($row = mysql_fetch_assoc($result))
		{
		$sql_text_news=$row["news_desc"];	
		$sql_picture_news=$row["pars_picture"];
		$sql_name_news=$row["pars_name"];
		$sql_aftar_news=$row["pars_autor"];
		$sql_data_news=$row["pars_date"];
		$sql_auto_kategory=$row["auto_kategory"];
			
echo '<div id="welcome" class="post">
			<div class="title">
				<h2>'.$sql_name_news.'</h2></a></li>
			</div>
			<h3 class="date"><span>'.$sql_data_news.'</span></h3>
			<div class="meta">
				<p>'.$sql_auto_kategory.'</p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_news.'" alt="" width="312" height="212" class="left" />
				<p>'.$sql_text_news.'</p>
			</div>
		</div>
		<div id="pagin">
		<div id="vk_comments"></div>
		</div>';
		}
	}
}
//---------------------------------------------------------------------------------
//--------------------Вывод Списка новостей из парсера-----------------------------	
else
{	
	// количество записей, выводимых на странице
$per_page=3;
// получаем номер страницы
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
// вычисляем первый оператор для LIMIT
$start=abs($page*$per_page);
// составляем запрос и выводим записи
// переменную $start используем, как нумератор записей.
	$q = "SELECT * FROM parser ORDER BY id_pars DESC LIMIT $start,$per_page";
	$res = mysql_query($q)or die("Запрос не выполнен");
	if (mysql_num_rows($res)>0) //Проверка кол-ва записей
		{
			while ($row = mysql_fetch_assoc($res))
			{
		$sql_text_news=$row["pars_desc"];	
		$sql_picture_news=$row["pars_picture"];
		$sql_name_news=$row["pars_name"];
	//	$sql_aftar_news=$row["pars_autor"];
		$sql_data_news=$row["pars_date"];
		$sql_pars_link=$row["pars_link"];
		$sql_auto_kategory=$row["auto_kategory"];

if (strlen($sql_text_news) > 570){
$cоntent = substr($sql_text_news,0,570);
echo '<div id="welcome" class="post">
			<div class="title">
				<h2><a href="'.$sql_pars_link.'">'.$sql_name_news.'</a></h2>
			</div>
			<h3 class="date"><span>'.$sql_data_news.'</span></h3>
			<div class="meta">
				<p>'.$sql_auto_kategory.'</p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_news.'" alt="" width="312" height="212" class="left" />
				<p>'.$cоntent.'... <noindex><a href="'.$sql_pars_link.'"> Читать далее</a></noindex></p>
			</div>
		</div>';
}
else
{
echo '<div id="welcome" class="post">
			<div class="title">
				<h2><a href="'.$sql_pars_link.'">'.$sql_name_news.'</a></h2>
			</div>
			<h3 class="date"><span>'.$sql_data_news.'</span></h3>
			<div class="meta">
				<p>'.$sql_auto_kategory.'</p>
			</div>
			<div class="story"> <img src="/images/'.$sql_picture_news.'" alt="" width="312" height="212" class="left" />
				<p>'.$sql_text_news.'</p>
			</div>
		</div>';
} 
			}
		}	
	}
	
	
}	
?>
