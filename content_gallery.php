<?php	
//---------------------------------------------------------------------------------
//--------------------Подключение БД ----------------------------------------------	
require_once("conect_bd.php");

//---------------------------------------------------------------------------------
//--------------------Вывод информации----------------------------------	
if(!empty($_GET['pphoto']))
{
	$per_page=20;
// получаем номер страницы
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
// вычисляем первый оператор для LIMIT
$start=abs($page*$per_page);
	$query = "SELECT * FROM gallery WHERE auto_kategory = '".$_GET['pphoto']."' LIMIT $start,$per_page";
	$result = mysql_query($query)or die("Запрос не выполнен");
	if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	echo'	<div id=gallery2>';
	while ($row = mysql_fetch_assoc($result))
		{
		$sql_photo_auto=$row["photo_auto"];	
		$sql_photo_path=$row["photo_path"];
	    $sql_id_photo=$row["id_photo"];
		$sql_auto_kategory=$row["auto_kategory"];
		
//onclick="window.open("images/'.$sql_photo_path.'")"  
    echo '<li><a class="gallery" rel="group" href="/images/'.$sql_photo_path.'"><img class="zoom-images" src="/images/'.$sql_photo_path.'" width="280" height="220"></a></li>';
		}
		
	echo'</div>';
	
	echo'<div id="pagin">';
$q="(SELECT count(*) FROM `gallery` WHERE auto_kategory = '".$_GET['pphoto']."')";
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
echo'</div>';
	
	}
}
//---------------------------------------------------------------------------------
//--------------------Вывод Списка----------------------------------------	
else
{	
	// количество записей, выводимых на странице
$per_page=8;
// получаем номер страницы
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
// вычисляем первый оператор для LIMIT
$start=abs($page*$per_page);
// составляем запрос и выводим записи
// переменную $start используем, как нумератор записей.
	$q = "SELECT * FROM gallery WHERE photo_main=1 ORDER BY id_photo DESC LIMIT $start,$per_page";
	$res = mysql_query($q)or die("Запрос не выполнен");
	if (mysql_num_rows($res)>0) //Проверка кол-ва записей
		{
				echo '	<div id=gallery>';
			while ($row = mysql_fetch_assoc($res))
			{
		$sql_photo_auto=$row["photo_auto"];
		$sql_photo_path=$row["photo_path"];
		$sql_id_photo=$row["id_photo"];
		$sql_auto_kategory=$row["auto_kategory"];
		
		
		    echo '<li><a href="?pphoto='.$sql_auto_kategory.'"><div class="photo" data-title="'.$sql_photo_auto.'"><img src="/images/'.$sql_photo_path.'" width="256" height="190"></div></a></li>';
		
		
}

	echo'</div>';
	
	echo'<div id="pagin">';
$q="(SELECT count(*) FROM `gallery` WHERE photo_main=1)";
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
echo'</div>';
	
			}
		}	
	

//---------------------------------------------------------------------------------	
?>
