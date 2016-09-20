<?php
header("Content-type: text/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
<channel>
<title>Электромобили </title>
<link>http://www.test1.ru/ </link>
<description>Новости, статьи и обзоры об электромобилях </description>';
include_once("conect_bd.php");

$query = "SELECT * FROM news ORDER by id_news desc LIMIT 20";

$res   = mysql_query($query);
while ($row=mysql_fetch_array($res)) {
// Убираем из тайтла html теги и лишние пробелы
$title   = strip_tags(trim($row['news_name']));
// С аноносом можно не проводить такие 
// манипуляции, т.к. мы вставим его в блок CDATA
$anon = $row['news_text'];
$url = $row['id_news'];
echo '<item>
        <title>'.$title.'</title>
		<description><![CDATA['.$anon.']]></description>
        <link>http://test1.ru/novosti.php?nnews='.$url.'</link>
        <guid isPermaLink="true">http://test1.ru/novosti.php?nnews='.$url.'</guid>
    </item>';
}
echo '</channel>
</rss>';
?>