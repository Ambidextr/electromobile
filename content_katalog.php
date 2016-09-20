<?php	
//----------------------Подключение БД ----------------------------------------------	
require_once("conect_bd.php");

//--------------------Вывод информации об авто---------------------------------------	
if(!empty($_GET['aauto']))
{
	$query = "SELECT * FROM catalogue WHERE auto_kategory = '".$_GET['aauto']."'";
	$result = mysql_query($query)or die(mysql_error());
	if (mysql_num_rows($result)>0) //Проверка кол-ва записей
	{
	if ($row = mysql_fetch_assoc($result))
		{
	    $sql_id_auto=$row["id_auto"];
		$sql_auto_name=$row["auto_name"];	
		$sql_auto_picture=$row["auto_picture"];
		$sql_auto_year=$row["auto_year"];
		$sql_auto_kuzov=$row["auto_kuzov"];
		$sql_auto_price=$row["auto_price"];	
		$sql_auto_text=$row["auto_text"];
$sql_auto_marka=$row["auto_marka"];
$sql_auto_kpp=$row["auto_kpp"];
$sql_auto_dveri=$row["auto_dveri"];
$sql_auto_mesta=$row["auto_mesta"];
$sql_auto_moshnost=$row["auto_moshnost"];
$sql_auto_zaradka=$row["auto_zaradka"];
$sql_auto_zapas_hoda=$row["auto_zapas_hoda"];
$sql_auto_privod=$row["auto_privod"];
$sql_auto_batareya=$row["auto_batareya"];
$sql_auto_max_speed=$row["auto_max_speed"];
$sql_auto_proizvoditel=$row["auto_proizvoditel"];
$sql_auto_massa=$row["auto_massa"];
$sql_auto_gabarity=$row["auto_gabarity"];	
$sql_auto_segment=$row["auto_segment"];
$sql_auto_bagaznik=$row["auto_bagaznik"];
$sql_auto_raspolozenie_motora=$row["auto_raspolozenie_motora"];
$sql_auto_razgon=$row["auto_razgon"];
$sql_auto_kategory=$row["auto_kategory"];

echo '<div id="welcome" class="obzor">
<div class="title">
			<h2><center>'.$sql_auto_name.'</center></h2>
			</div>
			<div class="story"><a href="gallery.php?pphoto='.$sql_auto_kategory.'"><img src="/images/'.$sql_auto_picture.'" alt="" width="580" height="350" class="left" /></a></br>
			</div>
                <table width="580px">
										   <tr><td width="50%">Год выпуска:</td><td>'.$sql_auto_year.'</td></tr>
										   <tr><td width="50%">Цена, $:</td><td>'.$sql_auto_price.'</td></tr>
										   <tr><td width="50%">Производитель:</td><td>'.$sql_auto_proizvoditel.'</td></tr>
										   <tr><td width="50%">Сегмент:</td><td>'.$sql_auto_segment.'</td></tr>
										   
										   <tr><td width="50%">Тип кузова:</td><td>'.$sql_auto_kuzov.'</td></tr>
									   	   <tr><td width="50%">КПП:</td><td>'.$sql_auto_kpp.'</td></tr>
                                            <tr><td width="50%">Привод:</td><td>'.$sql_auto_privod.'</td></tr>
											<tr><td width="50%">Расположение двигателя:</td><td>'.$sql_auto_raspolozenie_motora.'</td></tr>
											
											<tr><td width="50%">Количество дверей:</td><td>'.$sql_auto_dveri.'</td></tr>
											<tr><td width="50%">Количество мест:</td><td>'.$sql_auto_mesta.'</td></tr>
                                            <tr><td width="50%">Габариты, мм<br />(длина/ширина/высота)</td><td >'.$sql_auto_gabarity.'</td></tr>
											<tr><td width="50%">Масса, кг:</td><td >'.$sql_auto_massa.'</td></tr>
                                            <tr><td width="50%">Объем багажника, л:</td><td>'.$sql_auto_bagaznik.'</td></tr>
                                            
                                            <tr><td width="50%">Мощность двигателя, кВт:</td><td>'.$sql_auto_moshnost.'</td></tr>
                                            <tr><td width="50%">Время разгона с 0 до 100 км/ч, с:</td><td>'.$sql_auto_razgon.'</td></tr>
                                            <tr><td width="50%">Максимальная скорость, км/ч:</td><td>'.$sql_auto_max_speed.'</td></tr>
											<tr><td width="50%">Запас хода, км:</td><td>'.$sql_auto_zapas_hoda.'</td></tr>
											<tr><td width="50%">Полный заряд, ч:</td><td>'.$sql_auto_zaradka.'</td></tr>
											<tr><td width="50%">Аккумулятор:</td><td>'.$sql_auto_batareya.'</td></tr>
                </table>
				</br>
			<p>'.$sql_auto_text.'</p></br>
		</div>
		<div id="vk_comments"></div>';
		}
	}
}
//--------------------Вывод каталога-------------------------------------------	
else
{	
echo '<table width="580px">
    <thead>
    <tr>
          <th>Фото</th>
          <th>Название электромобиля</th>
		  <th>Цена, $</th>
          <th>Год выпуска</th>
	</tr>
  </thead>
  <tbody>';

// количество записей, выводимых на странице
$per_page=10;
// получаем номер страницы
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
// вычисляем первый оператор для LIMIT
$start=abs($page*$per_page);
// составляем запрос и выводим записи
// переменную $start используем, как нумератор записей.
	$query = "SELECT * FROM catalogue LIMIT $start,$per_page";
	$result = mysql_query($query)or die("Запрос не выполнен");
	if (mysql_num_rows($result)>0) //Проверка кол-ва записей
		{
			while ($row = mysql_fetch_assoc($result))
			{
	    $sql_id_auto=$row["id_auto"];
		$sql_auto_name=$row["auto_name"];	
		$sql_auto_marka=$row["auto_marka"];	
		$sql_auto_picture=$row["auto_picture"];
		$sql_auto_year=$row["auto_year"];
		$sql_auto_price=$row["auto_price"];	
		$sql_auto_text=$row["auto_text"];
		$sql_auto_kategory=$row["auto_kategory"];

echo          '<tr>
                  <td><a href="?aauto='.$sql_auto_kategory.'"><img src="/images/'.$sql_auto_picture.'" alt="Электромобиль" width="70" height="47"/></a></td>
				  <td>'.$sql_auto_name.'</td>
                  <td>'.$sql_auto_price.'</td>
                  <td>'.$sql_auto_year.'</td>
              </tr>';
}
  echo '</tbody>
                </table>';
				//нумерация страниц
$q="(SELECT count(*) FROM `catalogue`)";
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
			}
		}	
?>