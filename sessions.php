<?php
if($_GET["exit"]==1)
{
	unset($_SESSION["SS_user_id"]);
	unset($_SESSION["SS_user_login"]);
	unset($_SESSION["SS_user_email"]);
	unset($_SESSION["SS_user_parol"]);
}
//-----------------------------Авторизация-------------------------------------------------
$html=<<<EOF
<form action="?" method="POST">
  <p>  
  <input type="hidden" name="auth" value="1">
  <input type="text" name="login" maxlength="20" placeholder="Логин" required></br>
  <input type="password" name="parol" maxlength="20" placeholder="Пароль" required></br>
  </p>
  <p><input type="submit"></p><a href="?regist=1">Зарегистрироваться</a>
  <input type="text" name="name" class="hid" maxlength="20" placeholder="Имя">
</form>
EOF;
if(($_POST["auth"]==1) && !empty($_POST["login"]) && !empty($_POST["parol"]) && empty($_POST["name"]))
{
	$login = $_POST['login'];
	$parol = $_POST['parol'];
	//если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали
	$login = stripslashes($login);
    $login = htmlspecialchars($login);
    $parol = stripslashes($parol);
    $parol = htmlspecialchars($parol);
    //удаляем лишние пробелы
    $login = trim($login);
    $parol = trim($parol);
	$parol = md5($parol);//шифруем пароль          
    $parol = strrev($parol);// для надежности реверс
	require_once("conect_bd.php");	
	$strSQL = "SELECT * FROM users WHERE user_login = '".$login."' AND user_pass = '".$parol."'";	
	$rezult = mysql_query($strSQL);
	if($pros_rezul = mysql_fetch_array($rezult))
	{
		$_SESSION['SS_user_id'] = $pros_rezul['user_id'];
		$_SESSION['SS_user_email'] = $pros_rezul['user_email'];
		$_SESSION['SS_user_login'] = $pros_rezul['user_login'];
		$_SESSION['SS_user_pass'] = $pros_rezul['user_pass'];
	}
	else
	{
		echo 'Вы ввели неверный пароль или логин, повторите попытку <a href="?exit=1">Для перехода нажмите <b>заново</b></a>';
		return;	
	}
}
if (isset($_SESSION['SS_user_id']))
 {
 	require_once("conect_bd.php");	
	
 	echo "Здравствуйте,  ".$_SESSION['SS_user_login'];
	echo '<br><a href="?exit=1">Выход</a>';
	return;
 }
//-----------------------------Регистрация----------------------------------------
if($_GET["regist"]==1)
{
	$reg=<<<EOF
<form action="?regist=1" method="POST">
  <p>Введите данные</p>
  <p>для регистрации</p></br>
  <p>
  <input type="hidden" name="regist" value="1">
  <input type="text" name="email" maxlength="20" placeholder="E-mail" required></br>
  <input type="text" name="login" maxlength="20" placeholder="Логин" required></br>
  <input type="password" name="parol" maxlength="20" placeholder="Пароль" required></br>
  <input type="password" name="parol2" maxlength="20" placeholder="Повтор пароля" required></br>
  <img src="captcha.php" weight="130px" height="50" alt=""></br></br></br>
  <input type = "text" name = "captcha" placeholder="Каптча" required></br>
  </p>  
  <p><input type="submit"></p>
  <input type="text" name="name" class="hid" maxlength="20" placeholder="Имя">
 </form>
EOF;
	if($_POST["regist"]==1 && !empty($_POST["login"]) && !empty($_POST["parol"]) && !empty($_POST["email"]) && empty($_POST["name"]))
	{
	$captcha = $_POST["captcha"]; // ответ, который ввёл пользователь
	$code = $_SESSION["rand_code"];
    if ($captcha != $code) { echo 'Капча введена неправильно';}
    else {
	$login = $_POST['login'];
	$parol = $_POST['parol'];
	$parol2 = $_POST['parol2'];
	$email = $_POST['email'];
	//если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали
	$login = stripslashes($login);
    $login = htmlspecialchars($login);
    $parol = stripslashes($parol);
    $parol = htmlspecialchars($parol);
	$parol2 = stripslashes($parol2);
    $parol2 = htmlspecialchars($parol2);
	$email = stripslashes($email);
    $email = htmlspecialchars($email);
    //удаляем лишние пробелы
    $login = trim($login);
    $parol = trim($parol);
	$parol2 = trim($parol2);
    $email = trim($email);
	        if (strlen($login) < 3 or strlen($login) > 20) 
			{
            echo 'Логин должен состоять не менее чем из 3 символов и не более чем из 20</br>';
            }
            elseif (strlen($parol) < 6 or strlen($parol) > 20) 
			{
            echo 'Пароль должен состоять не менее чем из 6 символов и не более чем из 20</br>';
            }
			elseif(!preg_match ("~^[A-Za-z0-9_]{3,20}$~i", $login))
            {
            echo 'В логине были обнаружены запрещенные символы</br>';
            }
			elseif(!preg_match ("~^[A-Za-z0-9]{6,20}$~i", $parol))
            {
            echo 'В пароле были обнаружены запрещенные символы</br>';
            }
			elseif (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)) 
            {
			echo 'Неверно введен е-mail</br>';
			} 
			elseif ($parol!=$parol2) 
            {
			echo 'Пароли не совпадают</br>';
			} 
	        else
			{
	$parol = md5($parol);//шифруем пароль          
    $parol = strrev($parol);// для надежности реверс
	require_once("conect_bd.php");
	$result = mysql_query("SELECT user_id FROM users WHERE user_login='".$login."'") or die(mysql_error());;
    $myrow = mysql_fetch_assoc($result);
    if (!empty($myrow['user_id'])) {
		echo 'Введённый вами логин уже зарегистрирован</br></br>';
    }
	$result = mysql_query("SELECT user_id FROM users WHERE user_email='".$email."'") or die(mysql_error());;
    $myrow = mysql_fetch_assoc($result);
    if (!empty($myrow['user_id'])) {
		echo 'Введённый вами почтовый адрес уже зарегистрирован</br>';
    }
	else{
		$strSQL = "INSERT INTO users(user_email,user_login,user_pass) VALUES('".$email."','".$login."','".$parol."')";
		mysql_query($strSQL) or die(mysql_error());
		echo '<a href="?exit=1">Для перехода нажмите <b>сюда</b></a>';
	}}}}
	else
	{
		echo($reg);
	}
	return;
}
echo($html);
?>