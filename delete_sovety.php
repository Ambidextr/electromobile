<?php
 include "conect_bd.php";
    session_start();
//require_once("conect_bd.php");

$idnews = mysql_real_escape_string($_POST['select_sovety']);
	if($_SESSION["SS_user_id"]==1)
{
	$del_query = mysql_query("DELETE FROM sovety WHERE id_sovety='".$idnews."'");
if ($del_query) {
            header('Location: succes.php');
        } 
		else 
		{
        echo "Произошла ошибка, вернитесь и повторите снова.";
        header('Refresh: 2; URL=novosti.php?admin=1');
        }
}
else 
{
        echo "Произошла ошибка, вернитесь и повторите снова.";
        header('Refresh: 2; URL=novosti.php?admin=1');
	}
?>




	
