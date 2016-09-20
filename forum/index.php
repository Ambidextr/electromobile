<?php

setlocale(LC_CTYPE, "ru_RU.utf-8");

// "Прошить" формы сессией
require 'config/connect.php';
require 'config/config.php';
require 'functions/functions.php';
require 'geshi/geshi.php';
 
@mysql_connect ( DB_LOCATION, DB_USER, DB_PASSWORD ) or die( 'Сервер базы данных недоступен' );
@mysql_query( 'SET NAMES utf8' );
@mysql_select_db ( DB_NAME ) or die( 'В настоящий момент база данных не доступна' );

session_start();

// Содержимое html-тега title
$pageTitle = FORUM_TITLE;

// Если пользователь не авторизован, но выставлена 
// cookie-переменная autologin - входим на форум
if ( !isset( $_SESSION['user'] ) and isset( $_COOKIE['autologin'] ) ) autoLogin();

// Эта функция выполняется при каждом просмотре страницы зарегистрированным 
// пользователем и устанавливает время последнего посещения форума
if ( isset( $_SESSION['user'] ) ) setTimeVisit();

// Кто из зарегистрированных пользователей сейчас на сайте?
getUsersOnLine();

// Этот небольшой код для проверки того, существует ли форум,
// ID кторого передается методом GET
if ( isset( $_GET['idForum'] ) ) {
  $_GET['idForum'] = (int)$_GET['idForum'];
  if ( $_GET['idForum'] < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Проверяем, есть ли форум с таким ID
  $query = "SELECT name FROM ".TABLE_FORUMS." WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании страницы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  // Такого форума не существует - редирект на главную страницу
  if ( mysql_num_rows( $res ) == 0 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
}

if ( !isset( $_GET['action'] ) ) $_GET['action'] = 'showMainPage';
$actions = array( 'showMainPage',
                  'showForum',
				  'showTheme',
				  'addForumForm',
				  'addForum',
				  'editForumForm',
				  'updateForum',
				  'forumUp',
				  'forumDown',
				  'deleteForum',
                  'addThemeForm',
                  'addTheme',
				  'editThemeForm',
				  'updateTheme',
				  'deleteTheme',
				  'lockTheme',
				  'unlockTheme',
                  'addPostForm',
                  'addPost',
				  'quickReply',
				  'editPostForm',
				  'updatePost',
				  'deletePost',
				  'loginForm',
				  'login',
				  'logout',
				  'addNewUserForm',
				  'addNewUser',
				  'activateUser',
				  'newPasswordForm',
				  'sendNewPassword',
				  'activatePassword',
				  'editUserForm',
				  'updateUser',
				  'editUserFormByAdmin',
				  'updateUserByAdmin',
				  'showUsersList',
				  'showUserInfo',
				  'sendMsgForm',
				  'sendMessage',
				  'deleteMsg',
				  'showMsgBox',
				  'showInBox',
				  'showOutBox',
				  'showMsg',
				  'sendMailForm',
				  'sendMail',
                  'searchForm',
				  'searchResult' );
if ( !in_array( $_GET['action'], $actions ) ) $_GET['action'] = 'showMainPage';

switch ( $_GET['action'] )
{
  case 'showMainPage':  // главная страница форума
    $content = getMainPage( $pageTitle );
	break;
  case 'showForum':     // список тем форума
    $content = getForum( $pageTitle );
	break;
  case 'showTheme':     // список сообщений темы
    $content = getTheme( $pageTitle );
	break;
  case 'addForumForm':  // форма для добавления нового форума
    $content = getAddForumForm();
	break;
  case 'addForum':      // добавить новый форум
    $content = addForum();
	break;
  case 'editForumForm': // форма для редактирования форума
    $content = getEditForumForm();
	break;
  case 'updateForum':   // обновить запись в таблице БД TABLE_FORUMS
    $content = updateForum();
	break;
  case 'forumUp':
    $content = forumUp();
	break;
  case 'forumDown':
    $content = forumDown();
	break;
  case 'deleteForum':   // удалить запись в таблице БД TABLE_FORUMS
    $content = deleteForum();
	break;
  case 'addThemeForm':  // форма для добавления новой темы
    $content = getAddThemeForm();
	break;
  case 'addTheme':      // добавить новую тему
    $content = addTheme();
	break;
  case 'editThemeForm': // форма для редактирования темы
    $content = getEditThemeForm();
	break;
  case 'updateTheme':   // обновить запись в таблице БД TABLE_THEMES
    $content = updateTheme();
	break;
  case 'deleteTheme':   // удалить тему
    $content = deleteTheme();
	break;
  case 'lockTheme':   // закрыть тему
    $content = lockTheme();
	break;
  case 'unlockTheme': // открыть тему
    $content = unlockTheme();
	break;
  case 'addPostForm':   // форма для добавления нового сообщения (поста)
    $content = getAddPostForm();
    break;
  case 'addPost':       // добавить новую запись в таблицу БД TABLE_POSTS
    $content = addPost();
	break;
  case 'quickReply':     // добавить новую запись в таблицу БД TABLE_POSTS
    $content = quickReply();
	break;
  case 'editPostForm':  // форма для редактирования сообщения (поста)
    $content = getEditPostForm();
    break;
  case 'updatePost':    // обновить запись в таблице БД TABLE_POSTS
    $content = updatePost();
	break;
  case 'deletePost':    // удалить запись в таблице БД TABLE_POSTS
    $content = deletePost();
	break;
  case 'loginForm':     // форма для входа на форум (авторизация)
    $content = getLoginForm();
	break;
  case 'login':         // вход на форум (авторизация)
    $content = login();
	break;
  case 'logout':        // выход
    $content = logout();
	break;
  case 'addNewUserForm': // форма для регистрации нового пользователя
    $content = getAddNewUserForm();
	break;
  case 'addNewUser':    // добавить нового пользователя
    $content = addNewUser();
	break;
  case 'activateUser':  // активация учетной записи нового пользователя
    $content = activateUser();
	break;
  case 'newPasswordForm': // форма для получения нового пароля
    $content = newPasswordForm();
	break;
  case 'sendNewPassword':    // выслать пользователю новый пароль
    $content = sendNewPassword();
	break;
  case 'activatePassword': // активация нового пароля
    $content = activatePassword();
	break;
  case 'editUserForm':  // форма для редактирования профиля
    $content = getEditUserForm();
	break;
  case 'updateUser':    // обновить данные о пользователе
    $content = updateUser();
	break;
  case 'editUserFormByAdmin':  // форма редактирования профиля (для администратора)
    $content = getEditUserFormByAdmin();
	break;
  case 'updateUserByAdmin':    // обновить данные о пользователе (для администратора)
    $content = updateUserByAdmin();
	break;
  case 'showUsersList': // список пользователей форума
    $content = getUsersList();
	break;
  case 'showUserInfo':  // информация о пользователе (profile)
    $content = showUserInfo();
	break;
  case 'sendMsgForm':   // форма для отправки личного сообщения
    $content = getSendMsgForm();
	break;
  case 'sendMessage':   // отправить личное сообщение
    $content = sendMessage();
	break;
  case 'deleteMsg':    // удалить личное сообщение
    $content = deleteMessage();
	break;
  case 'showMsg':      // показать сообщение
    $content = getMessage();
	break;
  case 'showInBox':    // папка "Входящие"
    $content = getInMsgBox();
	break;
  case 'showOutBox':   // папка "Исходящие"
    $content = getOutMsgBox();
	break;
  case 'sendMailForm':  // форма для отправки письма пользователю
    $content = getSendMailForm();
	break;
  case 'sendMail':      // отправка письма
    $content = sendMail();
	break;
  case 'searchForm':    // форма для поиска по форуму
    $content = searchForm();
	break;
  case 'searchResult':  // результаты поиска по форуму
    $content = searchResult();
	break;
  default:
    $content = getMainPage();   
}

$menu = getMainMenu();
$html = file_get_contents( './templates/default.html' );
$html = str_replace( '{title}', $pageTitle, $html );
$html = str_replace( '{description}', FORUM_DESCRIPTION, $html );
$html = str_replace( '{menu}', $menu, $html );
$html = str_replace( '{content}', $content, $html );

echo $html;

// Функция возвращает html главного меню форума
function getMainMenu()
{
  $html = '';
  // if ( isset( $_SESSION['user'] ) ) $html = $html.'<p>Вы вошли как: '.$_SESSION['user']['name'].'</p>'."\n";
  
  $html = $html.'<div class="mainmenu">'."\n";
  $html = $html.'<table>'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'<td><img src="./images/icon_mini_forums.gif" width="12" height="13" 
          border="0" alt="Список форумов" align="bottom" />&nbsp;<a class="mainmenu" href="'.
		  $_SERVER['PHP_SELF'].'">Список форумов</a>&nbsp;&nbsp;</td>'."\n";
  // Если пользователь не авторизован - выводим ссылки "Вход" и "Регистрация"
  if ( !isset( $_SESSION['user'] ) ) {
	$html = $html.'<td><img src="./images/icon_mini_login.gif" width="12" height="13" 
            border="0" alt="Вход" align="bottom" />&nbsp;<a class="mainmenu" 
			href="'.$_SERVER['PHP_SELF'].'?action=loginForm">Вход</a>&nbsp;&nbsp;</td>'."\n";
	$html = $html.'<td><img src="./images/icon_mini_register.gif" width="12" height="13" 
            border="0" alt="Регистрация" align="bottom" />&nbsp;<a class="mainmenu" 
			href="'.$_SERVER['PHP_SELF'].'?action=addNewUserForm">Регистрация</a>&nbsp;&nbsp;</td>'."\n";
  } else {
	$html = $html.'<td><img src="./images/icon_mini_login.gif" width="12" height="13" 
            alt="Вход" align="bottom" />&nbsp;<a class="mainmenu" 
			href="'.$_SERVER['PHP_SELF'].'?action=logout">Выход</a>&nbsp;&nbsp;</td>'."\n";
	$html = $html.'<td><img src="./images/icon_mini_profile.gif" width="12" height="13" 
	        alt="Профиль" align="bottom" />&nbsp;<a class="mainmenu" 
			href="'.$_SERVER['PHP_SELF'].'?action=editUserForm">Профиль</a>&nbsp;&nbsp;</td>'."\n";
  }
  $html = $html.'<td><img src="./images/icon_mini_members.gif" width="12" height="13" 
          alt="Пользователи" align="bottom" />&nbsp;<a class="mainmenu" 
		  href="'.$_SERVER['PHP_SELF'].'?action=showUsersList">Пользователи</a>&nbsp;&nbsp;</td>'."\n";
  $html = $html.'<td><img src="./images/icon_mini_search.gif" width="12" height="13" 
          alt="Поиск" align="bottom" />&nbsp;<a class="mainmenu" 
		  href="'.$_SERVER['PHP_SELF'].'?action=searchForm">Поиск</a>&nbsp;&nbsp;</td>'."\n";
		  
  if ( isset( $_SESSION['user'] ) ) {
    // Есть ли непрочитанные сообщения в папке "Входящие"?
	$cntNewMsg = countNewMessages();
    if ( $cntNewMsg == 0 )
      $html = $html.'<td><img src="./images/icon_mini_message.gif" width="12" height="13" 
              alt="Личные сообщения" align="bottom" />&nbsp;<a class="mainmenu" 
		      href="'.$_SERVER['PHP_SELF'].'?action=showInBox">Личные&nbsp;сообщения</a>&nbsp;&nbsp;</td>'."\n";
    else if ( $cntNewMsg == 1 )
      $html = $html.'<td><img src="./images/icon_mini_message.gif" width="12" height="13" 
              alt="Новое сообщение" align="bottom" />&nbsp;<a class="newMessages" 
		      href="'.$_SERVER['PHP_SELF'].'?action=showInBox">Новое&nbsp;сообщение</a>&nbsp;&nbsp;</td>'."\n";
    else
      $html = $html.'<td><img src="./images/icon_mini_message.gif" width="12" height="13" 
              alt="Новые сообщения" align="bottom" />&nbsp;<a class="newMessages" 
		      href="'.$_SERVER['PHP_SELF'].'?action=showInBox">Новые&nbsp;сообщения</a>&nbsp;&nbsp;</td>'."\n";	
  }
  if ( isset( $_SESSION['user'] ) and $_SESSION['user']['status'] == 'admin' ) {
    $html = $html.'<td><img src="./images/icon_mini_addforum.gif" width="12" height="13" 
            border="0" alt="Добавить форум" align="bottom" />&nbsp;<a class="mainmenu" 
			href="'.$_SERVER['PHP_SELF'].'?action=addForumForm">Добавить форум</a></td>'."\n";
  }
  $html = $html.'</tr>'."\n";
  $html = $html.'</table>'."\n";
  $html = $html.'</div>'."\n";
  return $html;
}

// Функция возвращает html главной страница форума (список форумов)
function getMainPage( &$pageTitle )
{
  $pageTitle = $pageTitle.' / Список форумов';
  $query = "SELECT id_forum, name, description FROM ".TABLE_FORUMS." WHERE 1 ORDER BY pos";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка форумов';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  $html = '<h1>Список форумов</h1>'."\n";
  if ( mysql_num_rows( $res ) > 0 ) {
	while ( $forum = mysql_fetch_array( $res ) ) {
	
	  $html = $html.'<table width="100%" cellpadding="0" cellspacing="0">'."\n";
	  $html = $html.'<tr>'."\n";
	  // Выводим название форума
	  $html = $html.'<td>';
	  $html = $html.'<div><a class="header" href="'.
	          $_SERVER['PHP_SELF'].'?action=showForum&idForum='.
	          $forum['id_forum'].'">'.
			  $forum['name'].'</a></div>'."\n";
	  $html = $html.'<div style="font-size:smaller">'.$forum['description'].'</a></div>';
	  // Выводим краткое описание форума
	  $html = $html.'</td>'."\n";
	  // Ссылка "Править форум"
      if ( isset( $_SESSION['user'] ) and $_SESSION['user']['status'] == 'admin' ) {
        $html = $html.'<td align="right"><a href="'.$_SERVER['PHP_SELF'].
		        '?action=forumUp&idForum='.$forum['id_forum'].'"><img 
				src="./images/icon_up.gif"
			    alt="Вверх" title="Вверх" /></a>&nbsp;'."\n";
        $html = $html.'<a href="'.$_SERVER['PHP_SELF'].
		        '?action=forumDown&idForum='.$forum['id_forum'].'"><img 
				src="./images/icon_down.gif"
			    alt="Вниз" title="Вниз" /></a>&nbsp;'."\n";
		$html = $html.'<a href="'.$_SERVER['PHP_SELF'].
		        '?action=editForumForm&idForum='.$forum['id_forum'].'"><img 
				src="./images/icon_edit.gif"
			    alt="Править" title="Править" /></a>&nbsp;'."\n";
        $html = $html.'<a href="'.$_SERVER['PHP_SELF'].
		        '?action=deleteForum&idForum='.$forum['id_forum'].'"><img 
				src="./images/icon_delete.gif" alt="Удалить" title="Удалить" /></a></td>'."\n";				
      }
	  $html = $html.'</tr>'."\n";
	  $html = $html.'</table>'."\n";
	  /*	  
	  // Выводим три темы форума, сортирируя их по последнему сообщению
      $q = "SELECT a.id_theme, a.name, a.time, a.locked,
                   IFNULL(b.id_author, 0), 
				   IFNULL(b.name, '".NOT_REGISTERED_USER."'),         
				   (COUNT(*)-1), 
				   IFNULL(MAX(c.time), '0000-00-00 00:00:00') AS last_post,
				   MAX(c.id_post)
	        FROM ".TABLE_THEMES." a LEFT JOIN ".TABLE_USERS." b
			ON a.id_author=b.id_author
			LEFT JOIN ".TABLE_POSTS." c
			ON a.id_theme=c.id_theme
			WHERE id_forum=".$forum['id_forum']."
			GROUP BY a.id_theme, a.name, a.time, a.locked,
                     IFNULL(b.id_author, 0), 
					 IFNULL(b.name, '".NOT_REGISTERED_USER."')
			ORDER BY last_post DESC
			LIMIT 2";
      */
      $q = "SELECT id_theme, name, id_author, author, time, id_last_author, last_author, last_post, locked
	        FROM ".TABLE_THEMES."
			WHERE id_forum=".$forum['id_forum']."
			ORDER BY last_post DESC
			LIMIT 3";	
		
	  $r = mysql_query( $q );
	  
	  $html = $html.'<table class="showTable">'."\n";
	  $html = $html.'<tr>'."\n";
	  $html = $html.'<th width="23"><img src="./images/null.gif" width="23" height="1" alt="" /></th>'."\n";
	  $html = $html.'<th width="50%">Тема</th>'."\n";
	  $html = $html.'<th width="14%">Автор</th>'."\n";
	  $html = $html.'<th width="14%">Добавлена</th>'."\n";
      // $html = $html.'<th width="6%">Ответов</th>'."\n";	  
	  $html = $html.'<th width="20%">Последнее&nbsp;сообщение</th>'."\n";
	  $html = $html.'</tr>'."\n";
	  
	  if ( mysql_num_rows( $r ) == 0 ) {
	    $html = $html.'<tr>'."\n";
	    $html = $html.'<td colspan="6">'."\n";
		$html = $html.'В этом форуме пока нет сообщений';
	    $html = $html.'</td>'."\n";
	    $html = $html.'</tr>'."\n";
		$html = $html.'</table>'."\n";
        continue;		
	  }

	  while ( $theme = mysql_fetch_array( $r ) ) {
	    $html = $html.'<tr>'."\n";
	    if ( isset( $_SESSION['user'] ) ) { // это для зарегистрированного пользователя
	      // Если есть новые сообщения (посты) - только для зарегистрированных пользователей
	      if ( isset( $_SESSION['newThemes'] ) and in_array( $theme[0], $_SESSION['newThemes'] ) ) {
		    if ( $theme['locked'] == 0 ) // тема открыта
	          $html = $html.'<td align="center" valign="middle"><img src="./images/folder_new.gif" width="19"
		              height="18" alt="Новые сообщения" title="Новые сообщения" /></td>';
            else // тема закрыта		
	          $html = $html.'<td align="center" valign="middle"><img src="./images/folder_lock_new.gif" width="19"
		              height="18" alt="Новые сообщения" title="Новые сообщения" /></td>';
		  } else {
		    if ( $theme['locked'] == 0 ) // тема открыта
		      $html = $html.'<td align="center" valign="middle"><img src="./images/folder.gif" width="19" 
		              height="18" alt="Нет новых сообщений" title="Нет новых сообщений" /></td>';
            else // тема закрыта
		      $html = $html.'<td align="center" valign="middle"><img src="./images/folder_lock.gif" width="19" 
		              height="18" alt="Нет новых сообщений" title="Нет новых сообщений" /></td>';		  
          }
	    } else { // это для не зарегистрированного пользователя
	      if ( $theme['locked'] == 0 ) // тема открыта
		    $html = $html.'<td align="center" valign="middle"><img src="./images/folder.gif" width="19" 
		            height="18" alt="" /></td>';
          else // тема закрыта
		    $html = $html.'<td align="center" valign="middle"><img src="./images/folder_lock.gif" width="19"
                    height="18" alt="" /></td>';		
	    }
	    // Название темы
	    $html = $html.'<td>';
	    $html = $html.'<a class="topictitle" href="'.$_SERVER['PHP_SELF'].'?action=showTheme&idForum='.
	            $forum['id_forum'].'&id_theme='.$theme[0].'">'.$theme[1].'</a>';
	    $html = $html.'</td>';
	    $html = $html.'<td align="center" nowrap="nowrap">'."\n";
	    // Автор темы
		if ( $theme['id_author'] )
	      $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=showUserInfo&idUser='.
	              $theme['id_author'].'">'.$theme['author'].'</a>';
		else
		  $html = $html.$theme['author'];
	    $html = $html.'</td>';
	    // Дата добавления темы
	    $html = $html.'<td align="center"><span class="details">';
	    $html = $html.$theme['time'];
	    $html = $html.'</span></td>'."\n";
	    // Количество ответов
	    // $html = $html.'<td align="center"><span class="details">';
	    // $html = $html.$theme[6];
	    // $html = $html.'</td></span>'."\n";
	    // Дата последнего обновления
	    $html = $html.'<td align="center"><span class="details">';
	    $html = $html.$theme['last_post'];
		// Автор последнего сообщения (поста)
		if ( $theme['id_last_author'] )
	      $html = $html.' <a href="'.$_SERVER['PHP_SELF'].'?action=showUserInfo&idUser='.
	              $theme['id_last_author'].'">'.$theme['last_author'].'</a>';
		else
		  $html = $html.' '.$theme['last_author'];
	    $html = $html.'</span></td>'."\n";
	    $html = $html.'</tr>'."\n";
	  }
	  $html = $html.'</table>'."\n";
    }	  
  } else {
    $html = '<p>Не найдено ни одного форума</p>'."\n";
  }
  
  $html = $html.getStat();
  
  return $html;
}

// Функция возвращает список тем форума; ID форума передается методом GET
function getForum( &$pageTitle )
{
  // Если не передан ID форума - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Получаем информацию о форуме
  $query = "SELECT name FROM ".TABLE_FORUMS." WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка тем форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  $forum = mysql_result( $res, 0, 0 );
  // Заголовок страницы (содержимое тега title)
  $pageTitle = $pageTitle.' / '.$forum;
  // Выводим название форума
  $html = '<h1>'.$forum.'</h1>'."\n";

  // Панель навигации
  $html = $html.'<div class="navDiv">'."\n";
  $html = $html.'<a class="navigation" href="'.$_SERVER['PHP_SELF'].'">Список форумов</a>&nbsp;&gt;'."\n";
  $html = $html.'<a class="navigation" href="'.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.
          $_GET['idForum'].'">'.$forum.'</a>'."\n";
  $html = $html.'</div>'."\n";
  
  // Ссылка "Начать новую тему" - только для зарегистрированных пользователей
  if ( isset( $_SESSION['user'] ) ) {
    $addTheme = '<a href="'.$_SERVER['PHP_SELF'].'?action=addThemeForm&idForum='.
                $_GET['idForum'].'"><img src="./images/post.gif"  
		        alt="Начать новую тему" /></a>'."\n";
  }
  // Выбираем из БД количество тем форума - это нужно для 
  // построения постраничной навигации
  $query = "SELECT COUNT(*) FROM ".TABLE_THEMES." WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка тем форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  $total = mysql_result( $res, 0, 0 );
  
  if ( $total == 0 ) {
  if ( isset( $_SESSION['user'] ) ) 
    return $html.$addTheme;
  else 
    return $html.'<p>В этом форуме пока нет сообщений</p>'."\n";
  }
  
  // Число страниц списка тем форума (постраничная навигация)
  $cntPages = ceil( $total / THEMES_PER_PAGE );
  
  // Проверяем передан ли номер текущей страницы (постраничная навигация)
  if ( isset($_GET['page']) ) {
    $page = (int)$_GET['page'];
    if ( $page < 1 ) $page = 1;
  } else {
    $page = 1;
  }

  if ( $page > $cntPages ) $page = $cntPages;
  // Начальная позиция (постраничная навигация)
  $start = ( $page - 1 ) * THEMES_PER_PAGE;

  // Строим постраничную навигацию, если это необходимо
  if ( $cntPages > 1 ) {
    // Функция возвращает html меню для постраничной навигации
    $pages = pageIterator( $page, $cntPages, $_SERVER['PHP_SELF'].'?action=showForum&idForum='.
	                       $_GET['idForum'] );		   
  }
			 
  // Постраничную навигацию и ссылку "Начать новую тему" объединяем в один блок,
  // который выводится вверху и внизу страницы
  if ( isset( $pages ) or isset( $addTheme ) ) {
    $pagesAddTheme = '<table width="100%" cellpadding="0" cellspacing="0">'."\n";
    $pagesAddTheme = $pagesAddTheme.'<tr valign="middle">'."\n";
    if ( isset( $pages ) ) $pagesAddTheme = $pagesAddTheme.'<td>'.$pages.'</td>'."\n";
    if ( isset( $addTheme ) ) $pagesAddTheme = $pagesAddTheme.'<td align="right">'.$addTheme.'</td>'."\n";
    $pagesAddTheme = $pagesAddTheme.'</tr>'."\n";
    $pagesAddTheme = $pagesAddTheme.'</table>'."\n";
  }
  
  // Постраничная навигация и ссылка "Начать новую тему"
  if ( isset( $pagesAddTheme ) ) $html = $html.$pagesAddTheme;
		  
  /*
  // Получаем список тем форума, сортирируя их по последнему сообщению
  $query = "SELECT a.id_theme, a.name, a.time, a.locked,
                   IFNULL(b.id_author, 0), IFNULL(b.name, '".NOT_REGISTERED_USER."'),         
				   (COUNT(*)-1), IFNULL(MAX(c.time), '') AS last_post
	        FROM ".TABLE_THEMES." a LEFT JOIN ".TABLE_USERS." b
			ON a.id_author=b.id_author
			LEFT JOIN ".TABLE_POSTS." c
			ON a.id_theme=c.id_theme
			WHERE id_forum=".$_GET['idForum']."
			GROUP BY a.id_theme, a.name, a.time,
                     IFNULL(b.id_author, 0), IFNULL(b.name, '".NOT_REGISTERED_USER."')
			ORDER BY last_post DESC
			LIMIT ".$start.", ".THEMES_PER_PAGE;
  */

  $query = "SELECT id_theme, name, id_author, author, time, id_last_author, last_author, last_post, locked
	        FROM ".TABLE_THEMES."
			WHERE id_forum=".$_GET['idForum']."
			ORDER BY last_post DESC
			LIMIT ".$start.", ".THEMES_PER_PAGE;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка тем форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  if ( mysql_num_rows( $res ) > 0 ) {
	$html = $html.'<table class="showTable">'."\n";
    $html = $html.'<tr>'."\n";
	$html = $html.'<th>&nbsp;</th>';
    $html = $html.'<th width="50%">Темы</th>';
	$html = $html.'<th>Автор</th>';
	$html = $html.'<th>Добавлена</th>';
	// $html = $html.'<th>Ответов</th>';
	$html = $html.'<th>Последнее&nbsp;сообщение</th>'."\n";
	if ( isset( $_SESSION['user'] ) and $_SESSION['user']['status'] != 'user' ) {
	  $html = $html.'<th>Правка</th>'."\n";
	  $html = $html.'<th>Блк.</th>'."\n";
	  $html = $html.'<th>Удл.</th>'."\n";
	}
	  
	$html = $html.'</tr>'."\n";
	while ( $theme = mysql_fetch_array( $res ) ) {
	  $html = $html.'<tr>'."\n";
	  if ( isset( $_SESSION['user'] ) ) { // это для зарегистрированного пользователя
	    // Если есть новые сообщения (посты) - только для зарегистрированных пользователей
	    if ( isset( $_SESSION['newThemes'] ) and in_array( $theme['id_theme'], $_SESSION['newThemes'] ) ) {
		  if ( $theme['locked'] == 0 ) // тема открыта
	        $html = $html.'<td align="center" valign="middle"><img src="./images/folder_new.gif" width="19"
		            height="18" alt="Новые сообщения" title="Новые сообщения" /></td>';
          else // тема закрыта		
	        $html = $html.'<td align="center" valign="middle"><img src="./images/folder_lock_new.gif" width="19"
		            height="18" alt="Новые сообщения" title="Новые сообщения" /></td>';
		} else {
		  if ( $theme['locked'] == 0 ) // тема открыта
		    $html = $html.'<td align="center" valign="middle"><img src="./images/folder.gif" width="19" 
		            height="18" alt="Нет новых сообщений" title="Нет новых сообщений" /></td>';
          else // тема закрыта
		    $html = $html.'<td align="center" valign="middle"><img src="./images/folder_lock.gif" width="19" 
		            height="18" alt="Нет новых сообщений" title="Нет новых сообщений" /></td>';		  
        }
	  } else { // это для не зарегистрированного пользователя
	    if ( $theme['locked'] == 0 ) // тема открыта
		  $html = $html.'<td align="center" valign="middle"><img src="./images/folder.gif" width="19" 
		          height="18" alt="" /></td>';
        else // тема закрыта
		  $html = $html.'<td align="center" valign="middle"><img src="./images/folder_lock.gif" width="19"
                  height="18" alt="" /></td>';		
	  }
	  
	  // Название темы
	  $html = $html.'<td>';
	  $html = $html.'<a class="topictitle" href="'.$_SERVER['PHP_SELF'].'?action=showTheme&idForum='.
	          $_GET['idForum'].'&id_theme='.$theme['id_theme'].'">'.$theme['name'].'</a>';
	  $html = $html.'</td>';
	  $html = $html.'<td align="center">'."\n";
	  // Автор темы
	  if ( $theme['id_author'] ) {
	    $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=showUserInfo&idUser='.
	            $theme['id_author'].'">'.$theme['author'].'</a>';
	  } else {
	    $html = $html.$theme[5];
	  }
	  $html = $html.'</td>';
	  // Дата добавления темы
	  $html = $html.'<td align="center" nowrap="nowrap"><span class="details">';
	  $html = $html.$theme['time'];
	  $html = $html.'</span></td>'."\n";
	  // Количество ответов
	  // $html = $html.'<td align="center" nowrap="nowrap">';
	  // $html = $html.$theme[6];
	  // $html = $html.'</td>'."\n";
	  // Дата последнего обновления
	  $html = $html.'<td align="center"><span class="details">';
	  $html = $html.$theme['last_post'];
      // Автор последнего сообщения (поста)
      if ( $theme['id_last_author'] )
	    $html = $html.' <a href="'.$_SERVER['PHP_SELF'].'?action=showUserInfo&idUser='.
	            $theme['id_last_author'].'">'.$theme['last_author'].'</a>';
      else
		$html = $html.' '.$theme['last_author'];
	  $html = $html.'</span></td>'."\n";  
	  // Ссылки "Редактировать", "Закрыть"/"Открыть" и "Удалить" - 
	  // только для администратора и модератора
	  if ( isset( $_SESSION['user'] ) and $_SESSION['user']['status'] != 'user' ) {
	  $html = $html.'<td align="center" nowrap="nowrap">';
	  $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=editThemeForm&idForum='.
	          $_GET['idForum'].'&id_theme='.$theme[0].'"><img src="./images/icon_edit.gif"
			  alt="Править" title="Править" /></a>';
	  $html = $html.'</td>'."\n";
	  $html = $html.'<td align="center" nowrap="nowrap">';
	  if ( $theme['locked'] == 0 ) { // заблокировать тему
	    $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=lockTheme&idForum='.
	            $_GET['idForum'].'&id_theme='.$theme['id_theme'].'"><img src="./images/topic_lock.gif"
			    alt="Закрыть" title="Закрыть" /></a>';
	  } else { // разблокировать тему
	    $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=unlockTheme&idForum='.
	            $_GET['idForum'].'&id_theme='.$theme['id_theme'].'"><img src="./images/topic_unlock.gif"
			    alt="Открыть" title="Открыть" /></a>';
      }
	  $html = $html.'</td>'."\n";
	  $html = $html.'<td align="center" nowrap="nowrap">';
	  $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=deleteTheme&idForum='.
	          $_GET['idForum'].'&id_theme='.$theme['id_theme'].'"><img src="./images/icon_delete.gif"
			  alt="Удалить" title="Удалить" /></a>';
      $html = $html.'</td>'."\n";
	  
	  }
	  $html = $html.'</tr>'."\n";
	}
	$html = $html.'</table>'."\n";
	
    // Постраничная навигация и ссылка "Начать новую тему"
    if ( isset( $pagesAddTheme ) ) $html = $html.$pagesAddTheme;
  
  }
  return $html;
}

// Функция возвращает список сообщений(постов) темы; ID темы передается методом GET
function getTheme( &$pageTitle )
{
  // Если не передан ID форума - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  } 
  // Если не передан ID темы - функция вызвана по ошибке
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }  
  
  // Получаем из БД информацию о теме
  $query = "SELECT name, locked FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка сообщений темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showForum&idForum='.$_GET['idForum'] );
  }
  // Если запрошенной темы не существует - возвращаемся на форум
  if ( mysql_num_rows( $res ) == 0 ) 
    return showInfoMessage( 'Запрошенная тема не найдена', 'action=showForum&idForum='.$_GET['idForum'] );
  
  list( $theme, $locked ) = mysql_fetch_row( $res );
  // Заголовок страницы (содержимое тега title)
  $pageTitle = $pageTitle.' / '.$theme;
  // Название темы
  $html = '<h1>'.$theme.'</h1>'."\n";

  // Получаем информацию о форуме - это нужно для построения панели навигации
  $query = "SELECT name FROM ".TABLE_FORUMS." WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка сообщений темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.
                             $_GET['idForum'] );
  }
  // Панель навигации
  $html = $html.'<div class="navDiv">'."\n";
  $html = $html.'<a class="navigation" href="'.$_SERVER['PHP_SELF'].'">Список форумов</a>&nbsp;&gt;'."\n";
  $html = $html.'<a class="navigation" href="'.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.
          $_GET['idForum'].'">'.mysql_result( $res, 0, 0 ).'</a>&nbsp;&gt;'."\n";
  $html = $html.'<a class="navigation" href="'.$_SERVER['PHP_SELF'].'?action=showTheme&idForum='.
          $_GET['idForum'].'&id_theme='.$id_theme.'">'.$theme.'</a>'."\n";
  $html = $html.'</div>'."\n";
  
  // Выбираем из БД количество сообщений - это нужно для 
  // построения постраничной навигации
  $query = "SELECT COUNT(*) FROM ".TABLE_POSTS." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка сообщений темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showForum&idForum='.$_GET['idForum'] );
  }
  $total = mysql_result( $res, 0, 0 );
  // Не может быть темы, в которой нет сообщений (постов) - надо ее удалить
  if ( $total == 0 ) {
	$q = "DELETE FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
	$r = mysql_query( $q );
    if ( !$r ) {
      $msg = 'Ошибка при получении списка сообщений темы';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $q.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
    }
    return showInfoMessage( 'Запрошенная тема не найдена', 'action=showForum&idForum='.$_GET['idForum'] );	
  }

  // Число страниц списка сообщений (постов) темы (постраничная навигация)
  $cntPages = ceil( $total / POSTS_PER_PAGE );
  
  // Проверяем передан ли номер текущей страницы (постраничная навигация)
  if ( isset($_GET['page']) ) {
    $page = (int)$_GET['page'];
    if ( $page < 1 ) $page = 1;
  } else {
    $page = $cntPages;
  }

  if ( $page > $cntPages ) $page = $cntPages;
  // Начальная позиция (постраничная навигация)
  $start = ( $page - 1 ) * POSTS_PER_PAGE;

  // Строим постраничную навигацию, если это необходимо
  if ( $cntPages > 1 ) {
    // Функция возвращает html меню для постраничной навигации
    $pages = pageIterator( $page, $cntPages, $_SERVER['PHP_SELF'].'?action=showTheme&idForum='.
	                       $_GET['idForum'].'&id_theme='.$id_theme );		   
  } else {
    $pages = '&nbsp;';
  }
  
  // Получаем из БД список сообщений (постов) темы
  $query = "SELECT a.id_post, a.name, a.id_author, a.time, a.putfile, a.locked, a.id_theme, 
                   DATE_FORMAT(a.edittime, '%d.%m.%Y') AS edittime, a.id_editor, 
                   IFNULL(b.name, '".NOT_REGISTERED_USER."') AS author, b.posts, b.url, 
				   DATE_FORMAT(b.puttime, '%d.%m.%Y') AS regtime, b.status AS status, 
				   IFNULL(b.signature, '') AS signature, IFNULL(b.locked, 0) AS blocked,
				   IFNULL(c.name, '') AS editor, IFNULL(c.status, '') AS editor_status	
            FROM ".TABLE_POSTS." a LEFT JOIN ".TABLE_USERS." b
            ON a.id_author=b.id_author	
            LEFT JOIN ".TABLE_USERS." c
            ON a.id_editor=c.id_author			
			WHERE id_theme=".$id_theme." ORDER BY time ASC 
			LIMIT ".$start.", ".POSTS_PER_PAGE;
	
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении списка сообщений темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showForum&idForum='.$_GET['idForum'] );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    // Не может быть темы, в которой нет сообщений (постов) - надо ее удалить
	$q = "DELETE FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
	$r = mysql_query( $q );
    if ( !$r ) {
      $msg = 'Ошибка при получении списка сообщений темы';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $q.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
    }
    return showInfoMessage( 'Запрошенная тема не найдена', 'action=showForum&idForum='.$_GET['idForum'] );	
  }
  // Ссылка "Ответить" (если тема закрыта - выводим сообщение "Тема закрыта")
  if ( $locked == 0 )
    $addPost = '<a href="'.$_SERVER['PHP_SELF'].'?action=addPostForm&idForum='.$_GET['idForum'].
	           '&id_theme='.$id_theme.'"><img src="./images/reply.gif"
		       alt="Ответить" title="Ответить" /></a>'."\n";
  else
    $addPost = '<img src="./images/reply_locked.gif"
		       alt="Тема закрыта" title="Тема закрыта" />'."\n";  
			 
  // Постраничную навигацию и ссылку "Ответить" объединяем в один блок,
  // который выводится вверху и внизу страницы
  $pagesAddPost = '<table width="100%" cellpadding="0" cellspacing="0">'."\n";
  $pagesAddPost = $pagesAddPost.'<tr valign="middle">'."\n";
  $pagesAddPost = $pagesAddPost.'<td>'.$pages.'</td>'."\n";
  $pagesAddPost = $pagesAddPost.'<td align="right">'.$addPost.'</td>'."\n";
  $pagesAddPost = $pagesAddPost.'</tr>'."\n";
  $pagesAddPost = $pagesAddPost.'</table>'."\n"; 
  
  $html = $html.$pagesAddPost;
  // Сообщения (посты) темы; каждое сообщение - отдельная таблица	  
  while ( $post = mysql_fetch_array( $res ) ) {
	$html = $html.'<table class="postTable">'."\n";
	$html = $html.'<tr class="postTop">'."\n";
	$html = $html.'<td width="120"><span class="postAuthor" onClick="javascript:putName(\''.
	         $post['author'].'\')" onMouseOver="this.className=\'postAuthorOver\'"
             onMouseOut="this.className=\'postAuthor\'">'.$post['author'].
			 '</span><br/><img src="./images/null.gif" alt="" width="120" height="1" /></td>'."\n";
	$html = $html.'<td width="45%"><span class="details">&nbsp;Добавлено '.$post['time'].'</span></td>'."\n";
	$html = $html.'<td width="45%" align="right">';
	// Если тема не заблокирована - выводим ссылку "Ответить с цитатой"
	if ( $locked == 0 ) {
	  $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=addPostForm&idForum='.$_GET['idForum'].
	          '&id_theme='.$id_theme.'"><img src="./images/icon_quote.gif"
			   alt="Ответить с цитатой" title="Ответить с цитатой" border="0" /></a>&nbsp;&nbsp;';
    }
	// Определяем, нужно ли выводить ссылку "Редактировать"
    if ( hasRightEditPost( $post ) ) {
	  $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=editPostForm&idForum='.$_GET['idForum'].
	          '&id_theme='.$id_theme.'&id_post='.$post['id_post'].'"><img src="./images/icon_edit.gif"
			  alt="Править" title="Править" border="0" /></a>&nbsp;&nbsp;';
	}
	// Определяем, нужно ли выводить ссылку "Удалить"
    if ( hasRightDeletePost( $post ) ) {
	  $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=deletePost&idForum='.$_GET['idForum'].
	          '&id_theme='.$id_theme.'&id_post='.$post['id_post'].'"><img src="./images/icon_delete.gif"
			  alt="Удалить" title="Удалить" border="0" /></a>';
	}
	$html = $html.'</td>'."\n";
	$html = $html.'</tr>'."\n";
	$html = $html.'<tr class="postMiddle">'."\n";
	$html = $html.'<td>'."\n";
	// Если автор сообщения (поста) - зарегистрированный пользователь
	if ( $post['id_author'] ) {	  
	  // Аватар
	  if ( is_file( './photo/'.$post['id_author'] ) ) {
	    $html = $html.'<img src="./photo/'.$post['id_author'].'" alt="'.$post['author'].'" 
		        title="'.$post['author'].'" /><br/>'."\n";
	  } else {
	    $html = $html.'<img src="./images/null.gif" alt="" width="100" height="100" 
		        style="border:1px solid #CCCCCC" /><br/>'."\n";
	  }
	  // Статус пользователя
	  $status = array( 'user' => 'Пользователь',
                       'moderator' => 'Модератор',
				       'admin' => 'Администратор' );
	  if ( $post['status'] == 'admin' ) 
	    $html = $html.'<span class="adminStatus">'.$status[$post['status']].'</span><br/>'."\n";
	  if ( $post['status'] == 'moderator' ) 
	    $html = $html.'<span class="moderStatus">'.$status[$post['status']].'</span><br/>'."\n";
	  /*
	  if ( $post['status'] == 'user' ) 
	    $html = $html.'<span class="userStatus">'.$status[$post['status']].'</span><br/>'."\n";
	  */
      // Рейтинг пользователя (по количеству сообщений)
	  $stars = '';
	  $rating = $post['posts'];
	  while( $rating > 0 ) {
        if ( $rating < 50 )
          $img = 'stars0.gif';
        else if ( $rating >= 50 and $rating < 100 )
          $img = 'stars1.gif';
        else if ( $rating >= 100 and $rating < 150 )
          $img = 'stars2.gif';
        else if ( $rating >= 150 and $rating < 200 )
          $img = 'stars3.gif';
        else if ( $rating >= 200 and $rating < 250 )
          $img = 'stars4.gif';
        else if ( $rating >= 250 and $rating < 300 )
          $img = 'stars5.gif';
        else if ( $rating >= 300 and $rating < 350 )
          $img = 'stars6.gif';
        else if ( $rating >= 350 and $rating < 400 )
          $img = 'stars7.gif';
        else if ( $rating >= 400 and $rating < 450 )
          $img = 'stars8.gif';
        else if ( $rating >= 450 and $rating < 500 )
          $img = 'stars9.gif';
        else
          $img = 'stars10.gif';
		$rating = $rating - 500;
        $stars = $stars.'<img src="./images/'.$img.'" alt="" /><br/>';
	  }
	  $html = $html.$stars.'<br/>'."\n";
	  // Количество сообщений
	  $html = $html.'<span class="details">Сообщений:&nbsp;'.$post['posts'].'</span><br/>'."\n";
	  // Дата регистрации
	   $html = $html.'<span class="details">Зарегистрирован: '.$post['regtime'].'</span><br/>'."\n";

	  // Если автор сообщения сейчас "на сайте" 
	  if ( isset( $_SESSION['usersOnLine'] )  ) {
	    if ( isset( $_SESSION['usersOnLine'][$post['id_author']] ) ) 
	      $html = $html.'<span class="details">Просматривает форум</span><br/>'."\n";
        else
	      $html = $html.'<span class="details">Покинул форум</span><br/>'."\n";
      }
	  // Если пользователь заблокирован
	  if ( $post['blocked'] ) 
	    $html = $html.'<span class="userLocked">[Заблокирован]</span><br/>'."\n";
	   
	} else { // Если автор сообщения - незарегистрированный пользователь
	  $html = $html.'<img src="./images/null.gif" alt="" width="100" height="100" 
		      style="border:1px solid #CCCCCC" /><br/>'."\n";	
	}
	
	$html = $html.'<br/><span class="quoteAuthor" onClick=quoteSelection(\''.$post['author'].'\');
            onMouseOver="catchSelection(); this.className=\'quoteAuthorOver\'" 
            onMouseOut="this.className=\'quoteAuthor\'">Цитировать</span>';
	
	$html = $html.'</td>'."\n";
	$html = $html.'<td colspan="2">'."\n"; 
	$html = $html.print_page( $post['name'] )."\n";
	// Если есть прикреплённый файл - формируем ссылку на него
    if( !empty( $post['putfile'] ) and is_file( './files/'.$post['putfile'] ) ) {
      $html = $html.'<div align="right"><img src="./images/file.gif" alt="Открыть файл" 
	          title="Открыть файл" align="absmiddle" />&nbsp;<a target="_blank" 
			  href="./files/'.$post['putfile'].'">'.
              ( getFileSize( './files/'.$post['putfile'] ) ).' Кб</a></div>'."\n";
    }
    if ( !empty( $post['signature'] ) ) {
	  $html = $html.'<br/><br/><hr>'."\n".'<div class="details">'.$post['signature'].'</div>'."\n";
	}
	$html = $html.'</td>'."\n";
	  
	$html = $html.'</tr>'."\n";
	$html = $html.'<tr class="postBottom">'."\n";
	$html = $html.'<td><a class="navigation" href="#top">Наверх</a></td>'."\n";
	// Если автор сообщения (поста) - зарегистрированный пользователь
	if ( $post['id_author'] ) {
	  $html = $html.'<td>'."\n";
	  $html = $html.'&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?action=showUserInfo&idUser='.
		      $post['id_author'].'"><img src="./images/icon_profile.gif"
			  alt="Посмотреть профиль" title="Посмотреть профиль" /></a>';
	  $html = $html.'&nbsp;&nbsp;'."\n";
	  if ( isset( $_SESSION['user'] ) ) {
	    $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=sendMailForm&idUser='.
		        $post['id_author'].'"><img src="./images/icon_email.gif"
			    alt="Написать письмо" title="Написать письмо" /></a>';
	    $html = $html.'&nbsp;&nbsp;'."\n";
	    $html = $html.'<a href="'.$_SERVER['PHP_SELF'].'?action=sendMsgForm&idUser='.
		        $post['id_author'].'"><img src="./images/icon_pm.gif" 
		        alt="Личное сообщение" title="Личное сообщение" /></a>';
	    $html = $html.'&nbsp;&nbsp;'."\n";
      }
	  if ( !empty( $post['url'] ) ) {
	  $html = $html.'<a href="'.$post['url'].'" target="_blank"><img src="./images/icon_www.gif"
			  alt="Сайт автора" title="Сайт автора" /></a>';
      }
	  $html = $html.'</td>'."\n";	  
	} else {
	  $html = $html.'<td><span class="details"><img src="./images/null.gif" alt="" width="1" 
	          height="20" align="absmiddle" />Незарегистрированный пользователь</span></td>'."\n";
	}
	// Если сообщение редактировалось...
	if ( !empty( $post['editor'] ) ) {
	  $html = $html.'<td align="right">';
	  if ( $post['id_author'] == $post['id_editor'] ) {
	    $html = $html.'<span class="editedByUser">Отредактировано автором '.$post['edittime'].'</span>'."\n";
      } else { 
	    if ( $post['editor_status'] == 'admin' )
	      $html = $html.'<span class="editedByAdmin">Отредактировано администратором '.
		          $post['editor'].' '.$post['edittime'].'</span>'."\n";
	    if ( $post['editor_status'] == 'moderator' )
	      $html = $html.'<span class="editedByModer">Отредактировано модератором '.
		          $post['editor'].' '.$post['edittime'].'</span>'."\n";
	   if ( $post['editor_status'] == 'user' )
           $html = $html.'<span class="editedByUser">Отредактировано '.
		           $post['editor'].' '.$post['edittime'].'</span>'."\n";
      }	  
	  $html = $html.'</td>'."\n";
	} else {
	  $html = $html.'<td>&nbsp;</td>'."\n";
	}
	$html = $html.'</tr>'."\n";
	$html = $html.'</table>'."\n";	
  }
  
  // Постраничная навигация и ссылка "Ответить"
  $html = $html.$pagesAddPost;
  
  // Если тема не закрыта - выводим форму для быстрого ответа
  if ( $locked == 0 ) $html = $html.getQuickReplyForm( $id_theme );
  
  // Если страницу темы запросил зарегистрированный пользователь, значит он ее просмотрит
  if ( isset( $_SESSION['user'] ) and isset( $_SESSION['newThemes'] ) ) {
    if ( count( $_SESSION['newThemes'] ) > 0 ) {
	  if ( in_array( $id_theme, $_SESSION['newThemes'] ) ) {		
	    unset( $_SESSION['newThemes'][$id_theme] );
	  }
	} else {
	  unset( $_SESSION['newThemes'] );  
	}
  }
  
  return $html;
}

// Функция возвращает форму для добавления нового форума
function getAddForumForm()
{
  // Если форум пытается создать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Проверяем, иммет ли право этот пользователь создавать форумы
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  $html = '';
  
  $action = $_SERVER['PHP_SELF'].'?action=addForum';
  $title = '';
  $description = '';
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['addForumForm'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['addForumForm']['error'], $info );
	$html = $html.$info."\n";
	$title  = htmlspecialchars( $_SESSION['addForumForm']['title'] );
	$description = htmlspecialchars( $_SESSION['addForumForm']['description'] );
	unset( $_SESSION['addForumForm'] );
  }
  
  // Считываем в переменную содержимое файла, 
  // содержащего форму для добавления форума
  $tpl = file_get_contents( './templates/addForumForm.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{title}', '', $tpl );
  $tpl = str_replace( '{description}', '', $tpl );
  
  $html = $html . $tpl;
  
  return $html;
}

// Функция добавляет новый форум (новую запись в таблицу БД TABLE_FORUMS)
function addForum()
{
  // Если форум пытается создать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) )  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Проверяем, имеет ли право этот пользователь создавать форумы
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Если не переданы данные формы - значит функция была вызвана по ошибке
  if ( !isset( $_POST['title'] ) or
       !isset( $_POST['description'] ) )
  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $title  = substr( $_POST['title'], 0, 120 );
  $description = substr( $_POST['description'], 0, 250 );
  // Обрезаем лишние пробелы
  $title  = trim( $title );
  $description = trim( $description );
  
  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $title ) ) $error = $error.'<li>не заполнено поле "Название форума"</li>'."\n";
  if ( empty( $description ) ) $error = $error.'<li>не заполнено поле "Описание"</li>'."\n";
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $title ) and !preg_match( "#^[-.;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $title ) )
    $error = $error.'<li>поле "Название форума" содержит недопустимые символы</li>'."\n";
  if ( !empty( $description ) and !preg_match( "#^[-.;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $description ) )
    $error = $error.'<li>поле "Описание" содержит недопустимые символы</li>'."\n";
	
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя для исправления ошибок
  if ( !empty( $error ) ) {
	$_SESSION['addForumForm'] = array();
	$_SESSION['addForumForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['addForumForm']['title'] = $title;
	$_SESSION['addForumForm']['description'] = $description;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=addForumForm' );
	die();
  }
  // Порядок следования - новый форум будет в конце списка
  $query = "SELECT IFNULL(MAX(pos), 0) FROM ".TABLE_FORUMS;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении нового форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  $order = mysql_result( $res, 0, 0 ) + 1;
  $query = "INSERT INTO ".TABLE_FORUMS."
            (
			name,
			description,
			pos
			)
			VALUES
			(
			'".mysql_real_escape_string( $title )."',
			'".mysql_real_escape_string( $description )."',
			".$order."
			)";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении нового форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	  $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	  '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  
  return showInfoMessage( 'Новый форум успешно добавлен', '' );
}

// Функция возвращает форму для редактирования форума;
// уникальный ID форума передается методом GET
function getEditForumForm()
{
  // Если форум пытается редактировать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) )  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Проверяем, имеет ли право этот пользователь редактировать форум
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID форума - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  } 
  
  $html = '';
  
  // Получаем из БД информацию о форуме
  $query = "SELECT name, description FROM ".TABLE_FORUMS." WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для редактирования форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	  $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	  '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  $forum = mysql_fetch_array( $res );
  $action = $_SERVER['PHP_SELF'].'?action=updateForum&idForum='.$_GET['idForum'];
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['editForumForm'] ) ) {	
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['editForumForm']['error'], $info );
	$html = $html.$info."\n";	
	$title  = htmlspecialchars( $_SESSION['editForumForm']['title'] );
	$description = htmlspecialchars( $_SESSION['editForumForm']['description'] );
	unset( $_SESSION['editForumForm'] );
  } else {
    $title = htmlspecialchars( $forum['name'] );
    $description = htmlspecialchars( $forum['description'] );
  }
  // Считываем в переменную содержимое файла, 
  // содержащего форму для редактирования форума
  $tpl = file_get_contents( './templates/editForumForm.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{title}', $title, $tpl );
  $tpl = str_replace( '{description}', $description, $tpl );
  
  $html = $html . $tpl;
  
  return $html;
}

// Функция обновляет информацию о форуме (запись в таблице БД TABLE_FORUMS);
// уникальный ID форума передается методом GET
function updateForum()
{
  // Если форум пытается редактировать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) )  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Проверяем, имеет ли право этот пользователь редактировать форум
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  } 
  // Если не передан ID форума - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }

  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $title  = substr( $_POST['title'], 0, 120 );
  $description = substr( $_POST['description'], 0, 250 );
  // Обрезаем лишние пробелы
  $title  = trim( $title );
  $description = trim( $description );
  
  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $title ) ) $error = $error.'<li>не заполнено поле "Название форума"</li>'."\n";
  if ( empty( $description ) ) $error = $error.'<li>не заполнено поле "Описание"</li>'."\n";
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $title ) and !preg_match( "#^[-.;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $title ) )
    $error = $error.'<li>поле "Название форума" содержит недопустимые символы</li>'."\n";
  if ( !empty( $description ) and !preg_match( "#^[-.;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $description ) )
    $error = $error.'<li>поле "Описание" содержит недопустимые символы</li>'."\n";
	
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя для исправления ошибок
  if ( !empty( $error ) )
  {
	$_SESSION['editForumForm'] = array();
	$_SESSION['editForumForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['editForumForm']['title'] = $title;
	$_SESSION['editForumForm']['description'] = $description;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=editForumForm&idForum='.$_GET['idForum'] );
	die();
  }
  // Все поля заполнены правильно - выполняем запрос
  $query = "UPDATE ".TABLE_FORUMS." 
            SET 
			name='".mysql_real_escape_string( $title )."', 
			description='".mysql_real_escape_string( $description )."'
			WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при обновлении форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	      '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  } 
    
  return showInfoMessage( 'Обновление форума прошло успешно', '' ); 
}

function forumUp()
{
  // Если форум пытается редактировать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) )  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Проверяем, имеет ли право этот пользователь редактировать форум
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  } 
  // Если не передан ID форума - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Форум, который "поднимается" вверх
  $id_forum_up = $_GET['idForum'];
  $query = "SELECT pos FROM ".TABLE_FORUMS." WHERE id_forum=".$id_forum_up;
  $res = mysql_query( $query );
  // Порядок следования форума, который "поднимается" вверх
  $order_up = mysql_result( $res, 0, 0 );
  $query = "SELECT id_forum, pos  
            FROM ".TABLE_FORUMS." 
			WHERE pos<".$order_up." 
			ORDER BY pos DESC LIMIT 1";	
  $res = mysql_query( $query );
  // Если форум, который "поднимается" вверх и так выше всех (первый в списке)
  if ( mysql_num_rows( $res ) == 0 ) return true;
  // Порядок следования и ID форума, который находится выше и будет "опущен" вниз
  // ( поменявшись местами с форумом, который "поднимается" вверх )
  list( $id_forum_down, $order_down ) = mysql_fetch_array( $res );
  // Меняем местами форумы
  $query1 = "UPDATE ".TABLE_FORUMS." SET pos=".$order_down." WHERE id_forum=".$id_forum_up;
  $res1 = mysql_query( $query1 );
  $query2 = "UPDATE ".TABLE_FORUMS." SET pos=".$order_up." WHERE id_forum=".$id_forum_down;
  $res2 = mysql_query( $query2 );
  if ( $res1 and $res2 )
    return showInfoMessage( 'Операция прошла успешно', '' );
  else
    return showInfoMessage( 'Ошибка при выполнении операции', '' );
}

function forumDown()
{
  // Если форум пытается редактировать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) )  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Проверяем, имеет ли право этот пользователь редактировать форум
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  } 
  // Если не передан ID форума - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Форум, который "опускается" вниз
  $id_forum_down = $_GET['idForum'];
  $query = "SELECT pos FROM ".TABLE_FORUMS." WHERE id_forum=".$id_forum_down;
  $res = mysql_query( $query );
  // Порядок следования форума, который "опускается" вниз
  $order_down = mysql_result( $res, 0, 0 );
  $query = "SELECT id_forum, pos  
            FROM ".TABLE_FORUMS." 
			WHERE pos>".$order_down." 
			ORDER BY pos LIMIT 1";
  $res = mysql_query( $query );
  // Если форум, который "опускается" вниз и так ниже всех (последний в списке)
  if ( mysql_num_rows( $res ) == 0 ) return true;
  // Порядок следования и ID форума, который находится ниже и будет "поднят" вверх
  // ( поменявшись местами с форумом, который "опускается" вниз )
  list( $id_forum_up, $order_up ) = mysql_fetch_array( $res );
  // Меняем местами форумы
  $query1 = "UPDATE ".TABLE_FORUMS." SET pos=".$order_down." WHERE id_forum=".$id_forum_up;
  $res1 = mysql_query( $query1 );
  $query2 = "UPDATE ".TABLE_FORUMS." SET pos=".$order_up." WHERE id_forum=".$id_forum_down;
  $res2 = mysql_query( $query2 );
  if ( $res1 and $res2 )
    return showInfoMessage( 'Операция прошла успешно', '' );
  else
    return showInfoMessage( 'Ошибка при выполнении операции', '' );
}

// Функция удаляет форум (запись в таблице TABLE_FORUMS)
function deleteForum()
{
  // Не зарегистрированный пользователь не может добавить тему
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Форум может удалить только администратор
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();  
  }
  // Если не передан ID форума - значит функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Можно удалить только форум, который не содержит тем (в целях безопасности)
  $query = "SELECT COUNT(*) FROM ".TABLE_THEMES." WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении форума';
    $err = 'Ошибка при выполнении запроса: <br/>'.
           $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
           '(Файл '. __FILE__ .', строка '. __LINE__ .')';
    return showErrorMessage( $msg, $err, true, '' );    	  
  } 
  if ( mysql_result( $res, 0, 0 ) > 0 )
    return showInfoMessage( 'Нельзя удалить форум, который содержит темы', '' );
  else
	  
  // Теперь удаляем форум
  $query = "DELETE FROM ".TABLE_FORUMS." WHERE id_forum=".$_GET['idForum'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении форума';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	        $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	        '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  
    return showInfoMessage( 'Форум успешно удален', '' );  
}


// Функция возвращает форму для добавления новой темы; 
// ID форума, куда добавляется тема передается методом GET
function getAddThemeForm()
{
  // На зарегистрированный пользователь не может добавить тему
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();  
  }
  // Если не передан ID форума, куда будет добавлена тема - 
  // значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  
  $html = '';
  $theme   = '';
  $message = '';

  if ( isset( $_SESSION['viewMessage'] ) and !empty( $_SESSION['viewMessage']['message'] ) ) {
    $view = file_get_contents( './templates/previewMessage.html' );
    $view = str_replace( '{message}', print_page( $_SESSION['viewMessage']['message'] ), $view ); 
    $html = $html.$view."\n";
    $theme   = htmlspecialchars( $_SESSION['viewMessage']['theme'] );
    $message = htmlspecialchars( $_SESSION['viewMessage']['message'] );
    unset( $_SESSION['viewMessage'] );
  }
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['addThemeForm'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
    $info = str_replace( '{infoMessage}', $_SESSION['addThemeForm']['error'], $info );
    $html = $html.$info."\n";
    $theme   = htmlspecialchars( $_SESSION['addThemeForm']['theme'] );
    $message = htmlspecialchars( $_SESSION['addThemeForm']['message'] );
    unset( $_SESSION['addThemeForm'] );
  }

  $action = $_SERVER['PHP_SELF'].'?action=addTheme&idForum='.$_GET['idForum'];
  $html = $html.file_get_contents( './templates/addThemeForm.html' ); 
  $html = str_replace( '{action}', $action, $html );
  $html = str_replace( '{theme}', $theme, $html );
  $html = str_replace( '{message}', $message, $html );
  return $html;
}

// Функция добавляет новую тему (новую запись в таблицу БД TABLE_THEMES)
function addTheme()
{
  // На зарегистрированный пользователь не может добавить тему
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();  
  }
  // Если не переданы данные формы - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) or
	   !isset( $_POST['theme'] ) or
	   !isset( $_POST['message'] ) or
	   !isset( $_FILES['attach'] ) )
  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  
  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $theme   = substr( $_POST['theme'], 0, 128 );
  $message = substr( $_POST['message'], 0, MAX_POST_LENGTH );
  // Обрезаем лишние пробелы
  $theme   = trim( $theme );
  $message = trim( $message );

  // Если пользователь хочет посмотреть на сообщение перед отправкой
  if ( isset( $_POST['viewMessage'] ) ) {
    $_SESSION['viewMessage']['theme'] = $theme;
	$_SESSION['viewMessage']['message'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=addThemeForm&idForum='.$_GET['idForum'] );
	die();
  }
  
  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $theme ) ) $error = $error.'<li>не заполнено поле "Тема"</li>'."\n";
  if ( empty( $message ) ) $error = $error.'<li>не заполнено поле "Сообщение"</li>'."\n";
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $theme ) and !preg_match( "#^[-.;:,?!\/)=(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $theme ) )
	$error = $error.'<li>поле "Тема" содержит недопустимые символы</li>'."\n";
  if ( $_FILES['attach']['size'] > MAX_FILE_SIZE )
    $error = $error.'<li>Размер файла больше '.(MAX_FILE_SIZE/1024).' Кб</li>'."\n";
	
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя для исправления ошибок
  if ( !empty( $error ) )
  {
	$_SESSION['addThemeForm'] = array();
	$_SESSION['addThemeForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	                                     "\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['addThemeForm']['theme'] = $theme;
	$_SESSION['addThemeForm']['message'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=addThemeForm&idForum='.$_GET['idForum'] );
	die();
  }
  // Формируем SQL-запрос на добавление темы
  $query_theme = "INSERT INTO ".TABLE_THEMES." 
                  VALUES
				  (
                  NULL,
                  '".mysql_real_escape_string( $theme )."',
                  '".mysql_real_escape_string( $_SESSION['user']['name'] )."',
                  ".$_SESSION['user']['id_author'].",
                  NOW(),
				  ".$_SESSION['user']['id_author'].",
				  '".mysql_real_escape_string( $_SESSION['user']['name'] )."',
				  NOW(),
                  ".$_GET['idForum'].",
				  0
		          )";
  $res = mysql_query( $query_theme );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении новой темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query_theme.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  // Выясняем первичный ключ только что добавленной записи -
  // это понадобится для добавления сообщения (поста) и файла
  $id_theme = mysql_insert_id();
 
  // Если поле выбора файла(рисунка) не пустое
  $file = '';
  if ( !empty($_FILES['attach']['name']) ) {
    // Проверяем не больше ли файл максимально допустимого размера
    if ( $_FILES['attach']['size'] <= MAX_FILE_SIZE ) { 
      // Проверяем, не является ли файл скриптом PHP или Perl, html; 
	  // если это так преобразуем его в формат .txt
      $extentions = array(".php",".phtml",".php3",".html",".htm",".pl");
      // Извлекаем из имени файла расширение
      $ext = strrchr( $_FILES['attach']['name'], "." ); 
      // Формируем путь к файлу    
      if ( in_array( $ext, $extentions ) )
        $file = $id_theme.'-'.date("YmdHis",time()).'.txt'; 
      else
        $file = $id_theme.'-'.date("YmdHis",time()).$ext; 
      // Перемещаем файл из временной директории сервера в
      // директорию /files Web-приложения
      if ( move_uploaded_file ( $_FILES['attach']['tmp_name'], './files/'.$file ) )
	    chmod( './files/'.$file, 0644 );
	}
  }

  // Формируем SQL-запрос на добавление сообщения
  $query = "INSERT INTO ".TABLE_POSTS." 
            VALUES
            (
            NULL,
            '".mysql_real_escape_string( $message )."',
            '".$file."',
            '".mysql_real_escape_string( $_SESSION['user']['name'] )."',
            ".$_SESSION['user']['id_author'].",
            NOW(),
			NOW(),
            0,
            ".$id_theme.",
			0
			)";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении новой темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  // Обновляем число оставленных сообщений и созданных тем
  $q = "UPDATE ".TABLE_USERS." SET themes=themes+1, posts=posts+1
        WHERE id_author = ".$_SESSION['user']['id_author'];
  mysql_query( $q );
	
  return showInfoMessage( 'Новая тема успешно добавлена', 
                          'action=showForum&idForum='.$_GET['idForum'] );
}

// Функция возвращает форму для редактирования темы; 
// ID форума и темы передаются методом GET
function getEditThemeForm()
{
  // Если не передан ID форума - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID темы - значит функция была вызвана по ошибке
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Получаем из БД информацию о редактируемой теме
  $query = "SELECT name, author, id_forum FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для редактирования темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	  $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	  '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  } 
  if ( mysql_num_rows( $res ) == 0 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'action=showForum&idForum='.$_GET['idForum'] );
	die();
  }
  $theme = mysql_fetch_array( $res );
  $_GET['idForum'] = $theme['id_forum'];
  
  $html = '';
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['editThemeForm'] ) ) {
	$html = $html.$_SESSION['editThemeForm']['error'];
	$name = htmlspecialchars( $_SESSION['editThemeForm']['name'] );
	unset( $_SESSION['editThemeForm'] );
  } else {
	$name = htmlspecialchars( $theme['name'] );
  }
  
  // Формируем список форумов, чтобы можно было переместить тему в другой форум
  $query = "SELECT id_forum, name FROM ".TABLE_FORUMS." WHERE 1 ORDER BY pos";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для редактирования темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  $options = '';
  while ( $forum = mysql_fetch_array( $res ) ) {
    if ( $forum['id_forum'] == $theme['id_forum'] )
	  $options = $options.'<option value="'.$forum['id_forum'].'" selected>'.$forum['name'].'</option>'."\n";
	else
	  $options = $options.'<option value="'.$forum['id_forum'].'">'.$forum['name'].'</option>'."\n";
  }
  
  $action = $_SERVER['PHP_SELF'].'?action=updateTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme;
  
  // Считываем в переменную файл шаблона, содержащего форму для редактирования темы
  $tpl = file_get_contents( './templates/editThemeForm.html' );
  
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{name}', htmlspecialchars( $theme['name'] ), $tpl );
  $tpl = str_replace( '{author}', htmlspecialchars( $theme['author'] ), $tpl );
  $tpl = str_replace( '{options}', $options, $tpl );
  
  $html = $html. $tpl;
  
  return $html;
}

// Функция обновляет информацию о теме (запись в таблице БД TABLE_THEMES);
// уникальный ID темы передается методом GET
function updateTheme()
{
  // Если не переданы данные формы - функция вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) or
       !isset( $_GET['id_theme'] ) or
	   !isset( $_POST['id_forum'] ) or
       !isset( $_POST['name'] ) )
  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.$_GET['idForum'] );
	die();
  }
  $id_forum = (int)$_POST['id_forum'];
  if ( $id_forum < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.$_GET['idForum'] );
	die();
  }
  
  // Если тему пытается редактировать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'action=showForum&idForum='.$_GET['idForum'] );
	die();
  }

  // Проверяем, имеет ли право этот пользователь редактировать тему
  if ( $_SESSION['user']['status'] == 'user' ) {
    $msg = 'У вас нет прав для редактирования темы';
    return showInfoMessage( $msg, 'action=showForum&idForum='.$_GET['idForum'] );
  }

  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $name   = substr( $_POST['name'], 0, 128 );
  // Обрезаем лишние пробелы
  $name   = trim( $name );

  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $name ) ) $error = $error.'<li>не заполнено поле "Тема"</li>'."\n";
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $name ) and !preg_match( "#^[-.;:,?!/)=(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $name ) )
	$error = $error.'<li>поле "Тема" содержит недопустимые символы</li>'."\n";
	
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем пользователя для исправления ошибок
  if ( !empty( $error ) )
  {
	$_SESSION['editThemeForm'] = array();
	$_SESSION['editThemeForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'."\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['editThemeForm']['name'] = $name;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=editThemeForm&idForum='.
	        $_GET['idForum'].'&id_theme='.$id_theme );
	die();
  }
  
  // Если тема перемещается в другой форум, мы
  // должны проверить, что этот форум существует
  $tmp = '';
  if ( $id_forum != $_GET['idForum'] ) {
    $query = "SELECT id_forum FROM ".TABLE_FORUMS." WHERE 1";
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при обновлении темы';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
    }
    while ( $id = mysql_fetch_row( $res ) ) $ids[] = $id[0];
    if ( !in_array( $id_forum, $ids ) ) 
	  return showInfoMessage( 'Ошибка при обновлении темы', 'action=showForum&idForum='.$_GET['idForum'] );
	else
	  $tmp = ', id_forum='.$id_forum;
  }
  
  // Запрос на обновление темы
  $query = "UPDATE ".TABLE_THEMES." 
            SET name='".mysql_real_escape_string( $name )."'".$tmp." 
			WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );			
  if ( !$res ) {
    $msg = 'Ошибка при обновлении темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  } else {
    return showInfoMessage( 'Обновление темы прошло успешно', 'action=showForum&idForum='.$_GET['idForum'] );
  } 
}

// Функция удаляет тему; ID темы передается методом GET
function deleteTheme()
{
  // Если тему пытается удалить не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Только администратор или модератор может удалить тему
  if ( $_SESSION['user']['status'] == 'user' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID форума - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID темы, которую надо удалить
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Выдаем пользователю сообщение с просьбой подтвердить свое
  // желание удалить тему
  if ( !isset( $_GET['confirm'] ) ) {
    $html = '<div align="center"><p>Вы действительно хотите удалить эту тему?</p>'."\n";
    $html = $html.'<input type="button" name="yes" value="Да" 
            onClick="document.location.href=\''.$_SERVER['PHP_SELF'].'?action=deleteTheme&idForum='.
		    $_GET['idForum'].'&id_theme='.$id_theme.'&confirm=yes\'" />&nbsp;&nbsp;'."\n";
    $html = $html.'<input type="button" name="no" value="Нет" 
            onClick="document.location.href=\''.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.
		    $_GET['idForum'].'\'" /></div>'."\n";
    $tpl = file_get_contents( './templates/infoMessage.html' );
    $tpl = str_replace( '{infoMessage}', $html, $tpl );
    return $tpl; 
  }
  
  // Это небольшой код для удаленния коллизий в БД;
  // каким-то образом во время тестирования форума
  // у меня появилось несколько тем, в которых не
  // было сообщений (постов)
  // Вообще, надо подумать об использовании InnoDB и Foreign Key
  $q = "DELETE FROM ".TABLE_THEMES." WHERE id_theme NOT IN (SELECT DISTINCT id_theme FROM ".TABLE_POSTS.")";
  mysql_query( $q );
  $q = "DELETE FROM ".TABLE_POSTS." WHERE id_theme NOT IN (SELECT id_theme FROM ".TABLE_THEMES.")";
  mysql_query( $q );
  
  // Сперва мы должны удалить все сообщения (посты) темы;
  // начнем с того, что удалим файлы вложений
  $query = "SELECT putfile, id_author FROM ".TABLE_POSTS."
            WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	        $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	        '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  if ( mysql_num_rows( $res ) > 0 ) {
    while( $file = mysql_fetch_row( $res ) ) {
	  if ( !empty( $file[0] ) and is_file( './files/'.$file[0] ) ) unlink( is_file( './files/'.$file[0] ) );
	  // заодно обновляем таблицу TABLE_USERS - надо обновить поле posts (кол-во сообщений)
	  if ( $file[1] ) { 
	    // Здесь надо будет переделать - выполнять только один запрос
		// UPDATE users SET posts=posts-1 WHERE id_author IN (3, 5, 12);
	    $q = "UPDATE ".TABLE_USERS." SET posts=posts-1 WHERE id_author=".$file[1];
	    $r = mysql_query( $q );
      }
	}
  }
  
  // Продолжаем - удаляем сообщения (посты)
  $query = "DELETE FROM ".TABLE_POSTS." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	        $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	        '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }

  // Обновляем таблицу TABLE_USERS - надо обновить поле themes
  $query = "UPDATE ".TABLE_USERS." SET themes=themes-1 
            WHERE id_author=(SELECT id_author FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme.")";
  mysql_query( $query );
  
  // Теперь удаляем тему (запись в таблице TABLE_THEMES)
  $query = "DELETE FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении темы';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	        $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	        '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  
  return showInfoMessage( 'Тема удалена', 
                          'action=showForum&idForum='.$_GET['idForum'] );
}

// Закрыть тему
function lockTheme()
{
  // Если тему пытается закрыть не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Только администратор или модератор может закрыть тему
  if ( $_SESSION['user']['status'] == 'user' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID форума - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID темы, которую надо закрыть
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  } 
  
  // Сначала заблокируем сообщения (посты) темы
  $query = "UPDATE ".TABLE_POSTS." SET locked=1 WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при попытке заблокировать тему';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  } 
  // Теперь заблокируем тему
  $query = "UPDATE ".TABLE_THEMES." SET locked=1 WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при попытке заблокировать тему';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
 
  return showInfoMessage( 'Тема закрыта', 
                          'action=showForum&idForum='.$_GET['idForum'] );
}

// Открыть тему
function unlockTheme()
{
  // Если тему пытается разблокировать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Только администратор или модератор может разблокировать тему
  if ( $_SESSION['user']['status'] == 'user' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID форума - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID темы, которую надо разблокировать
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Сначала разблокируем сообщения (посты) темы
  $query = "UPDATE ".TABLE_POSTS." SET locked=0 WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при попытке разблокировать тему';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  } 
  // Теперь разблокируем тему
  $query = "UPDATE ".TABLE_THEMES." SET locked=0 WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при попытке разблокировать тему';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
 
  return showInfoMessage( 'Тема открыта', 
                          'action=showForum&idForum='.$_GET['idForum'] ); 
}

// Функция возвращает форму для добавления нового сообщения (поста)
function getAddPostForm()
{
  // Если не передан ID форума - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID темы, куда будет добавлено сообщение - 
  // значит функция была вызвана по ошибке
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Проверяем, не заблокирована ли тема?
  $query = "SELECT locked FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для добавления нового сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.$_GET['idForum'] );
	die();
  }
  if ( mysql_result( $res, 0, 0 ) == 1 )
    return showInfoMessage( 'Вы не можете добавить сообщение - тема заблокирована.', 
	                        'action=showForum&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );  
  
  $message = '';
  $html = '';
  
  if ( isset( $_SESSION['viewMessage'] ) and !empty( $_SESSION['viewMessage'] ) ) {
    $view = file_get_contents( './templates/previewMessage.html' );
	$view = str_replace( '{message}', print_page( $_SESSION['viewMessage'] ), $view ); 
	$html = $html.$view."\n";
	$message = htmlspecialchars( $_SESSION['viewMessage'] );
	unset( $_SESSION['viewMessage'] );
  }
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['addPostForm'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['addPostForm']['error'], $info );
	$html = $html.$info."\n";
    $message = htmlspecialchars( $_SESSION['addPostForm']['message'] );
    unset( $_SESSION['addPostForm'] );
  }
  
  $tpl = file_get_contents( './templates/addPostForm.html' );
  $action = $_SERVER['PHP_SELF'].'?action=addPost&idForum='.$_GET['idForum'].'&id_theme='.$id_theme;
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{message}', $message, $tpl );
  
  $html = $html . $tpl;
  
  return $html."\n";
}

// Функция добавляет новое сообщение(пост) (новую запись в таблицу БД TABLE_POSTS)
function addPost()
{
  
  // Если не переданы данные формы - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) or
       !isset( $_GET['id_theme'] ) or
       !isset( $_POST['message'] ) or
       !isset( $_FILES['attach'] ) 
	   )
  {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Проверяем, не заблокирована ли тема?
  $query = "SELECT locked FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для добавления нового сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.$_GET['idForum'] );
	die();
  }
  if ( mysql_result( $res, 0, 0 ) == 1 )
    return showInfoMessage( 'Вы не можете добавить сообщение - тема заблокирована.', 
	                        'action=showForum&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );

  $msgLen = strlen( $_POST['message'] );
							
  // Обрезаем сообщение (пост) до длины MAX_POST_LENGTH
  $message = substr( $_POST['message'], 0, MAX_POST_LENGTH );
  // Обрезаем лишние пробелы
  $message = trim( $message );
  // Если пользователь хочет посмотреть на сообщение перед отправкой
  if ( isset( $_POST['viewMessage'] ) ) 
  {
	$_SESSION['viewMessage'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=addPostForm&idForum='.
	        $_GET['idForum'].'&id_theme='.$id_theme );
	die();
  }

  // Проверяем, правильно ли заполнены поля формы
  $error = '';
  if ( empty( $message ) ) $error = $error.'<li>не заполнено поле "Сообщение"</li>'."\n";
  if ( $msgLen > MAX_POST_LENGTH ) 
    $error = $error.'<li>длина сообщения больше '.MAX_POST_LENGTH.' символов</li>'."\n";
  if ( !empty( $_FILES['attach']['name'] ) and $_FILES['attach']['size'] > MAX_FILE_SIZE ) 
    $error = $error.'<li>размер файла вложения больше '.(MAX_FILE_SIZE/1024).' Кб</li>'."\n";
	
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем пользователя для исправления ошибок
  if ( !empty( $error ) )
  {
	$_SESSION['addPostForm'] = array();
	$_SESSION['addPostForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'."\n".
    '<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['addPostForm']['message'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=addPostForm&idForum='.
	        $_GET['idForum'].'&id_theme='.$id_theme );
	die();
  }
  $file = '';
  if ( !empty( $_FILES['attach']['name'] ) ) {
    // Массив недопустимых расширений файла вложения
    $extentions = array('.php', '.phtml', '.php3', '.html', '.htm', '.pl');
    // Извлекаем из имени файла расширение
    $ext = strrchr( $_FILES['attach']['name'], "." ); 
    // Формируем путь к файлу    
    if ( in_array( $ext, $extentions ) )
      $file = $id_theme.'-'.date("YmdHis",time()).'.txt'; 
    else
      $file = $id_theme.'-'.date("YmdHis",time()).$ext; 
    // Перемещаем файл из временной директории сервера в директорию files
      if ( move_uploaded_file ( $_FILES['attach']['tmp_name'], './files/'.$file ) )
	    chmod( './files/'.$file, 0644 );
  }
  
  if ( isset( $_SESSION['user'] ) ) {
    $name = $_SESSION['user']['name'];
	$id_user = $_SESSION['user']['id_author'];
  } else {
    $name = NOT_REGISTERED_USER;
    $id_user = 0;
  }

  // Защита от того, чтобы один пользователь не добавил 
  // 100 сообщений за одну минуту
  if ( isset( $_SESSION['unix_last_post'] ) and ( time()-$_SESSION['unix_last_post'] < 10 ) ) {
    return showInfoMessage( 'Ваше сообщение уже было добавлено', 
                            'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
 
  // Все поля заполнены правильно - выполняем запрос к БД
  $query = "INSERT INTO ".TABLE_POSTS."
            (
			name,
			putfile,
			author,
			id_author,
			time,
			id_theme
			)
			VALUES
			(
			'".mysql_real_escape_string( $message )."',
			'".$file."',
			'".mysql_real_escape_string( $name )."',
			".$id_user.",
			NOW(),
			".$id_theme."
			)";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении нового сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
  $query = "UPDATE ".TABLE_THEMES." 
            SET 
			id_last_author=".$id_user.",
			last_author='".mysql_real_escape_string( $name )."',
			last_post=NOW()
			WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении нового сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
  
  // Добавляем в массив $_SESSION	время последнего сообщения;
  // Это нужно для того, чтобы один пользователь не добавил 
  // 100 сообщений за одну минуту
  $_SESSION['unix_last_post'] = time();
  
  // Обновляем количество сообщений для зарегистрированного пользователя
  if ( isset( $_SESSION['user'] ) ) {
	$query = "UPDATE ".TABLE_USERS." SET posts=posts+1 WHERE id_author = ".$_SESSION['user']['id_author'];
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при добавлении нового сообщения';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 
	                           'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
    }
  }

  return showInfoMessage( 'Ваше сообщение успешно добавлено', 
                          'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
}

// Функция добавляет новое сообщение(пост) (новую запись в таблицу БД TABLE_POSTS)
function quickReply()
{
  // Если не переданы данные формы - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) or
       !isset( $_GET['id_theme'] ) or
       !isset( $_POST['message'] )
	   )
  {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  // Проверяем, не заблокирована ли тема?
  $query = "SELECT locked FROM ".TABLE_THEMES." WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для добавления нового сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showForum&idForum='.$_GET['idForum'] );
	die();
  }
  if ( mysql_result( $res, 0, 0 ) == 1 )
    return showInfoMessage( 'Вы не можете добавить сообщение - тема заблокирована.', 
	                        'action=showForum&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );

  if ( isset( $_SESSION['user'] ) ) {
    $name = $_SESSION['user']['name'];
	$id_user = $_SESSION['user']['id_author'];
  } else {
    $name = NOT_REGISTERED_USER;
    $id_user = 0;
  }
  // Обрезаем сообщение (пост) до длины MAX_POST_LENGTH
  $message = substr( $_POST['message'], 0, MAX_POST_LENGTH );
  // Обрезаем лишние пробелы
  $message = trim( $message );

  // Проверяем, правильно ли заполнены поля формы
  if ( empty( $message ) ) 
	  return showInfoMessage( 'Не заполнено поле "Сообщение"', 
                              'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );

  // Защита от того, чтобы один пользователь не добавил 
  // 100 сообщений за одну минуту
  if ( isset( $_SESSION['unix_last_post'] ) and ( time()-$_SESSION['unix_last_post'] < 10 ) ) {
    return showInfoMessage( 'Ваше сообщение уже было добавлено несколькими секундами ранее', 
                            'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
							  
  // Все поля заполнены правильно - выполняем запрос к БД
  $query = "INSERT INTO ".TABLE_POSTS."
            (
			name,
			putfile,
			author,
			id_author,
			time,
			id_theme
			)
			VALUES
			(
			'".mysql_real_escape_string( $message )."',
			'',
			'".mysql_real_escape_string( $name )."',
			".$id_user.",
			NOW(),
			".$id_theme."
			)";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении нового сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
  $query = "UPDATE ".TABLE_THEMES." 
            SET 
			id_last_author=".$id_user.",
			last_author='".mysql_real_escape_string( $name )."',
			last_post=NOW()
			WHERE id_theme=".$id_theme;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при добавлении нового сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
  
  // Добавляем в массив $_SESSION время последнего сообщения;
  // Это нужно для того, чтобы один пользователь не добавил 
  // 100 сообщений за одну минуту
  $_SESSION['unix_last_post'] = time();
 
  // Обновляем количество сообщений для зарегистрированного пользователя
  if ( isset( $_SESSION['user'] ) ) {
	$query = "UPDATE ".TABLE_USERS." SET posts=posts+1 WHERE id_author = ".$_SESSION['user']['id_author'];
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при добавлении нового сообщения';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 
	                           'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
    }	
  }

  return showInfoMessage( 'Ваше сообщение успешно добавлено', 
                          'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
}

// Функция возвращает форму для редактирования сообщения(поста)
function getEditPostForm()
{
  // Если не передан ID форума - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID темы - значит функция была вызвана по ошибке
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не передан ID сообщения - значит функция была вызвана по ошибке
  if ( !isset( $_GET['id_post'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_post = (int)$_GET['id_post'];
  if ( $id_post < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }

  // Если сообщение пытается редактировать не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }

  // Получаем из БД сообщение
  $query = "SELECT name, putfile, id_author, author, time, id_theme, locked 
            FROM ".TABLE_POSTS." 
			WHERE id_post=".$id_post;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для редактирования сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  // Если сообщение не найдено - редирект на страницу темы
  if ( mysql_num_rows( $res ) == 0 ) 
    return showInfoMessage( 'Ошибка при формировании формы для редактирования сообщения', 
	                        'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
	
  $post = mysql_fetch_array( $res ); 
  $id_theme = $post['id_theme']; 
  
  // Проверяем, имеет ли пользователь право редактировать это сообщение (пост)
  if ( !hasRightEditPost( $post ) ) {
    $msg = 'У вас нет прав для редактирования этого сообщения';
	$queryString = 'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme;
    return showInfoMessage( $msg, $queryString );
  }	
  
  $message = htmlspecialchars( $post['name'] );
  $html = '';
  
  if ( isset( $_SESSION['viewMessage'] ) and !empty( $_SESSION['viewMessage'] ) ) {
    $view = file_get_contents( './templates/previewMessage.html' );
	$view = str_replace( '{message}', print_page( $_SESSION['viewMessage'] ), $view ); 
	$html = $html.$view."\n";
	$message = htmlspecialchars( $_SESSION['viewMessage'] );
	unset( $_SESSION['viewMessage'] );
  }
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['editPostForm'] ) ) {
    $html    = $html . $_SESSION['editPostForm']['error'];
    $message = htmlspecialchars( $_SESSION['editPostForm']['message'] );
    unset( $_SESSION['editPostForm'] );
  }
  
  $tpl = file_get_contents( './templates/editPostForm.html' );

  $action = $_SERVER['PHP_SELF'].'?action=updatePost&idForum='.
            $_GET['idForum'].'&id_theme='.$id_theme.'&id_post='.$id_post;
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{message}', $message, $tpl );
  // Если ранее был загружен файл - надо предоставить возможность удалить его
  $unlinkfile = '';
  if ( !empty( $post['putfile'] ) and is_file( './files/'.$post['putfile'] ) ) {
    $unlinkfile = '<input type="checkbox" name="unlink" value="1" />&nbsp;Удалить загруженный ранее файл<br/>'."\n";
  }
  $tpl = str_replace( '{unlinkfile}', $unlinkfile, $tpl );
  $html = $html . $tpl;
  
  return $html."\n";
}

function updatePost()
{
  // Если не переданы данные формы - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) or
       !isset( $_GET['id_post'] ) or
       !isset( $_POST['message'] ) or
       !isset( $_FILES['attach'] ) 
    )
  {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  $id_post = (int)$_GET['id_post'];
  if ( $id_post < 1 ) {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }

  // Проверяем, имеет ли пользователь право редактировать это сообщение (пост)
  $query = "SELECT * FROM ".TABLE_POSTS." WHERE id_post=".$id_post;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при обновлении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    $msg = 'Ошибка при обновлении сообщения: сообщение не найдено';
    return showInfoMessage( $msg, '' );
  }
  $post = mysql_fetch_array( $res );
  $id_theme = $post['id_theme'];
  if ( !hasRightEditPost( $post ) ) {
    $msg = 'У вас нет прав для редактирования этого сообщения';
	$queryString = 'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme;
    return showInfoMessage( $msg, $queryString );
  }

  // Обрезаем сообщение до длины MAX_POST_LENGTH
  $message = substr( $_POST['message'], 0, MAX_POST_LENGTH );
  // Обрезаем лишние пробелы
  $message = trim( $message );

  // Если пользователь хочет посмотреть на сообщение перед отправкой
  if ( isset( $_POST['viewMessage'] ) ) 
  {
	$_SESSION['viewMessage'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=editPostForm&idForum='.
	        $_GET['idForum'].'&id_theme='.$id_theme.'&id_post='.$id_post );
	die();
  }
  
  // Проверяем, правильно ли заполнены поля формы
  $error = '';
  if ( empty( $message ) ) $error = $error.'<li>не заполнено поле "Сообщение"</li>'."\n";
  if ( !empty( $_FILES['attach']['name'] ) and $_FILES['attach']['size'] > MAX_FILE_SIZE ) 
      $error = $error.'<li>размер файла вложения больше '.(MAX_FILE_SIZE/1024).' Кб</li>'."\n";
	
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя для исправления ошибок
  if ( !empty( $error ) ) {
	$_SESSION['editPostForm'] = array();
	$_SESSION['editPostForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'."\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['editPostForm']['message'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=editPostForm&idForum='.
	         $_GET['idForum'].'&id_theme='.$id_theme.'&id_post='.$id_post );
	die();
  }
  
  $file = $post['putfile'];
  // Такой ситуации быть не должно, но я случайтно удалил файл вложения
  // вручную, и после этого нельзя было правильно загрузить файл
  if ( !empty( $file ) and !is_file( './files/'.$post['putfile'] ) ) $file = '';
  // Если выставлен флажок "Удалить загруженный ранее файл"
  if ( isset( $_POST['unlink'] ) and !empty( $file ) and is_file( './files/'.$post['putfile'] ) ) {
    if ( unlink( './files/'.$post['putfile'] ) ) $file = '';
  }
  if ( !empty( $_FILES['attach']['name'] ) ) {
    // Если пользователь загружает новый файл - мы должны сперва удалить старый
	// (при условии, что он вообще был загружен ранее)
    if ( !empty( $file ) and is_file( './files/'.$post['putfile'] ) ) {
      if ( unlink( './files/'.$post['putfile'] ) ) $file = '';	  
	}
	// Загружать новый файл мы будем только при условии, что был успешно
	// удален ранее загруженный (или он не загружался вовсе)
	if ( empty( $file ) ) {
      // Массив недопустимых расширений файла вложения
      $extentions = array('.php', '.phtml', '.php3', '.html', '.htm', '.pl');
      // Извлекаем из имени файла расширение
      $ext = strrchr( $_FILES['attach']['name'], "." ); 
      // Формируем путь к файлу    
      if ( in_array( $ext, $extentions ) )
        $new = $id_theme.'-'.date("YmdHis",time()).'.txt'; 
      else
        $new = $id_theme.'-'.date("YmdHis",time()).$ext; 
      // Перемещаем файл из временной директории сервера в директорию files
      if ( move_uploaded_file ( $_FILES['attach']['tmp_name'], './files/'.$new ) ) {
	    chmod( './files/'.$new, 0644 );
	    $file = $new;
	  }
	}
  }
  
  // Все поля заполнены правильно - выполняем запрос к БД
  $query = "UPDATE ".TABLE_POSTS." SET
			name='".mysql_real_escape_string( $message )."',
			putfile='".$file."',
			id_editor=".$_SESSION['user']['id_author'].",
			edittime=NOW()
			WHERE id_post=".$id_post;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при обновлении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	  $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	  '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	$queryString = 'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme;
	return showErrorMessage( $msg, $err, true, $queryString );
  } 
    
  $msg = 'Cообщение успешно исправлено';
  $queryString = 'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$id_theme;
  return showInfoMessage( $msg, $queryString );
  
}

// Функция удаляет сообщение (пост)
function deletePost()
{
  // Если не передан ID форума - значит функция была вызвана по ошибке
  if ( !isset( $_GET['idForum'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не прередан ID темы - значит функция вызвана по ошибке
  if ( !isset( $_GET['id_theme'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_theme = (int)$_GET['id_theme'];
  if ( $id_theme < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не прередан ID сообщения (поста) - значит функция вызвана по ошибке
  if ( !isset( $_GET['id_post'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  $id_post = (int)$_GET['id_post'];
  if ( $id_post < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  } 

  // Выдаем пользователю сообщение с просьбой подтвердить свое
  // желание удалить сообщение (пост)
  if ( !isset( $_GET['confirm'] ) ) {
    $html = '<div align="center"><p>Вы действительно хотите удалить это сообщение?</p>'."\n";
    $html = $html.'<input type="button" name="yes" value="Да" 
            onClick="document.location.href=\''.$_SERVER['PHP_SELF'].'?action=deletePost&idForum='.
		    $_GET['idForum'].'&id_theme='.$id_theme.'&id_post='.$id_post.
		    '&confirm=yes\'" />&nbsp;&nbsp;'."\n";
    $html = $html.'<input type="button" name="no" value="Нет" 
            onClick="document.location.href=\''.$_SERVER['PHP_SELF'].'?action=showTheme&idForum='.
		    $_GET['idForum'].'&id_theme='.$id_theme.'\'" /></div>'."\n";
    $tpl = file_get_contents( './templates/infoMessage.html' );
    $tpl = str_replace( '{infoMessage}', $html, $tpl );
    return $tpl; 
  }
  
  // Получаем из БД информацию об удаляемом сообщении - это нужно,
  // чтобы узнать, имеет ли право пользователь удалить это сообщение
  $query = "SELECT * FROM ".TABLE_POSTS." WHERE id_post=".$id_post;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	  $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	  '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showForum&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    return showInfoMessage( 'Сообщение успешно удалено', 
	                        'action=showForum&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  } 
  $post = mysql_fetch_array( $res );   
  if ( !hasRightDeletePost( $post ) ) {
    return showInfoMessage( 'У вас нет прав, чтобы удалить это сообщение', 
	                        'action=showForum&idForum='.$_GET['idForum'].'&id_theme='.$id_theme );
  }
  
  // Удаляем файл, если он есть
  if ( !empty( $post['putfile'] ) and is_file( './files/'.$post['putfile'] ) )
    unlink( './files/'.$post['putfile'] );
  $query = "DELETE FROM ".TABLE_POSTS." WHERE id_post=".$id_post;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showTheme&idForum='.$_GET['idForum'].'&id_theme='.$post['id_theme'] );
  }
  // Если это - единственное сообщение темы, то надо удалить и тему
  $query = "SELECT COUNT(*) FROM ".TABLE_POSTS." WHERE id_theme=".$post['id_theme'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showForum&idForum='.$_GET['idForum'] );
  }
  if ( mysql_result( $res, 0, 0 ) == 0 ) {
    // Прежде чем удалять тему, надо обновить таблицу TABLE_USERS
	$query = "UPDATE ".TABLE_USERS." 
	          SET themes=themes-1 
			  WHERE id_author=(SELECT id_author FROM ".TABLE_THEMES." WHERE id_theme=".$post['id_theme'].")";
	$res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при удалении сообщения';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	        '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
    }
    $query = "DELETE FROM ".TABLE_THEMES." WHERE id_theme=".$post['id_theme'];
	$res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при удалении сообщения';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	        '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.$_GET['idForum'] );
    }
	// Если мы удалили тему, то мы не можем в нее вернуться;
	// поэтому редирект будет на страницу форума, а не страницу темы
	$deleteTheme = true;
  } 
  
  // Обновляем количество сообщений, оставленных автором сообщения ...
  $query = "UPDATE ".TABLE_USERS." SET posts=posts-1 WHERE id_author=".$post['id_author'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при удалении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 
	                         'action=showForum&idForum='.$_GET['idForum'] );
  }
  // ... и таблицу TABLE_THEMES
  if ( !isset( $deleteTheme ) ) {
    $query = "SELECT id_author, author, time
	          FROM ".TABLE_POSTS."
			  WHERE id_theme=".$post['id_theme']." 
			  ORDER BY id_post DESC
			  LIMIT 1";
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при удалении сообщения';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.
	                           $_GET['idForum'].'&id_theme='.$post['id_theme'] );
    }
	list( $id_last_author, $last_author, $last_post ) = mysql_fetch_row( $res );
	$query = "UPDATE ".TABLE_THEMES." 
	          SET id_last_author=".$id_last_author.", last_author='".$last_author."', last_post='".$last_post."'
			  WHERE id_theme=".$post['id_theme'];
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при удалении сообщения';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=showForum&idForum='.
	                           $_GET['idForum'].'&id_theme='.$post['id_theme'] );
    }  
  }
	
  if ( isset( $deleteTheme ) ) {
    return showInfoMessage( 'Сообщение успешно удалено', 'action=showForum&idForum='.$_GET['idForum'] );
  } else {
    return showInfoMessage( 'Сообщение успешно удалено', 'action=showTheme&idForum='.
                            $_GET['idForum'].'&id_theme='.$post['id_theme'] ); 
  }
}

// Функция возвращает форму для регистрации нового пользователя на форуме
function getAddNewUserForm()
{
  if ( isset( $_SESSION['captcha_keystring'] ) ) unset( $_SESSION['captcha_keystring'] );
  $html = '';
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['addNewUserForm'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['addNewUserForm']['error'], $info );
    $html = $html.$info."\n";
    $name      = htmlspecialchars( $_SESSION['addNewUserForm']['name'] );
    $email     = htmlspecialchars( $_SESSION['addNewUserForm']['email'] );
    $timezone  = $_SESSION['addNewUserForm']['timezone'];
    $icq       = htmlspecialchars( $_SESSION['addNewUserForm']['icq'] );
    $url       = htmlspecialchars( $_SESSION['addNewUserForm']['url'] );
    $about     = htmlspecialchars( $_SESSION['addNewUserForm']['about'] );
    $signature = htmlspecialchars( $_SESSION['addNewUserForm']['signature'] );
    unset( $_SESSION['addNewUserForm'] );
  } else {
    $name      = '';
    $email     = '';
    $timezone  = 0;
    $icq       = '';
    $url       = '';
    $about     = '';
    $signature = '';
  }
  
  // Считываем в переменную файл шаблона, содержащего 
  // форму для добавления нового пользователя
  $tpl = file_get_contents( './templates/addNewUserForm.html' );
  $action = $_SERVER['PHP_SELF'].'?action=addNewUser';
  $tpl = str_replace( '{action}', $action, $tpl);
  $tpl = str_replace( '{name}', $name, $tpl);
  $tpl = str_replace( '{email}', $email, $tpl);
  $tpl = str_replace( '{icq}', $icq, $tpl);
  $tpl = str_replace( '{url}', $url, $tpl);
  $tpl = str_replace( '{about}', $about, $tpl);
  $tpl = str_replace( '{signature}', $signature, $tpl);
  $kcaptcha = './kcaptcha/kc.php?'.session_name().'='.session_id();
  $tpl = str_replace( '{kcaptcha}', $kcaptcha, $tpl);
  $tpl = str_replace( '{keystring}', '', $tpl);
  
  $options = '';
  for ( $i = -12; $i <= 12; $i++ ) {
    if ( $i < 1 ) 
	  $value = $i.' часов';
	else
	  $value = '+'.$i.' часов';
    if ( $i == $timezone )
      $options = $options . '<option value="'.$i.'" selected>'.$value.'</option>'."\n";
    else
      $options = $options . '<option value="'.$i.'">'.$value.'</option>'."\n";
  }
  $tpl = str_replace( '{options}', $options, $tpl);
  $tpl = str_replace( '{servertime}', date( "d.m.Y H:i:s" ), $tpl );
  
  $html = $html . $tpl;
  return $html;
}

// Функция добавляет нового пользователя форума (запись в таблице БД TABLE_USERS)
function addNewUser()
{

  // Если не переданы данные формы - значит функция была вызвана по ошибке
  if ( !isset( $_POST['name'] ) or
       !isset( $_POST['password'] ) or
       !isset( $_POST['confirm'] ) or
       !isset( $_POST['email'] ) or
       !isset( $_POST['timezone'] ) or
       !isset( $_POST['icq'] ) or
       !isset( $_POST['url'] ) or
       !isset( $_POST['about'] ) or
       !isset( $_POST['signature'] ) or
       !isset( $_POST['keystring'] ) or
       !isset( $_FILES['avatar'] ) 
    )
  {
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=addNewUserForm' );
	die();
  }

  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $name      = substr( $_POST['name'], 0, 30 );
  $password  = substr( $_POST['password'], 0, 30 );
  $confirm   = substr( $_POST['confirm'], 0, 30 );
  $email     = substr( $_POST['email'], 0, 60 );
  $icq       = substr( $_POST['icq'], 0, 12 );
  $url       = substr( $_POST['url'], 0, 60 );
  $about     = substr( $_POST['about'], 0, 1000 );
  $signature = substr( $_POST['signature'], 0, 500 );
  $keystring = substr( $_POST['keystring'], 0, 6 );

  // Обрезаем лишние пробелы
  $name      = trim( $name );
  $password  = trim( $password );
  $confirm   = trim( $confirm );
  $email     = trim( $email );
  $icq       = trim( $icq );
  $url       = trim( $url );
  $about     = trim( $about );
  $signature = trim( $signature );
  $keystring = trim( $keystring );

  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $name ) ) $error = $error.'<li>не заполнено поле "Имя"</li>'."\n";
  if ( empty( $password ) ) $error = $error.'<li>не заполнено поле "Пароль"</li>'."\n";
  if ( empty( $confirm ) ) $error = $error.'<li>не заполнено поле "Подтвердите пароль"</li>'."\n";
  if ( empty( $email ) ) $error = $error.'<li>не заполнено поле "Адрес e-mail"</li>'."\n";
  if ( empty( $keystring ) ) $error = $error.'<li>не заполнено поле "Код"</li>'."\n";
  // Проверяем, не слишком ли короткий пароль
  if ( !empty( $password ) and strlen( $password ) < MIN_PASSWORD_LENGTH )
    $error = $error.'<li>длина пароля должна быть не меньше '.MIN_PASSWORD_LENGTH.' символов</li>'."\n";
  // Проверяем, совпадают ли пароли
  if ( !empty( $password ) and !empty( $confirm ) and $password != $confirm ) 
    $error = $error.'<li>не совпадают пароли</li>'."\n";
  // Проверяем поле "код"
  if ( !empty( $keystring ) ) {
    // Проверяем поле "код" на недопустимые символы
    if ( !preg_match( "~^[A-Za-z0-9]{2,20}$~i", $keystring ) )
      $error = $error.'<li>поле "Код" содержит недопустимые символы</li>'."\n";
	// Проверяем, совпадает ли код с картинки
    if ( !isset( $_SESSION['captcha_keystring'] ) or $_SESSION['captcha_keystring'] != $keystring )
      $error = $error.'<li>не совпадает код с картинки</li>'."\n";
  }
  unset( $_SESSION['captcha_keystring'] );

  // Проверяем поля формы на недопустимые символы
  if ( !empty( $name ) and !preg_match( "#^[- _0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $name ) )
    $error = $error.'<li>поле "Имя" содержит недопустимые символы</li>'."\n";
  if ( !empty( $password ) and !preg_match( "#^[-_0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $password ) )
    $error = $error.'<li>поле "Пароль" содержит недопустимые символы</li>'."\n";
  if ( !empty( $confirm ) and !preg_match( "#^[-_0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $confirm ) )
    $error = $error.'<li>поле "Подтвердите пароль" содержит недопустимые символы</li>'."\n";
  if ( !empty( $icq ) and !preg_match( "#^[0-9]+$#", $icq ) )
    $error = $error.'<li>поле "ICQ" содержит недопустимые символы</li>'."\n";
  if ( !empty( $about ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $about ) )
    $error = $error.'<li>поле "Интересы" содержит недопустимые символы</li>'."\n";
  if ( !empty( $signature ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $signature ) )
    $error = $error.'<li>поле "Подпись" содержит недопустимые символы</li>'."\n";

  // Проверяем корректность e-mail
  if ( !empty( $email ) and !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ) )
    $error = $error.'<li>поле "Адрес e-mail" должно соответствовать формату somebody@somewhere.ru</li>'."\n";
	
  // Проверяем корректность URL домашней странички	
  if ( !empty( $url ) and !preg_match( "#^(http:\/\/)?(www.)?[-0-9a-z]+\.[a-z]{2,6}\/?$#i", $url ) )
    $error = $error.'<li>поле "Домашняя страничка" должно соответствовать формату http://www.homepage.ru</li>'."\n";

  // Выясняем не зарегистрировано ли уже это имя
  // Возможно три ситуации, которые необходимо предотвратить:
  // 1. Вводится ник, полностью совпадающий с уже существующим
  // 2. Вводится уже существующий кирилический ник, в котором
  //    одна или несколько букв заменены на латинские
  // 3. Вводится уже существующий латинский ник, в котором
  //    одна или несколько букв заменениы на кирилические
  
  // Массив кирилических букв
  $rus = array( "А","а","В","Е","е","К","М","Н","О","о","Р","р","С","с","Т","Х","х" );
  // Массив латинских букв
  $eng = array( "A","a","B","E","e","K","M","H","O","o","P","p","C","c","T","X","x" );
  $new_name = preg_replace( "#[^- _0-9a-zА-Яа-я]#i", "", $name );
  // Заменяем русские буквы латинскими
  $eng_new_name = str_replace( $rus, $eng, $new_name ); 
  // Заменяем латинские буквы русскими
  $rus_new_name = str_replace( $eng, $rus, $new_name ); 
  // Формируем SQL-запрос
  $query = "SELECT * FROM ".TABLE_USERS." 
		    WHERE name LIKE '".mysql_real_escape_string( $new_name )."' OR
			      name LIKE '".mysql_real_escape_string( $eng_new_name )."' OR
			      name LIKE '".mysql_real_escape_string( $rus_new_name )."';";
  $res = mysql_query( $query );

  if ( !$res ) {
    $msg = 'Ошибка при регистрации нового пользователя';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }

  if ( mysql_num_rows( $res ) > 0 )
    $error = $error.'<li>имя "'.$new_name.'" уже зарегистрировано</li>'."\n";

  if ( !empty( $_FILES['avatar']['name'] ) ) {
    $ext = strrchr( $_FILES['avatar']['name'], "." );
    $extensions = array( ".jpg", ".gif", ".bmp", ".png" );
    if ( !in_array( $ext, $extensions ) ) 
	  $error = $error.'<li>недопустимый формат файла аватара</li>'."\n";
    if ( $_FILES['avatar']['size'] > MAX_AVATAR_SIZE ) 
      $error = $error.'<li>размер файла аватора больше '.(MAX_AVATAR_SIZE/1024).' Кб</li>'."\n";
  }

  $timezone = (int)$_POST['timezone'];
  if ( $timezone < -12 or $timezone > 12 ) $timezone = 0;

  // Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации
  if ( !empty( $error ) ) {
    $_SESSION['addNewUserForm'] = array();
    $_SESSION['addNewUserForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
    $_SESSION['addNewUserForm']['name'] = $name;
    $_SESSION['addNewUserForm']['email'] = $email;
    $_SESSION['addNewUserForm']['timezone'] = $timezone;
    $_SESSION['addNewUserForm']['icq'] = $icq;
    $_SESSION['addNewUserForm']['url'] = $url;
    $_SESSION['addNewUserForm']['about'] = $about;
    $_SESSION['addNewUserForm']['signature'] = $signature;
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=addNewUserForm' );
    die();
  }

  if ( !empty( $url ) and substr($url, 0, 7) != 'http://' ) $url = 'http://'.$url;
  
  // Уникальный код для активации учетной записи
  $code = md5( uniqid( rand(), 1 ) );
  // Все поля заполнены правильно - продолжаем регистрацию
  $query = "INSERT INTO ".TABLE_USERS."
		    (
		    name,
		    passw,
		    email,
		    timezone,
		    url,
		    icq,
		    about,
		    signature,
			photo,
			puttime,
			last_visit,
			themes,
			status,
		    activation
		    )
		    VALUES
		    (
		    '".mysql_real_escape_string( $name )."',
		    '".mysql_real_escape_string( md5( $password ) )."',
		    '".mysql_real_escape_string( $email )."',
		    ".$timezone.",
		    '".mysql_real_escape_string( $url )."',
		    '".$icq."',
		    '".mysql_real_escape_string( $about )."',
		    '".mysql_real_escape_string( $signature )."',
			'',
			NOW(),
			NOW(),
			0,
			'user',
		    '".$code."'
		    );";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при регистрации нового пользователя';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	      '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }

  $id = mysql_insert_id();
  if ( !empty( $_FILES['avatar']['name'] ) and 
       move_uploaded_file ( $_FILES['avatar']['tmp_name'], './photo/'.$id ) ) chmod( './photo/'.$id, 0644 );

  // Посылаем письмо пользователю с просьбой активировать учетную запись
  $headers = "From: ".$_SERVER['SERVER_NAME']." <".ADMIN_EMAIL.">\n";
  $headers = $headers."Content-type: text/html; charset=\"utf-8\"\n";
  $headers = $headers."Return-path: <".ADMIN_EMAIL.">\n";
  $message = '<p>Добро пожаловать на форум '.$_SERVER['SERVER_NAME'].'!</p>'."\n";
  $message = $message.'<p>Пожалуйста сохраните это сообщение. Параметры вашей учётной записи таковы:</p>'."\n";
  $message = $message.'<p>Логин: '.$name.'<br/>Пароль: '.$password.'</p>'."\n";
  $message = $message.'<p>Для активации вашей учетной записи перейдите по ссылке:</p>'."\n";
  $link = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?action=activateUser&code='.$code;  
  $message = $message.'<p><a href="'.$link.'">Активировать учетную запись</a></p>'."\n";
  $message = $message.'<p>Не забывайте свой пароль: он хранится в нашей базе в зашифрованном 
             виде, и мы не сможем вам его выслать. Если вы всё же забудете пароль, то сможете 
             запросить новый, который придётся активировать таким же образом, как и вашу 
             учётную запись.</p>'."\n";
  $message = $message.'<p>Спасибо за то, что зарегистрировались на нашем форуме.</p>'."\n";
  $subject = 'Регистрация на форуме '.$_SERVER['SERVER_NAME'];
  $subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?=';
  mail( $email, $subject, $message, $headers );
  
  $msg = 'На Ваш e-mail выслано письмо с просьбой подтвердить регистрацию. 
          Чтобы завершить регистрацию и активировать учетную запись, зайдите 
          по адресу, указанному в письме.';
  $html = file_get_contents( './templates/infoMessage.html' );
  $html = str_replace( '{infoMessage}', $msg, $html );
  return $html;
}

// Активация учетной записи нового пользователя
function activateUser()
{
  // Если не передан параметр $code - значит функция вызвана по ошибке
  if ( !isset( $_GET['code'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Т.к. код зашифрован с помощью md5, то он представляет собой 
  // 32-значное шестнадцатеричное число
  $code = substr( $_GET['code'], 0, 32 );
  $code = preg_replace( "#[^0-9a-f]#i", '', $code );

  $query = "SELECT id_author 
            FROM ".TABLE_USERS." 
			WHERE activation='".mysql_real_escape_string( $code )."' LIMIT 1";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при активации учетной записи. Обратитесь к администратору.';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	      '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  if ( mysql_num_rows( $res ) > 0 ) {
	$id = mysql_result( $res, 0, 0 );
	$query = "UPDATE ".TABLE_USERS." 
	          SET activation='', last_visit=NOW() 
			  WHERE id_author=".$id;
	$res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при активации учетной записи. Обратитесь к администратору.';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, '' );
    }
  }
  return showInfoMessage( 'Ваша учетная запись активирована.', 'action=loginForum' );
}

// Если пользователь забыл свой пароль, он может получить новый,
// заполнив эту форму (свой логин и e-mail)
function newPasswordForm()
{
  $html = '';
  if ( isset( $_SESSION['newPasswordForm']['error'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['newPasswordForm']['error'], $info );
	$html = $html.$info."\n";
	unset( $_SESSION['newPasswordForm']['error'] );
  }  
  $action = $_SERVER['PHP_SELF'].'?action=sendNewPassword';
  $tpl = file_get_contents( './templates/newPasswordForm.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $html = $html . $tpl;
  return $html;
}

// Функция высылает на e-mail пользователя новый пароль
function sendNewPassword()
{

  // Если не переданы методом POST логин и e-mail - перенаправляем пользователя
  if ( !isset( $_POST['username'] ) or
	   !isset( $_POST['email'] )
	  ) 
  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }

  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $name  = substr( $_POST['username'], 0, 30 );
  $email = substr( $_POST['email'], 0, 60 );

  // Обрезаем лишние пробелы
  $name = trim( $name );
  $email = trim( $email );

  // Проверяем, заполнены ли обязательные поля
  $error = "";
  if ( empty( $name ) ) $error = $error.'<li>не заполнено поле "Имя"</li>'."\n";
  if ( empty( $email ) ) $error = $error.'<li>не заполнено поле "Адрес e-mail"</li>'."\n";

  // Проверяем поля формы на недопустимые символы
  if ( !empty( $name ) and !ereg( "[-_[:blank:]0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+", $name ) )
    $error = $error.'<li>поле "Имя" содержит недопустимые символы</li>'."\n";
  // Проверяем корректность e-mail
  if ( !empty( $email ) and !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ) )
    $error = $error.'<li>поле "Адрес e-mail" должно соответствовать формату somebody@somewhere.ru</li>'."\n";
  // Проверять существование такого пользователя есть смысл только в том
  // случае, если поля не пустые и не содержат недопустимых символов
  if ( empty( $error ) ) {	
    $query = "SELECT id_author FROM ".TABLE_USERS." 
              WHERE name='".mysql_real_escape_string( $name )."' 
              AND email='".mysql_real_escape_string( $email )."'";
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при создании нового пароля.';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=newPasswordForm' );
    }
	
    // Если пользователь с таким логином и e-mail существует
    if ( mysql_num_rows( $res ) > 0 ) {
      // Небольшой код, который читает содержимое директории activate
      // и удаляет старые файлы для активации пароля (были созданы более суток назад)
      if ( $dir = opendir( './activate' ) ) {
        chdir( './activate' );
        $tmp = 24*60*60;
        while ( false !== ( $file = readdir($dir) ) ) { 
          if ( is_file( $file ) ) 
            if ( ( time() - filemtime( $file ) ) > $tmp ) unlink( $file );
        }
        chdir( '..' );
        closedir( $dir );
      }
      // Как происходит процедура восстановления пароля? Пользователь ввел свой логин
      // и e-mail, мы проверяем существование такого пользователя в таблице БД. Потом
      // генерируем с помощью функции getNewPassword() новый пароль, создаем файл с именем
      // md5( $newPassword ) в директории activate. Файл содержит ID пользователя.
      // В качестве кода активации выступает хэш пароля - md5( $newPassword ). 
      // Когда пользователь перейдет по ссылке в письме для активации своего нового пароля,
      // мы проверяем наличие в директории activatePassword файла с именем кода активации,
      // и если он существует, активируем новый пароль.
      $id = mysql_result( $res, 0, 0 );
      $newPassword = getNewPassword();
      $code = md5( $newPassword );
      // file_put_contents( './activate/'.$code, $id );
      $fp = fopen( './activate/'.$code, "w" );
      fwrite($fp, $id);
      fclose($fp);
      // Посылаем письмо пользователю с просьбой активировать пароль
      $headers = "From: ".$_SERVER['SERVER_NAME']." <".ADMIN_EMAIL.">\n";
      $headers = $headers."Content-type: text/html; charset=\"utf-8\"\n";
      $headers = $headers."Return-path: <".ADMIN_EMAIL.">\n";
      $message = '<p>Добрый день, '.$name.'!</p>'."\n";
      $message = $message.'<p>Вы получили это письмо потому, что вы (либо кто-то, выдающий себя 
	             за вас) попросили выслать новый пароль к вашей учётной записи на форуме '.
				 $_SERVER['SERVER_NAME'].'. Если вы не просили выслать пароль, то не обращайте 
				 внимания на это письмо, если же подобные письма будут продолжать приходить, 
				 обратитесь к администратору форума</p>'."\n";
      $message = $message.'<p>Прежде чем использовать новый пароль, вы должны его активировать. 
	             Для этого перейдите по ссылке:</p>'."\n";
      $link = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].
	          '?action=activatePassword&code='.$code;		
      $message = $message.'<p><a href="'.$link.'">Активировать пароль</a></p>'."\n";
      $message = $message.'<p>В случае успешной активации вы сможете входить в систему, используя 
	             следующий пароль: '.$newPassword.'</p>'."\n";
      $message = $message.'<p>Вы сможете сменить этот пароль на странице редактирования профиля. 
	             Если у вас возникнут какие-то трудности, обратитесь к администратору форума.</p>'."\n";
      $subject = 'Активация пароля на форуме '.$_SERVER['SERVER_NAME'];
	  $subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?=';
      mail( $email, $subject, $message, $headers );
 
      $msg = 'На ваш e-mail выслано письмо. Чтобы активировать новый пароль, зайдите 
	          по адресу, указанному в письме.';
      $html = file_get_contents( './templates/infoMessage.html' );
      $html = str_replace( '{infoMessage}', $msg, $html );
	  
      return $html;  
    } else {
      $error = $error.'<li>неправильный логин или e-mail</li>'."\n";
    }
  }
    
  // Если были допущены ошибки при заполнении формы - перенаправляем посетителя
  if ( !empty( $error ) ) {
	$_SESSION['newPasswordForm'] = array();
	$_SESSION['newPasswordForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены
    ошибки:</p>'."\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=newPasswordForm' );
	die();
  }

}

// Активация нового пароля
function activatePassword()
{
  if ( !isset( $_GET['code'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Т.к. код активации создан с помощью md5, то он 
  // представляет собой 32-значное шестнадцатеричное число
  $code = substr( $_GET['code'], 0, 32 );
  $code = preg_replace( "#[^0-9a-f]#i", '', $code );
  if ( is_file( './activate/'.$code ) and  ( ( time() - filemtime('./activate/'.$code) ) < 24*60*60 ) ) {
    $file = file( './activate/'.$code );
	unlink( './activate/'.$code );
	$id_user = (int)trim( $file[0] );
    $query = "UPDATE ".TABLE_USERS." 
              SET passw='".mysql_real_escape_string( $code )."' 
			  WHERE id_author=".$id_user;
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при активации нового пароля. Обратитесь к администратору.';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=newPasswordForm' );
    }  
    $message = 'Ваш новый пароль успешно активирован.';
  } else {
    $message = 'Ошибка при активации нового пароля. Обратитесь к администратору.';
  }
  
  $html = file_get_contents( './templates/infoMessage.html' );
  $html = str_replace( '{infoMessage}', $message, $html );
  return $html;
}

// Функция возвращает случайно сгенерированный пароль
function getNewPassword()
{
  $length = rand( 10, 30 );
  $password = '';
  for( $i = 0; $i < $length; $i++ ) {
    $range = rand(1, 3);
    switch( $range ) {
      case 1: $password = $password.chr( rand(48, 57) );  break;
      case 2: $password = $password.chr( rand(65, 90) );  break;
      case 3: $password = $password.chr( rand(97, 122) ); break;
    }
  }
  return $password;
}

// Функция возвращает html формы для редактирования данных о пользователе
function getEditUserForm()
{
  // Если информацию о пользователе пытается редактировать 
  // не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  $html = '';
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['editUserForm'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['editUserForm']['error'], $info );
    $html = $html.$info."\n";
    $email     = htmlspecialchars( $_SESSION['editUserForm']['email'] );
    $timezone  = $_SESSION['editUserForm']['timezone'];
    $icq       = htmlspecialchars( $_SESSION['editUserForm']['icq'] );
    $url       = htmlspecialchars( $_SESSION['editUserForm']['url'] );
    $about     = htmlspecialchars( $_SESSION['editUserForm']['about'] );
    $signature = htmlspecialchars( $_SESSION['editUserForm']['signature'] );
    unset( $_SESSION['editUserForm'] );
  } else {
    $email     = htmlspecialchars( $_SESSION['user']['email'] );
    $timezone  = $_SESSION['user']['timezone'];
    $icq       = htmlspecialchars( $_SESSION['user']['icq'] );
    $url       = htmlspecialchars( $_SESSION['user']['url'] );
    $about     = htmlspecialchars( $_SESSION['user']['about'] );
    $signature = htmlspecialchars( $_SESSION['user']['signature'] );
  }
  
  $action = $_SERVER['PHP_SELF'].'?action=updateUser';
  
  $tpl = file_get_contents( './templates/editUserForm.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{email}', htmlspecialchars( $email ), $tpl );
  $tpl = str_replace( '{icq}', htmlspecialchars( $icq ), $tpl );
  $tpl = str_replace( '{url}', htmlspecialchars( $url ), $tpl );
  $tpl = str_replace( '{about}', htmlspecialchars( $about ), $tpl );
  $tpl = str_replace( '{signature}', htmlspecialchars( $signature ), $tpl );
  
  $options = '';
  for ( $i = -12; $i <= 12; $i++ ) {
    if ( $i < 1 ) 
	  $value = $i.' часов';
	else
	  $value = '+'.$i.' часов';
    if ( $i == $_SESSION['user']['timezone'] )
      $options = $options . '<option value="'.$i.'" selected>'.$value.'</option>'."\n";
    else
      $options = $options . '<option value="'.$i.'">'.$value.'</option>'."\n";
  }
  $tpl = str_replace( '{options}', $options, $tpl);
  $tpl = str_replace( '{servertime}', date( "d.m.Y H:i:s" ), $tpl );
  // Если ранее был загружен файл - надо предоставить возможность удалить его
  $unlinkfile = '';
  if ( is_file( './photo/'.$_SESSION['user']['id_author'] ) ) {
    $unlinkfile = '<br/><input type="checkbox" name="unlink" value="1" />
	              Удалить загруженный ранее файл'."\n";
  }
  $tpl = str_replace( '{unlinkfile}', $unlinkfile, $tpl );
  
  $html = $html.$tpl;
  
  return $html;
}

// Функция обновляет данные пользователя (обновляет запись в таблице TABLE_USERS)
function updateUser()
{
  // Если это не зарегистрированный пользователь - функция вызвана по ошибке
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }

  // Если не переданы данные формы - функция вызвана по ошибке
  if ( !isset( $_POST['password'] ) or
       !isset( $_POST['newpassword'] ) or
       !isset( $_POST['confirm'] ) or
       !isset( $_POST['email'] ) or
       !isset( $_POST['timezone'] ) or
       !isset( $_POST['icq'] ) or
       !isset( $_POST['url'] ) or
       !isset( $_POST['about'] ) or
       !isset( $_POST['signature'] ) or
       !isset( $_FILES['avatar'] ) 
    )
  {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }

  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $password     = substr( $_POST['password'], 0, 30 );
  $newpassword  = substr( $_POST['newpassword'], 0, 30 );
  $confirm      = substr( $_POST['confirm'], 0, 30 );
  $email        = substr( $_POST['email'], 0, 60 );
  $icq          = substr( $_POST['icq'], 0, 12 );
  $url          = substr( $_POST['url'], 0, 60 );
  $about        = substr( $_POST['about'], 0, 1000 );
  $signature    = substr( $_POST['signature'], 0, 500 );

  // Обрезаем лишние пробелы
  $password     = trim( $password );
  $newpassword  = trim( $newpassword );
  $confirm      = trim( $confirm );
  $email        = trim( $email );
  $icq          = trim( $icq );
  $url          = trim( $url );
  $about        = trim( $about );
  $signature    = trim( $signature );

  // Проверяем, заполнены ли обязательные поля
  $error = '';
  
  // Если заполнено поле "Текущий пароль" - значит пользователь
  // хочет изменить его или поменять свой e-mail 
  $changePassword = false;
  $changeEmail = false;
  if ( !empty( $_POST['password'] ) ) {
    if ( md5( $_POST['password'] ) != $_SESSION['user']['passw'] ) 
	  $error = $error.'<li>текущий пароль введен не верно</li>'."\n";
	// Надо выяснить, что хочет сделать пользователь: 
	// поменять свой e-mail, изменить пароль или и то и другое
	if ( !empty( $newpassword ) ) { // хочет изменить пароль
	  $changePassword = true;
      if ( empty( $confirm ) ) $error = $error.'<li>не заполнено поле "Подтвердите пароль"</li>'."\n";
      // Проверяем, не слишком ли короткий новый пароль
      if ( strlen( $newpassword ) < MIN_PASSWORD_LENGTH )
        $error = $error.'<li>длина пароля должна быть не меньше '.MIN_PASSWORD_LENGTH.' символов</li>'."\n";
      // Проверяем, совпадают ли пароли
      if ( !empty( $confirm ) and $newpassword != $confirm ) 
        $error = $error.'<li>не совпадают пароли</li>'."\n";
      // Проверяем поля формы на недопустимые символы
      if (  !preg_match( "#^[-_0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $newpassword ) )
        $error = $error.'<li>поле "Новый пароль" содержит недопустимые символы</li>'."\n";
      if ( !empty( $confirm ) and !preg_match( "#^[-_0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $confirm ) )
        $error = $error.'<li>поле "Подтвердите пароль" содержит недопустимые символы</li>'."\n";
    }
	if ( $email != $_SESSION['user']['email'] ) { // хочет изменить e-mail
	  $changeEmail = true;
      if ( empty( $email ) ) $error = $error.'<li>не заполнено поле "Адрес e-mail"</li>'."\n";
      // Проверяем корректность e-mail
      if ( !empty( $email ) and !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ) )
        $error = $error.'<li>поле "Адрес e-mail" должно соответствовать формату 
		         somebody@somewhere.ru</li>'."\n";	
	}  
  }  
  
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $icq ) and !preg_match( "#^[0-9]+$#", $icq ) )
    $error = $error.'<li>поле "ICQ" содержит недопустимые символы</li>'."\n";
  if ( !empty( $about ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $about ) )
    $error = $error.'<li>поле "Интересы" содержит недопустимые символы</li>'."\n";
  if ( !empty( $signature ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $signature ) )
    $error = $error.'<li>поле "Подпись" содержит недопустимые символы</li>'."\n";
	
  // Проверяем корректность URL домашней странички	
  if ( !empty( $url ) and !preg_match( "#^(http:\/\/)?(www.)?[-0-9a-z]+\.[a-z]{2,6}\/?$#i", $url ) )
    $error = $error.'<li>поле "Домашняя страничка" должно соответствовать формату http://www.homepage.ru</li>'."\n";

  if ( !empty( $_FILES['avatar']['name'] ) ) {
    $ext = strrchr( $_FILES['avatar']['name'], "." );
    $extensions = array( ".jpg", ".gif", ".bmp", ".png" );
    if ( !in_array( $ext, $extensions ) ) 
	  $error = $error.'<li>недопустимый формат файла аватара</li>'."\n";
    if ( $_FILES['avatar']['size'] > MAX_AVATAR_SIZE ) 
      $error = $error.'<li>размер файла аватора больше '.(MAX_AVATAR_SIZE/1024).' Кб</li>'."\n";
  }

  $timezone = (int)$_POST['timezone'];
  if ( $timezone < -12 or $timezone > 12 ) $timezone = 0;

  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя на страницу редактирования
  if ( !empty( $error ) ) {
    $_SESSION['editUserForm'] = array();
    $_SESSION['editUserForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
    $_SESSION['editUserForm']['email'] = $email;
    $_SESSION['editUserForm']['timezone'] = $timezone;
    $_SESSION['editUserForm']['icq'] = $icq;
    $_SESSION['editUserForm']['url'] = $url;
    $_SESSION['editUserForm']['about'] = $about;
    $_SESSION['editUserForm']['signature'] = $signature;
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=editUserForm' );
    die();
  }	

  // Если выставлен флажок "Удалить загруженный ранее файл"
  if ( isset( $_POST['unlink'] ) and is_file( './photo/'.$_SESSION['user']['id_author'] ) ) {
    unlink( './photo/'.$_SESSION['user']['id_author'] );
  } 
  if ( !empty( $_FILES['avatar']['name'] ) and 
       move_uploaded_file ( $_FILES['avatar']['tmp_name'], './photo/'.$_SESSION['user']['id_author'] ) ) {
	chmod( './photo/'.$_SESSION['user']['id_author'], 0644 );
  }
  
  // Все поля заполнены правильно - записываем изменения в БД
  $tmp = '';  
  if ( $changePassword ) {
    $tmp = $tmp."passw='".mysql_real_escape_string( md5( $newpassword ) )."', ";
	$_SESSION['user']['passw'] = md5( $newpassword );
  }
  if ( $changeEmail ) {
    $tmp = $tmp."email='".mysql_real_escape_string( $email )."', ";
	$_SESSION['user']['email'] = $email;
  }
  $query = "UPDATE ".TABLE_USERS." SET ".$tmp."
		    timezone=".$timezone.",
		    url='".mysql_real_escape_string( $url )."',
		    icq='".$icq."',
		    about='".mysql_real_escape_string( $about )."',
		    signature='".mysql_real_escape_string( $signature )."'
			WHERE id_author=".$_SESSION['user']['id_author'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при обновлении профиля';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	      '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  // Теперь надо обновить данные о пользователе в массиве $_SESSION['user']
  if ( $changePassword ) $_SESSION['user']['passw'] = md5( $newpassword );
  if ( $changeEmail ) $_SESSION['user']['email'] = $email;
  $_SESSION['user']['timezone'] = $timezone;
  $_SESSION['user']['url'] = $url;
  $_SESSION['user']['icq'] = $icq;
  $_SESSION['user']['about'] = $about;
  $_SESSION['user']['signature'] = $signature;
  // ... и в массиве $_COOKIE
  if ( isset( $_COOKIE['autologin'] ) ) {
    $tmppos = strrpos( $_SERVER['PHP_SELF'], '/' ) + 1;
	$path = substr( $_SERVER['PHP_SELF'], 0, $tmppos );
	setcookie( 'autologin', 'yes', time() + 3600*24*COOKIE_TIME, $path );
	setcookie( 'username', $_SESSION['user']['name'], time() + 3600*24*COOKIE_TIME, $path );
	setcookie( 'password', $_SESSION['user']['passw'], time() + 3600*24*COOKIE_TIME, $path );
  }

  return showInfoMessage( 'Ваш профиль был изменён', '' );
}

// Функция возвращает html формы для редактирования данных о пользователе
// (только для администратора форума)
function getEditUserFormByAdmin()
{
  // Если не передан ID пользователя - значит функция вызвана по ошибке
  if ( !isset( $_GET['idUser'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showUsersList' );
	die();
  }
  $id = (int)$_GET['idUser'];
  // ID зарегистрированного пользователя не может быть меньше 
  // единицы - значит функция вызвана по ошибке
  if ( $id < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showUsersList' );
	die();
  }
  // Если информацию о пользователе пытается редактировать 
  // не зарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Только администратор имеет право на эту операцию
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  
  $statusArray = array( 'user' => 'Пользователь',
                        'moderator' => 'Модератор',
				        'admin' => 'Администратор' );  
  $html = '';
  
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['editUserFormByAdmin'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['editUserFormByAdmin']['error'], $info );
    $html = $html.$info."\n";
	$name      = htmlspecialchars( $_SESSION['editUserFormByAdmin']['name'] );
	$status    = $_SESSION['editUserFormByAdmin']['status'];
    $email     = htmlspecialchars( $_SESSION['editUserFormByAdmin']['email'] );
	$oldEmail  = htmlspecialchars( $_SESSION['editUserFormByAdmin']['oldEmail'] );
    $timezone  = $_SESSION['editUserFormByAdmin']['timezone'];
    $icq       = htmlspecialchars( $_SESSION['editUserFormByAdmin']['icq'] );
    $url       = htmlspecialchars( $_SESSION['editUserFormByAdmin']['url'] );
    $about     = htmlspecialchars( $_SESSION['editUserFormByAdmin']['about'] );
    $signature = htmlspecialchars( $_SESSION['editUserFormByAdmin']['signature'] );
    unset( $_SESSION['editUserFormByAdmin'] );
  } else {
    // Получаем данные о пользователе из БД
    $query = "SELECT id_author, name, email, url, icq, about, photo, status, timezone, signature 
              FROM ".TABLE_USERS." 
              WHERE id_author=".$id;
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при получении информации о пользователе';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, 'action=showUsersList', true );
    }
    if ( mysql_num_rows( $res ) == 0 ) 
      return showInfoMessage( 'Пользователь не найден', 'action=showUsersList' );
    $user = mysql_fetch_array( $res );
	
	$name      = htmlspecialchars( $user['name'] );
	$status    = $user['status'];
	$email     = htmlspecialchars( $user['email'] );
	$oldEmail  = $email;
    $timezone  = $user['timezone'];
    $icq       = htmlspecialchars( $user['icq'] );
    $url       = htmlspecialchars( $user['url'] );
    $about     = htmlspecialchars( $user['about'] );
    $signature = htmlspecialchars( $user['signature'] );
  }

  $userStatus = '<select name="status">'."\n";
  foreach( $statusArray as $key => $value ) {
    if ( $key == $status )
	  $userStatus = $userStatus.'<option value="'.$key.'" selected>'.$value.'</option>'."\n";
	else
	  $userStatus = $userStatus.'<option value="'.$key.'">'.$value.'</option>'."\n";
  }
  $userStatus = $userStatus.'</select>'."\n";
  
  $action = $_SERVER['PHP_SELF'].'?action=updateUserByAdmin&idUser='.$id;
  
  $tpl = file_get_contents( './templates/editUserFormByAdmin.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{name}', htmlspecialchars( $name ), $tpl );
  $tpl = str_replace( '{status}', $userStatus, $tpl );
  $tpl = str_replace( '{email}', htmlspecialchars( $email ), $tpl );
  $tpl = str_replace( '{icq}', htmlspecialchars( $icq ), $tpl );
  $tpl = str_replace( '{url}', htmlspecialchars( $url ), $tpl );
  $tpl = str_replace( '{about}', htmlspecialchars( $about ), $tpl );
  $tpl = str_replace( '{signature}', htmlspecialchars( $signature ), $tpl );
  
  $options = '';
  for ( $i = -12; $i <= 12; $i++ ) {
    if ( $i < 1 ) 
	  $value = $i.' часов';
	else
	  $value = '+'.$i.' часов';
    if ( $i == $timezone )
      $options = $options . '<option value="'.$i.'" selected>'.$value.'</option>'."\n";
    else
      $options = $options . '<option value="'.$i.'">'.$value.'</option>'."\n";
  }
  $tpl = str_replace( '{options}', $options, $tpl);
  $tpl = str_replace( '{servertime}', date( "d.m.Y H:i:s" ), $tpl );
  // Если ранее был загружен файл - надо предоставить возможность удалить его
  $unlinkfile = '';
  if ( is_file( './photo/'.$id ) ) {
    $unlinkfile = '<br/><input type="checkbox" name="unlink" value="1" />
	              Удалить загруженный ранее файл'."\n";
  }
  $tpl = str_replace( '{unlinkfile}', $unlinkfile, $tpl );
  
  $html = $html.$tpl;
  
  return $html;
}

// Функция обновляет данные пользователя (только для администратора форума)
function updateUserByAdmin()
{
  // Если не передан ID пользователя - значит функция вызвана по ошибке
  if ( !isset( $_GET['idUser'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showUsersList' );
	die();
  }
  $id = (int)$_GET['idUser'];
  // ID зарегистрированного пользователя не может быть меньше 
  // единицы - значит функция вызвана по ошибке
  if ( $id < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showUsersList' );
	die();
  }
  // Если профиль пытается редактировать не зарегистрированный 
  // пользователь - функция вызвана по ошибке
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Только администратор имеет право на эту операцию
  if ( $_SESSION['user']['status'] != 'admin' ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }
  // Если не переданы данные формы - функция вызвана по ошибке
  if ( !isset( $_POST['name'] ) or
       !isset( $_POST['status'] ) or
	   !isset( $_POST['email'] ) or
	   !isset( $_POST['oldEmail'] ) or
       !isset( $_POST['newpassword'] ) or
	   !isset( $_POST['confirm'] ) or
       !isset( $_POST['timezone'] ) or
       !isset( $_POST['icq'] ) or
       !isset( $_POST['url'] ) or
       !isset( $_POST['about'] ) or
       !isset( $_POST['signature'] ) or
       !isset( $_FILES['avatar'] ) 
    )
  {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die();
  }

  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $email        = substr( $_POST['email'], 0, 60 );
  $oldEmail     = substr( $_POST['oldEmail'], 0, 60 );
  $newpassword  = substr( $_POST['newpassword'], 0, 30 );
  $confirm      = substr( $_POST['confirm'], 0, 30 );
  $icq          = substr( $_POST['icq'], 0, 12 );
  $url          = substr( $_POST['url'], 0, 60 );
  $about        = substr( $_POST['about'], 0, 1000 );
  $signature    = substr( $_POST['signature'], 0, 500 );

  // Обрезаем лишние пробелы
  $email        = trim( $email );
  $oldEmail     = trim( $oldEmail );
  $newpassword  = trim( $newpassword );
  $confirm      = trim( $confirm );
  $icq          = trim( $icq );
  $url          = trim( $url );
  $about        = trim( $about );
  $signature    = trim( $signature );

  // Проверяем, заполнены ли обязательные поля
  $error = '';
  
  // Надо выяснить, что хочет сделать администратор: 
  // поменять e-mail, изменить пароль или и то и другое
  $changePassword = false;
  $changeEmail = false;
	
  if ( !empty( $newpassword ) ) { // хочет изменить пароль
	$changePassword = true;
    if ( empty( $confirm ) ) $error = $error.'<li>не заполнено поле "Подтвердите пароль"</li>'."\n";
    // Проверяем, не слишком ли короткий новый пароль
    if ( strlen( $newpassword ) < MIN_PASSWORD_LENGTH )
      $error = $error.'<li>длина пароля должна быть не меньше '.MIN_PASSWORD_LENGTH.' символов</li>'."\n";
    // Проверяем, совпадают ли пароли
    if ( !empty( $confirm ) and $newpassword != $confirm ) 
      $error = $error.'<li>не совпадают пароли</li>'."\n";
    // Проверяем поля формы на недопустимые символы
    if (  !preg_match( "#^[-_0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $newpassword ) )
      $error = $error.'<li>поле "Новый пароль" содержит недопустимые символы</li>'."\n";
    if ( !empty( $confirm ) and !preg_match( "#^[-_0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $confirm ) )
      $error = $error.'<li>поле "Подтвердите пароль" содержит недопустимые символы</li>'."\n";
  }
  if ( $email != $oldEmail ) { // хочет изменить e-mail
	$changeEmail = true;
    if ( empty( $email ) ) $error = $error.'<li>не заполнено поле "Адрес e-mail"</li>'."\n";
    // Проверяем корректность e-mail
    if ( !empty( $email ) and !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ) )
      $error = $error.'<li>поле "Адрес e-mail" должно соответствовать формату 
		       somebody@somewhere.ru</li>'."\n";	
  }    
  
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $icq ) and !preg_match( "#^[0-9]+$#", $icq ) )
    $error = $error.'<li>поле "ICQ" содержит недопустимые символы</li>'."\n";
  if ( !empty( $about ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $about ) )
    $error = $error.'<li>поле "Интересы" содержит недопустимые символы</li>'."\n";
  if ( !empty( $signature ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $signature ) )
    $error = $error.'<li>поле "Подпись" содержит недопустимые символы</li>'."\n";
	
  // Проверяем корректность URL домашней странички	
  if ( !empty( $url ) and !preg_match( "#^(http:\/\/)?(www.)?[-0-9a-z]+\.[a-z]{2,6}\/?$#i", $url ) )
    $error = $error.'<li>поле "Домашняя страничка" должно соответствовать формату http://www.homepage.ru</li>'."\n";

  if ( !empty( $_FILES['avatar']['name'] ) ) {
    $ext = strrchr( $_FILES['avatar']['name'], "." );
    $extensions = array( ".jpg", ".gif", ".bmp", ".png" );
    if ( !in_array( $ext, $extensions ) ) 
	  $error = $error.'<li>недопустимый формат файла аватара</li>'."\n";
    if ( $_FILES['avatar']['size'] > MAX_AVATAR_SIZE ) 
      $error = $error.'<li>размер файла аватора больше '.(MAX_AVATAR_SIZE/1024).' Кб</li>'."\n";
  }

  $statusArray = array( 'user' => 'Пользователь',
                        'moderator' => 'Модератор',
				        'admin' => 'Администратор' ); 
  if ( in_array( $_POST['status'], $statusArray ) )
    $status = $_POST['status'];
  else
    $status = 'user';
  
  $timezone = (int)$_POST['timezone'];
  if ( $timezone < -12 or $timezone > 12 ) $timezone = 0;

  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя на страницу редактирования
  if ( !empty( $error ) ) {
    $_SESSION['editUserFormByAdmin'] = array();
    $_SESSION['editUserFormByAdmin']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['editUserFormByAdmin']['name'] = $_POST['name'];
	$_SESSION['editUserFormByAdmin']['status'] = $status;
    $_SESSION['editUserFormByAdmin']['email'] = $email;
	$_SESSION['editUserFormByAdmin']['oldEmail'] = $oldEmail;
    $_SESSION['editUserFormByAdmin']['timezone'] = $timezone;
    $_SESSION['editUserFormByAdmin']['icq'] = $icq;
    $_SESSION['editUserFormByAdmin']['url'] = $url;
    $_SESSION['editUserFormByAdmin']['about'] = $about;
    $_SESSION['editUserFormByAdmin']['signature'] = $signature;
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=editUserFormByAdmin' );
    die();
  }	

  // Если выставлен флажок "Удалить загруженный ранее файл"
  if ( isset( $_POST['unlink'] ) and is_file( './photo/'.$id ) ) {
    unlink( './photo/'.$id );
  } 
  if ( !empty( $_FILES['avatar']['name'] ) and 
       move_uploaded_file ( $_FILES['avatar']['tmp_name'], './photo/'.$id ) ) {
	chmod( './photo/'.$id, 0644 );
  }
  
  // Все поля заполнены правильно - записываем изменения в БД
  $tmp = '';  
  if ( $changePassword ) {
    $tmp = $tmp."passw='".mysql_real_escape_string( md5( $newpassword ) )."', ";
  }
  if ( $changeEmail ) {
    $tmp = $tmp."email='".mysql_real_escape_string( $email )."', ";
  }
  $query = "UPDATE ".TABLE_USERS." SET ".$tmp."
            status='".$status."',
		    timezone=".$timezone.",
		    url='".mysql_real_escape_string( $url )."',
		    icq='".$icq."',
		    about='".mysql_real_escape_string( $about )."',
		    signature='".mysql_real_escape_string( $signature )."'
			WHERE id_author=".$id;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при обновлении профиля';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	      '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  } else {
    return showInfoMessage( 'Профиль был изменён', '' );
  }
}

// Функция возвращает html списка пользователей форума
function getUsersList()
{
  // Выбираем из БД количество пользователей - это нужно для 
  // построения постраничной навигации
  $query = "SELECT COUNT(*) FROM ".TABLE_USERS." WHERE 1";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании списка пользователей';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showUsersList' );
  }
  $total = mysql_result( $res, 0, 0 );
    
  // Проверяем передан ли номер текущей страницы (постраничная навигация)
  if ( isset($_GET['page']) ) {
    $page = (int)$_GET['page'];
    if ( $page < 1 ) $page = 1;
  } else {
    $page = 1;
  }

  // Число страниц списка пользователей (постраничная навигация)
  $cntPages = ceil( $total / USERS_PER_PAGE );
  if ( $page > $cntPages ) $page = $cntPages;
  // Начальная позиция (постраничная навигация)
  $start = ( $page - 1 ) * USERS_PER_PAGE;

  $query = "SELECT id_author, name, status, email, url, icq, puttime, posts
            FROM ".TABLE_USERS." 
            WHERE 1 ORDER BY puttime ASC LIMIT ".$start.", ".USERS_PER_PAGE;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании списка пользователей';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showUsersList' );
  }

  // Выводим "шапку" таблицы
  $html = '<table class="showTable">'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'<th>Имя</th>'."\n";
  $html = $html.'<th>Статус</th>'."\n";
  $html = $html.'<th>Зарегистрирован</th>'."\n";
  $html = $html.'<th>Сообщений</th>'."\n";
  if ( isset( $_SESSION['user'] ) ) { 
    $html = $html.'<th>Личное сообщение</th>'."\n";
    $html = $html.'<th>E-mail</th>'."\n";
  }
  $html = $html.'<th>WWW</th>'."\n";
  $html = $html.'<th>ICQ</th>'."\n";
  if ( isset( $_SESSION['user'] ) and $_SESSION['user']['status'] == 'admin' )
    $html = $html.'<th>Правка</th>'."\n";
  $html = $html.'</tr>'."\n";

  $status = array( 'user' => 'Пользователь',
                   'moderator' => 'Модератор',
				   'admin' => 'Администратор' );
  
  while( $user = mysql_fetch_array( $res ) ) {
    $html = $html.'<tr align="center">'."\n"; 
    $html = $html.'<td align="left"><a href="'.$_SERVER['PHP_SELF'].'?action=showUserInfo&idUser='.
	        $user['id_author'].'">'.$user['name'].'</a></td>'."\n";
	$html = $html.'<td align="left">'.$status[$user['status']].'</td>'."\n";
	$html = $html.'<td>'.$user['puttime'].'</td>'."\n";
	$html = $html.'<td>'.$user['posts'].'</td>'."\n";
    if ( isset( $_SESSION['user'] ) ) { 
      $html = $html.'<td><a href="'.$_SERVER['PHP_SELF'].'?action=sendMsgForm&idUser='.
	          $user['id_author'].'">Написать</a></td>'."\n";
      $html = $html.'<td><a href="'.$_SERVER['PHP_SELF'].'?action=sendMailForm&idUser='.
	          $user['id_author'].'">Написать</a></td>'."\n";
    }
    if ( !empty( $user['url'] ) ) 
        $html = $html.'<td align="left"><a href="'.$user['url'].'" target="_blank">'.$user['url'].'</td>'."\n";
    else
        $html = $html.'<td align="left">&nbsp;</td>'."\n";
    if ( !empty( $user['icq'] ) ) 
        $html = $html.'<td>'.$user['icq'].'</td>'."\n";
    else
        $html = $html.'<td>&nbsp;</td>'."\n"; 
    if ( isset( $_SESSION['user'] ) and $_SESSION['user']['status'] == 'admin' ) {
      $html = $html.'<td><a href="'.$_SERVER['PHP_SELF'].
		        '?action=editUserFormByAdmin&idUser='.$user['id_author'].'"><img 
				src="./images/icon_edit.gif" alt="Править" title="Править" /></a></td>'."\n";
    }				
    $html = $html.'</tr>'."\n";
  }

  $html = $html.'</table>'."\n";

  // Строим постраничную навигацию
  if ( $cntPages > 1 ) {
	$html = $html.pageIterator( $page, $cntPages, $_SERVER['PHP_SELF'].'?action=showUsersList' );
  }
  return $html;
}

// Функция возврашает информацию о пользователе; ID пользователя передается методом GET
function showUserInfo()
{
  // Если не передан ID пользователя - значит функция вызвана по ошибке
  if ( !isset( $_GET['idUser'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showUsersList' );
	die();
  }
  $id = (int)$_GET['idUser'];
  // ID зарегистрированного пользователя не может быть меньше 
  // единицы - значит функция вызвана по ошибке
  if ( $id < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showUsersList' );
	die();
  }
  
  $status = array( 'user' => 'Пользователь',
                   'moderator' => 'Модератор',
				   'admin' => 'Администратор' );
  $query = "SELECT id_author, name, email, url, icq, about, photo, puttime, last_visit, posts, status 
            FROM ".TABLE_USERS." 
            WHERE id_author=".$id;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при получении информации о пользователе';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, 'action=showUsersList', true );
  }
  if ( mysql_num_rows( $res ) == 0 ) 
    return showInfoMessage( 'Пользователь не найден', 'action=showUsersList' );
  $user = mysql_fetch_array( $res );
  
  if ( isset( $_SESSION['user'] ) ) {
    $email = '<a href="'.$_SERVER['PHP_SELF'].'?action=sendMailForm&idUser='.$id.'">Написать письмо</a>';
    $privateMessage = '<a href="'.$_SERVER['PHP_SELF'].'?action=sendMsgForm&idUser='.
                      $id.'">Отправить личное сообщение</a>';
  } else {
    $email = '[Для зарегистрированных пользователей]';
	$privateMessage = '[Для зарегистрированных пользователей]';
  }
  $query = "SELECT time FROM ".TABLE_POSTS." WHERE id_author=".$id." ORDER BY time DESC LIMIT 1";
  $res = mysql_query( $query );
  if ( $res ) {
    if ( mysql_num_rows( $res ) > 0 )
      $lastPost = mysql_result( $res, 0, 0 );
	else
	  $lastPost = '';
  } else {
    $lastPost = '';
  }
  
  $html = file_get_contents( './templates/showUserInfo.html' );
  $html = str_replace( '{name}', $user['name'], $html );
  $html = str_replace( '{regdate}', $user['puttime'], $html ); 
  $html = str_replace( '{status}', $status[$user['status']], $html );
  $html = str_replace( '{lastvisit}', $user['last_visit'], $html );
  $html = str_replace( '{lastpost}', $lastPost, $html ); 
  $html = str_replace( '{totalposts}', $user['posts'], $html );
  $html = str_replace( '{email}', $email, $html );
  $html = str_replace( '{url}', $user['url'], $html );
  $html = str_replace( '{icq}', $user['icq'], $html );
  $html = str_replace( '{about}', $user['about'], $html );
  $html = str_replace( '{privatemessage}', $privateMessage, $html );
  return $html."\n"; 
}

// Функция возвращает html формы для отправки личного сообщения
function getSendMsgForm()
{
  // Незарегистрированный пользователь не может отправлять личные сообщения
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die(); 
  }
  
  $html = '<h1>Личные сообщения</h1>'."\n";
  $html = $html.getMessagesMenu(); 
  
  $toUser = '';
  if ( isset( $_GET['idUser'] ) ) {
    $id = (int)$_GET['idUser'];
    if ( $id > 0 ) {
	  $query = "SELECT name FROM ".TABLE_USERS." WHERE id_author=".$id;
	  $res = mysql_query( $query );
	  if ( $res ) {
	    if ( mysql_num_rows( $res ) > 0 ) $toUser = mysql_result( $res, 0, 0 );
	  }
	}
  }
  $subject = '';
  $message = '';

  if ( isset( $_SESSION['viewMessage'] ) and !empty( $_SESSION['viewMessage']['message'] ) ) {
    $view = file_get_contents( './templates/previewMessage.html' );
	$view = str_replace( '{message}', print_page( $_SESSION['viewMessage']['message'] ), $view ); 
	$html = $html.$view."\n";
	$toUser  = htmlspecialchars( $_SESSION['viewMessage']['toUser'] );
	$subject = htmlspecialchars( $_SESSION['viewMessage']['subject'] );
	$message = htmlspecialchars( $_SESSION['viewMessage']['message'] );
	unset( $_SESSION['viewMessage'] );
  }
  
  $action = $_SERVER['PHP_SELF'].'?action=sendMessage';
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['sendMessageForm'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['sendMessageForm']['error'], $info );
	$html = $html.$info."\n";
	$toUser  = htmlspecialchars( $_SESSION['sendMessageForm']['toUser'] );
	$subject = htmlspecialchars( $_SESSION['sendMessageForm']['subject'] );
	$message = htmlspecialchars( $_SESSION['sendMessageForm']['message'] );
	unset( $_SESSION['sendMessageForm'] );
  }
  
  $tpl = file_get_contents( './templates/sendMessageForm.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{toUser}', $toUser, $tpl );
  $tpl = str_replace( '{subject}', $subject, $tpl );
  $tpl = str_replace( '{message}', $message, $tpl );
  
  $html = $html.$tpl;
  
  return $html;
}

// Отправка личного сообщения (добавляется новая запись в таблицу БД TABLE_MESSAGES)
function sendMessage()
{
  // Незарегистрированный пользователь не может отправлять личные сообщения
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die(); 
  }
  
  // Если не переданы данные формы - функция вызвана по ошибке
  if ( !isset( $_POST['toUser'] ) or
	   !isset( $_POST['subject'] ) or
	   !isset( $_POST['message'] ) )
  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }

  $msgLen = strlen( $_POST['message'] );
  
  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $toUser  = substr( $_POST['toUser'], 0, 30 );
  $subject = substr( $_POST['subject'], 0, 60 );
  $message = substr( $_POST['message'], 0, MAX_MESSAGE_LENGTH );
  // Обрезаем лишние пробелы
  $toUser  = trim( $toUser );
  $subject = trim( $subject );
  $message = trim( $message );
  
  // Если пользователь хочет посмотреть на сообщение перед отправкой
  if ( isset( $_POST['viewMessage'] ) ) 
  {
	$_SESSION['viewMessage'] = array();
	$_SESSION['viewMessage']['toUser'] = $toUser;
	$_SESSION['viewMessage']['subject'] = $subject;
	$_SESSION['viewMessage']['message'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=sendMsgForm' );
	die();
  }
  
  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $toUser ) ) $error = $error.'<li>не заполнено поле "Для пользователя"</li>'."\n";
  if ( empty( $subject ) ) $error = $error.'<li>не заполнено поле "Заголовок сообщения"</li>'."\n";
  if ( empty( $message ) ) $error = $error.'<li>не заполнено поле "Текст сообщения"</li>'."\n";
  if ( $msgLen > MAX_MESSAGE_LENGTH ) 
    $error = $error.'<li>длина сообщения больше '.MAX_MESSAGE_LENGTH.' символов</li>'."\n";
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $toUser ) and !preg_match( "#^[- _0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $toUser ) )
    $error = $error.'<li>поле "Для пользователя" содержит недопустимые символы</li>'."\n";
  if ( !empty( $subject ) and !preg_match( "#^[-.;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $subject ) )
    $error = $error.'<li>поле "Заголовок сообщения" содержит недопустимые символы</li>'."\n";
  // Проверяем, есть ли такой пользователь
  if ( !empty( $toUser ) ) {
    $to = preg_replace( "#[^- _0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]#i", '', $toUser );
    $query = "SELECT id_author FROM ".TABLE_USERS." WHERE name='".$to."' LIMIT 1";
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Произошла ошибка при отправке сообщения';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=sendMsgForm' );
    }
    if ( mysql_num_rows( $res ) == 0 ) 
      $error = $error.'<li>пользователь с именем <strong>'.$to.'</strong> не зарегистрирован</li>'."\n";
	if ( (mysql_num_rows( $res ) == 1 ) and (mysql_result( $res, 0, 0 ) == $_SESSION['user']['id_author']) )
	  $error = $error.'<li>нельзя послать сообщение самому себе</li>'."\n";
  }
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя для исправления ошибок
  if ( !empty( $error ) )
  {
	$_SESSION['sendMessageForm'] = array();
	$_SESSION['sendMessageForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['sendMessageForm']['toUser'] = $toUser;
	$_SESSION['sendMessageForm']['subject'] = $subject;
	$_SESSION['sendMessageForm']['message'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=sendMsgForm' );
	die();
  }
  
  // Все поля заполнены правильно - "посылаем" сообщение  
  $to = mysql_result( $res, 0, 0 );
  $from = $_SESSION['user']['id_author'];
  
  $query = "INSERT INTO ".TABLE_MESSAGES."
            VALUES 
			( 
			NULL, 
			".$to.", 
			".$from.",
            NOW(),			
			'".mysql_real_escape_string( $subject )."', 
			'".mysql_real_escape_string( $message )."',
            0,
            0			
			)";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Произошла ошибка при отправке сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showOutBox' );
  }
  
  return showInfoMessage( 'Ваше сообщение успешно отправлено', 'action=showOutBox' );
}

// Функция возвращает личное сообщение для просмотра пользователем
function getMessage()
{
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  if ( !isset( $_GET['idMsg'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  $idMsg = (int)$_GET['idMsg'];
  if ( $idMsg < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showInBox' );
    die();
  }
  // Получаем из БД информацию о сообщении.
  // В этом запросе дополнительное условие нужно для того, чтобы
  // пользователь не смог просмотреть чужое сообщение, просто указав
  // ID сообщения в адресной строке браузера
  $query = "SELECT a.to_user, a.from_user, a.subject, a.sendtime, a.message, a.viewed,
                   b.name AS to_user_name, c.name AS from_user_name  
            FROM ".TABLE_MESSAGES." a INNER JOIN ".TABLE_USERS." b
			ON a.to_user=b.id_author
			INNER JOIN ".TABLE_USERS." c
			ON a.from_user=c.id_author
			WHERE id_msg=".$idMsg." 
			AND (a.to_user=".$_SESSION['user']['id_author']." OR a.from_user=".$_SESSION['user']['id_author'].")
			AND a.id_rmv<>".$_SESSION['user']['id_author'];
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Произошла ошибка при получении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, 'action=showInBox' );
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showInBox' );
    die();
  }
  // Далее мы должны выяснить, запрашивается входящее или исходящее
  // сообщение? Это нужно, чтобы правильно указать "Отправитель"
  // или "Получатель" и вывести заголовок страницы: "Входящие" 
  // или "Исходящие"
  $message = mysql_fetch_array( $res );
  if ( $message['to_user'] == $_SESSION['user']['id_author'] ) {
    $redirect = 'action=showInBox';
	$inBox = true;
  } else {
    $redirect = 'action=showOutBox';
	$inBox = false;
  }
  // Формируем заголовок страницы
  if ( $inBox )  // Папка "Входящие"
    $html = '<h1>Личные сообщения (входящие)</h1>'."\n";
  else           // Папка "Исходящие"
    $html = '<h1>Личные сообщения (исходящие)</h1>'."\n";
  $html = $html.getMessagesMenu();
  $html = $html.'<table class="showTable">'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'<th colspan="2">Сообщение</th>'."\n";
  $html = $html.'</tr>'."\n";
  $html = $html.'<tr>'."\n";
  if ( $inBox ) {
    $html = $html.'<td width="15%">Отправитель</td>'."\n";
	$html = $html.'<td width="85%">'.$message['from_user_name'].'</td>'."\n";
  } else {
    $html = $html.'<td width="15%">Получатель</td>'."\n";
	$html = $html.'<td width="85%">'.$message['to_user_name'].'</td>'."\n";
  }
  $html = $html.'</tr>'."\n";
  $html = $html.'<tr>'."\n";
  if ( $inBox )
    $html = $html.'<td>Отправлено</td>'."\n";
  else
    $html = $html.'<td>Получено</td>'."\n";
  $html = $html.'<td>'.$message['sendtime'].'</td>'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'</tr>'."\n";
  $html = $html.'<td>Тема</td>'."\n";
  $html = $html.'<td>'.$message['subject'].'</td>'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'</tr>'."\n";
  $html = $html.'<td>Сообщение</td>'."\n";
  $html = $html.'<td>'.print_page( $message['message'] ).'</td>'."\n";
  $html = $html.'</tr>'."\n";
  $html = $html.'</table>'."\n";
  
  // Помечаем сообщение, как прочитанное
  if ( $inBox and $message['viewed'] != 1 ) {
    $query = "UPDATE ".TABLE_MESSAGES." SET viewed=1 WHERE id_msg=".$idMsg;
	mysql_query( $query );
  }
  
  return $html;
}

// Папка личных сообщений (входящие)
function getInMsgBox()
{
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  $html = '<h1>Личные сообщения (входящие)</h1>'."\n";
  $html = $html.getMessagesMenu(); 
  
  $html = $html.'<table class="showTable">'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'<th width="2%">&nbsp;</th>'."\n";
  $html = $html.'<th width="15%">Отправитель</th>'."\n";
  $html = $html.'<th width="63%">Тема сообщения</th>'."\n";
  $html = $html.'<th width="15%">Дата</th>'."\n";
  $html = $html.'<th width="5%">Удл.</th>'."\n";
  $html = $html.'</tr>'."\n";
  // Запрос на выборку входящих сообщений
  
  // id_rmv - это поле указывает на то, что это сообщение уже удалил
  // один из пользователей. Т.е. сначала id_rmv=0, после того, как
  // сообщение удалил один из пользователей, id_rmv=id_user. И только после
  // того, как сообщение удалит второй пользователь, мы можем удалить
  // запись в таблице БД TABLE_MESSAGES
  $query = "SELECT a.id_msg, a.subject, a.from_user, a.sendtime, a.viewed, b.name
            FROM ".TABLE_MESSAGES." a INNER JOIN ".TABLE_USERS." b
            ON a.from_user=b.id_author
            WHERE a.to_user=".$_SESSION['user']['id_author']."
			AND id_rmv<>".$_SESSION['user']['id_author']."
			ORDER BY sendtime DESC";
  $res = mysql_query( $query );
  
  while ( $msg = mysql_fetch_row( $res ) ) {
    $html = $html.'<tr>'."\n";
    // Если сообщение еще не прочитано
	if ( $msg[4] == 0 )
	  $html = $html.'<td align="center" valign="middle"><img src="./images/folder_new.gif" width="19"
		      height="18" alt="" /></td>';
	else
      $html = $html.'<td align="center" valign="middle"><img src="./images/folder.gif" width="19" 
		      height="18" alt="" /></td>';	  
	$html = $html.'<td>'.$msg[5].'</td>'."\n";
	$html = $html.'<td><a href="'.$_SERVER['PHP_SELF'].'?action=showMsg&idMsg='.
	        $msg[0].'">'.$msg[1].'</a></td>'."\n";
	$html = $html.'<td>'.$msg[3].'</td>'."\n";
	$html = $html.'<td align="center"><a href="'.$_SERVER['PHP_SELF'].
	        '?action=deleteMsg&idMsg='.$msg[0].'"><img src="./images/icon_delete.gif"
			alt="Удалить" title="Удалить" border="0" /></a></td>'."\n";
	$html = $html.'</tr>'."\n";    
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    $html = $html.'<tr>'."\n";
	$html = $html.'<td colspan="4">В этой папке нет сообщений</td>'."\n";
	$html = $html.'</tr>'."\n";
  }
  $html = $html.'</table>'."\n";
  
  return $html;
}

// Папка личных сообщений (исходящие)
function getOutMsgBox()
{
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  $html = '<h1>Личные сообщения (исходящие)</h1>'."\n";
  $html = $html.getMessagesMenu(); 
  
  $html = $html.'<table class="showTable">'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'<th width="15%">Получатель</th>'."\n";
  $html = $html.'<th width="65%">Тема сообщения</th>'."\n";
  $html = $html.'<th width="15%">Дата</th>'."\n";
  $html = $html.'<th width="5%">Удл.</th>'."\n";
  $html = $html.'</tr>'."\n";
  
  // Запрос на выборку исходящих сообщений 
  // id_rmv - это поле указывает на то, что это сообщение уже удалил
  // один из пользователей. Т.е. сначала id_rmv=0, после того, как
  // сообщение удалил один из пользователей, id_rmv=id_user. И только после
  // того, как сообщение удалит второй пользователь, мы можем удалить
  // запись в таблице БД TABLE_MESSAGES
  $query = "SELECT a.id_msg, a.subject, a.to_user, a.sendtime, b.name
            FROM ".TABLE_MESSAGES." a INNER JOIN ".TABLE_USERS." b
		    ON a.to_user=b.id_author
		    WHERE a.from_user=".$_SESSION['user']['id_author']."
			AND id_rmv<>".$_SESSION['user']['id_author']."
			ORDER BY sendtime DESC";
  $res = mysql_query( $query );
  
  while ( $msg = mysql_fetch_row( $res ) ) {
    $html = $html.'<tr>'."\n";
	$html = $html.'<td>'.$msg[4].'</td>'."\n";
	$html = $html.'<td><a href="'.$_SERVER['PHP_SELF'].'?action=showMsg&idMsg='.
	        $msg[0].'">'.$msg[1].'</a></td>'."\n";
	$html = $html.'<td>'.$msg[3].'</td>'."\n";
	$html = $html.'<td align="center"><a href="'.$_SERVER['PHP_SELF'].
	        '?action=deleteMsg&idMsg='.$msg[0].'"><img src="./images/icon_delete.gif"
			alt="Удалить" title="Удалить" border="0" /></a></td>'."\n";
	$html = $html.'</tr>'."\n";    
  }
  if ( mysql_num_rows( $res ) == 0 ) {
    $html = $html.'<tr>'."\n";
	$html = $html.'<td colspan="4">В этой папке нет сообщений</td>'."\n";
	$html = $html.'</tr>'."\n";
  }
  $html = $html.'</table>'."\n";
  
  return $html;		 
}
// Функция удаляет личное сообщение; ID сообщения передается методом GET
function deleteMessage()
{
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }

  if ( !isset( $_GET['idMsg'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  } 

  $idMsg = (int)$_GET['idMsg'];
  if ( $idMsg < 1 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }

  // Далее мы должны выяснить, удаляется входящее или исходящее
  // сообщение. Это нужно, чтобы сделать редирект на нужный ящик.
  // В этом запросе дополнительное условие нужно для того, чтобы
  // пользователь не смог удалить чужое сообщение, просто указав
  // ID сообщения в адресной строке браузера
  $query = "SELECT to_user, id_rmv 
            FROM ".TABLE_MESSAGES." 
			WHERE id_msg=".$idMsg." AND 
			(to_user=".$_SESSION['user']['id_author']." OR from_user=".$_SESSION['user']['id_author'].")";
  $res = mysql_query( $query );
  if ( mysql_num_rows( $res ) == 0 ) {
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=showInBox' );
    die();
  }

  list ( $toUser, $id_rmv ) = mysql_fetch_row( $res );
  if ( $toUser == $_SESSION['user']['id_author'] )
    $redirect = 'action=showInBox';
  else
    $redirect = 'action=showOutBox';
  // id_rmv - это поле указывает на то, что это сообщение уже удалил
  // один из пользователей. Т.е. сначала id_rmv=0, после того, как
  // сообщение удалил один из пользователей, id_rmv=id_user. И только после
  // того, как сообщение удалит второй пользователь, мы можем удалить
  // запись в таблице БД TABLE_MESSAGES
  if ( $id_rmv == 0 ) 
    $query = "UPDATE ".TABLE_MESSAGES." SET id_rmv=".$_SESSION['user']['id_author']." WHERE id_msg=".$idMsg;
  else
    $query = "DELETE FROM ".TABLE_MESSAGES." WHERE id_msg=".$idMsg;
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Произошла ошибка при удалении сообщения';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, $redirect );
  }
  return showInfoMessage( 'Сообщение успешно удалено', $redirect );
}

// Функция возвращает меню для раздела "Личные сообщения"
function getMessagesMenu()
{
  $html = '<table width="100%">'."\n";
  $html = $html.'<tr valign="middle">'."\n";
  $html = $html.'<td><img src="./images/msg_inbox.gif" alt="Входящие" title="Входящие" /></td>'."\n";
  $html = $html.'<td><a class="header" href="'.$_SERVER['PHP_SELF'].
          '?action=showInBox">Входящие</a>&nbsp;&nbsp;</td>'."\n";
  $html = $html.'<td><img src="./images/msg_outbox.gif" alt="Исходящие" title="Исходящие" /></td>'."\n";
  $html = $html.'<td><a class="header" href="'.$_SERVER['PHP_SELF'].
		  '?action=showOutBox">Исходящие</a></td>'."\n";
  $html = $html.'<td align="right" width="90%"><a href="'.$_SERVER['PHP_SELF'].
          '?action=sendMsgForm"><img 
		  src="./images/msg_newpost.gif" alt="Новое сообщение" 
		  title="Новое сообщение" /></a></td>'."\n";
  $html = $html.'</tr>'."\n";
  $html = $html.'</table>'."\n";
  
  return $html;
}

// Функция возвращает html формы для отправки письма через форум
function getSendMailForm()
{
  // Если письмо пытается отправить незарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();  
  }
  
  $html = '';
  
  $toUser = '';
  if ( isset( $_GET['idUser'] ) ) {
    $id = (int)$_GET['idUser'];
    if ( $id > 0 ) {
	  $query = "SELECT name FROM ".TABLE_USERS." WHERE id_author=".$id;
	  $res = mysql_query( $query );
	  if ( $res ) {
	    if ( mysql_num_rows( $res ) > 0 ) $toUser = mysql_result( $res, 0, 0 );
	  }
	}
  }
  $subject = '';
  $message = '';
  
  $action = $_SERVER['PHP_SELF'].'?action=sendMail';
  // Если при заполнении формы были допущены ошибки
  if ( isset( $_SESSION['sendMailForm'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['sendMailForm']['error'], $info );
	$html = $html.$info."\n";
	$toUser  = htmlspecialchars( $_SESSION['sendMailForm']['toUser'] );
	$subject = htmlspecialchars( $_SESSION['sendMailForm']['subject'] );
	$message = htmlspecialchars( $_SESSION['sendMailForm']['message'] );
	unset( $_SESSION['sendMailForm'] );
  }
  
  $tpl = file_get_contents( 'templates/sendMailForm.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{toUser}', $toUser, $tpl );
  $tpl = str_replace( '{subject}', $subject, $tpl );
  $tpl = str_replace( '{message}', $message, $tpl );
  
  $html = $html.$tpl;
  
  return $html;
}

// Отправка письма пользователю сайта
function sendMail()
{
  // Если не переданы данные формы - функция вызвана по ошибке
  if ( !isset( $_POST['toUser'] ) or
	   !isset( $_POST['subject'] ) or
	   !isset( $_POST['message'] ) )
  {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();
  }
  // Если письмо пытается отправить незарегистрированный пользователь
  if ( !isset( $_SESSION['user'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();  
  }

  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $toUser  = substr( $_POST['toUser'], 0, 30 );
  $subject = substr( $_POST['subject'], 0, 60 );
  $message = substr( $_POST['message'], 0, MAX_MAILBODY_LENGTH );
  // Обрезаем лишние пробелы
  $toUser  = trim( $toUser );
  $subject = trim( $subject );
  $message = trim( $message );
  
  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $toUser ) ) $error = $error.'<li>не заполнено поле "Для пользователя"</li>'."\n";
  if ( empty( $subject ) ) $error = $error.'<li>не заполнено поле "Заголовок письма"</li>'."\n";
  if ( empty( $message ) ) $error = $error.'<li>не заполнено поле "Текст письма"</li>'."\n";
  // Проверяем поля формы на недопустимые символы
  if ( !empty( $toUser ) and !preg_match( "#^[- _0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $toUser ) )
    $error = $error.'<li>поле "Для пользователя" содержит недопустимые символы</li>'."\n";
  if ( !empty( $subject ) and !preg_match( "#^[-.;:,?!\/)(_\"\s0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $subject ) )
    $error = $error.'<li>поле "Заголовок письма" содержит недопустимые символы</li>'."\n";
  // Проверяем, есть ли такой пользователь
  if ( !empty( $toUser ) ) {
    $to = preg_replace( "#[^- _0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]#i", '', $toUser );
    $query = "SELECT id_author, name, email FROM ".TABLE_USERS." WHERE name='".$to."' LIMIT 1";
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Произошла ошибка при отправке письма';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, '' );
    }
    if ( mysql_num_rows( $res ) == 0 ) 
      $error = $error.'<li>пользователь с именем <strong>'.$to.'</strong> не зарегистрирован</li>'."\n";
  }
  // Если были допущены ошибки при заполнении формы - 
  // перенаправляем посетителя для исправления ошибок
  if ( !empty( $error ) )
  {
	$_SESSION['sendMailForm'] = array();
	$_SESSION['sendMailForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
	$_SESSION['sendMailForm']['toUser'] = $toUser;
	$_SESSION['sendMailForm']['subject'] = $subject;
	$_SESSION['sendMailForm']['message'] = $message;
	header( 'Location: '.$_SERVER['PHP_SELF'].'?action=sendMailForm' );
	die();
  }
  $toUser = mysql_fetch_array( $res );
  $fromUser = $_SESSION['user']['name'];

  $message = 'ОТ: '.$fromUser."\n".'ТЕМА: '.$subject."\n\n".$message;
	
  // формируем заголовки письма
  $headers = "From: ".$_SERVER['SERVER_NAME']." <".ADMIN_EMAIL.">\n";
  $headers = $headers."Content-type: text/plain; charset=\"utf-8\"\n";
  $headers = $headers."Return-path: <".ADMIN_EMAIL.">\n";
  $subject = 'Письмо с форума '.$_SERVER['SERVER_NAME'].' от '.$fromUser;
  $subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?=';
  if ( mail( $toUser['email'], $subject, $message, $headers ) )
    return showInfoMessage( 'Ваше письмо успешно отправлено', '' );
  else
    return showInfoMessage( 'Произошла ошибка при отправке письма', '' );
}

// Вспомогательная функция - после выполнения пользователем каких-либо действий
// выдает информационное сообщение и делает редирект на нужную страницу с задержкой
function showInfoMessage( $message, $queryString )
{
  if ( !empty( $queryString ) ) $queryString = '?'.$queryString;
  header( 'Refresh: '.REDIRECT_DELAY.'; url='.$_SERVER['PHP_SELF'].$queryString );
  $html = file_get_contents( './templates/infoMessage.html' );
  $html = str_replace( '{infoMessage}', $message, $html );
  return $html;
}

// Вспомогательная функция - выдает сообщение об ошибке 
// и делает редирект на нужную страницу с задержкой
function showErrorMessage( $message = '', $error = '', $redirect = false, $queryString = '' )
{
  if ( $redirect ) {
    if ( !empty( $queryString ) ) $queryString = '?'.$queryString;
    header( 'Refresh: '.REDIRECT_DELAY.'; url='.$_SERVER['PHP_SELF'].$queryString );
  }
  $html = file_get_contents( './templates/infoMessage.html' );
  $html = str_replace( '{infoMessage}', $message, $html );
  if ( DEBUG_MODE ) {
    $tpl = file_get_contents( './templates/errorMessage.html' );
    $tpl = str_replace( '{errorMessage}', $error, $tpl );
    $html = $html.$tpl."\n";
  }
  return $html;
}

// Функция возвращает true или false в зависимости от того, имеет ли
// право пользователь редактировать сообщение (пост)
function hasRightEditPost( $post )
{
  // Незарегистрированный пользователь не имеет право редактировать сообщения
  if ( !isset( $_SESSION['user'] ) ) return false;
  // Если пользователь - администратор или модератор, он имеет право 
  // редактировать любые сообщения (посты)
  if ( $_SESSION['user']['status'] != 'user' ) return true;
  // Обычный пользователь не может редактировать чужие сообщения (посты)
  if ( $_SESSION['user']['id_author'] != $post['id_author'] ) return false;
  // Пользователь не может редактировать сообщение, если оно заблокировано
  if ( $post['locked'] == 1 ) return false;
  // Обычный пользователь может редактировать свое сообщение, 
  // только если на него не было ответов
  $query = "SELECT id_post FROM ".TABLE_POSTS." 
            WHERE id_theme=".$post['id_theme']." AND time>'".$post['time']."'";
  $res = mysql_query( $query );
  if ( !$res ) return false;
  if ( mysql_num_rows( $res ) == 0 ) 
    return true;
  else
    return false;
}

// Функция возвращает true или false в зависимости от того, имеет ли
// право пользователь удалить это сообщение (пост)
function hasRightDeletePost( $post )
{
  // Незарегистрированный пользователь не имеет право удалять сообщения
  if ( !isset( $_SESSION['user'] ) ) return false;
  // Если пользователь - администратор или модератор, он имеет право 
  // удалять любые сообщения (посты)
  if ( $_SESSION['user']['status'] != 'user' ) return true;
  // Обычный пользователь не может удалять чужие сообщения (посты)
  if ( $_SESSION['user']['id_author'] != $post['id_author'] ) return false;
  // Пользователь не может удалять сообщение, если оно заблокировано
  if ( $post['locked'] == 1 ) return false; 
  // Обычный  пользователь имеет право удалять свои
  // сообщения, если на них еще не было ответа
  $query = "SELECT id_post FROM ".TABLE_POSTS." 
            WHERE id_theme=".$post['id_theme']." AND time>'".$post['time']."'";
  $res = mysql_query( $query );
  if ( !$res ) return false;
  if ( mysql_num_rows( $res ) == 0 ) 
    return true;
  else
    return false;
}

// Эта функция производит обновление времени последнего посещения зарегистрированного
// пользователя. Вызывается при каждом просмотре страницы форума зарегестрированным
// пользователем (если пользователь авторизовался)
function setTimeVisit()
{
  $query = "UPDATE ".TABLE_USERS." 
	        SET last_visit=NOW() 
			WHERE id_author=".$_SESSION['user']['id_author'];
  mysql_query( $query );
}

// Функция возвращает html формы для авторизации на форуме
function getLoginForm()
{
  $html = '';
  if ( isset( $_SESSION['loginForm']['error'] ) ) {
    $info = file_get_contents( './templates/infoMessage.html' );
	$info = str_replace( '{infoMessage}', $_SESSION['loginForm']['error'], $info );
	$html = $html.$info."\n";
	unset( $_SESSION['loginForm']['error'] );
  }  
  $action = $_SERVER['PHP_SELF'].'?action=login';
  $newPassword = '<a href="'.$_SERVER['PHP_SELF'].'?action=newPasswordForm">Забыли пароль?</a>'."\n";
  $tpl = file_get_contents( './templates/loginForm.html' );
  $tpl = str_replace( '{action}', $action, $tpl );
  $tpl = str_replace( '{newpassword}', $newPassword, $tpl );
  $html = $html . $tpl;
  return $html;
}

// Вход на форум - обработчик формы авторизации
function login()
{
  // Если не переданы данные формы - значит функция была вызвана по ошибке
  if ( !isset( $_POST['username'] ) or
       !isset( $_POST['password'] ) )
  {
	header( 'Location: '.$_SERVER['PHP_SELF'] );
	die(); 
  }

  // Защита от перебора пароля - при каждой неудачной попытке время задержки увеличивается
  if ( isset( $_SESSION['loginForm']['count'] ) ) sleep( $_SESSION['loginForm']['count'] );
  
  // Обрезаем переменные до длины, указанной в параметре maxlength тега input
  $name      = substr( $_POST['username'], 0, 30 );
  $password  = substr( $_POST['password'], 0, 30 );
  
  // Обрезаем лишние пробелы
  $name      = trim( $name );
  $password  = trim( $password );

  // Проверяем, заполнены ли обязательные поля
  $error = '';
  if ( empty( $name ) ) $error = $error.'<li>не заполнено поле "Имя"</li>'."\n";
  if ( empty( $password ) ) $error = $error.'<li>не заполнено поле "Пароль"</li>'."\n";

  // Проверяем поля формы на недопустимые символы
  if ( !empty( $name ) and !preg_match( "#^[- _0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $name ) )
    $error = $error.'<li>поле "Имя" содержит недопустимые символы</li>'."\n";
  if ( !empty( $password ) and !preg_match( "#^[-_0-9A-ZАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя]+$#i", $password ) )
    $error = $error.'<li>поле "Пароль" содержит недопустимые символы</li>'."\n";

  // Проверять существование такого пользователя есть смысл только в том
  // случае, если поля не пустые и не содержат недопустимых символов
  if ( empty( $error ) ) {	
    // Выполняем запрос на получение данных пользователя из БД
    $query = "SELECT *, UNIX_TIMESTAMP(last_visit) as unix_last_visit 
              FROM ".TABLE_USERS." 
              WHERE name='".mysql_real_escape_string( $name )."'
			  AND passw='".mysql_real_escape_string( md5( $password ) )."'
			  LIMIT 1";
    $res = mysql_query( $query );
    if ( !$res ) {
      $msg = 'Ошибка при авторизации пользователя';
	  $err = 'Ошибка при выполнении запроса: <br/>'.
	         $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	         '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	  return showErrorMessage( $msg, $err, true, 'action=loginForm' );
    }
	if ( mysql_num_rows( $res ) == 0 )
	  $error = $error.'<li>Неправильный логин или пароль</li>'."\n";
  }
	
  // Если были допущены ошибки при заполнении формы
  if ( !empty( $error ) ) {
	if ( !isset( $_SESSION['loginForm']['count'] ) )
		$_SESSION['loginForm']['count'] = 1;
	else
		$_SESSION['loginForm']['count'] = $_SESSION['loginForm']['count'] + 1;
    $_SESSION['loginForm']['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>'.
	"\n".'<ul class="errorMsg">'."\n".$error.'</ul>'."\n";
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=loginForm' );
    die();
  }
  
  // Все поля заполнены правильно и такой пользователь существует - продолжаем...
  unset( $_SESSION['loginForm'] );
  $user = mysql_fetch_assoc( $res );

  if ( !empty( $user['activation'] ) ) 
    return showInfoMessage( 'Ваша учетная запись не активирована', '' );
	
  // Если пользователь заблокирован
  if ( $user['locked'] )
    return showInfoMessage( 'Ваша учетная запись заблокирована. Обратитесь к администратору.', '' );
  
  $_SESSION['user'] = $user;
  
  // Функция getNewThemes() помещает в массив $_SESSION['newThemes'] ID тем, 
  // в которых были новые сообщения со времени последнего посещения пользователя
  getNewThemes();
  
  // Выставляем cookie, если пользователь хочет входить на форум автоматически
  if ( isset ( $_POST['autologin'] ) ) {
    $tmppos = strrpos( $_SERVER['PHP_SELF'], '/' ) + 1;
	$path = substr( $_SERVER['PHP_SELF'], 0, $tmppos );
	setcookie( 'autologin', 'yes', time() + 3600*24*COOKIE_TIME, $path );
	setcookie( 'username', $_SESSION['user']['name'], time() + 3600*24*COOKIE_TIME, $path );
	setcookie( 'password', $_SESSION['user']['passw'], time() + 3600*24*COOKIE_TIME, $path );
  }
  // Авторизация прошла успешно - перенаправляем посетителя на главную страницу
  header( 'Location: '.$_SERVER['PHP_SELF'] );
  die();
}

// Функция осуществляет автоматический вход на форум
function autoLogin()
{
  // Если не установлены cookie, содержащие логин и пароль
  if ( !isset( $_COOKIE['username'] ) or !isset( $_COOKIE['password'] ) ) {
    $tmppos = strrpos( $_SERVER['PHP_SELF'], '/' ) + 1;
	$path = substr( $_SERVER['PHP_SELF'], 0, $tmppos );
	if ( isset( $_COOKIE['username'] ) ) setcookie( 'username', '', time() - 1, $path );
	if ( isset( $_COOKIE['password'] ) ) setcookie( 'password', '', time() - 1, $path );
	if ( isset( $_COOKIE['autologin'] ) ) setcookie( 'autologin', '', time() - 1, $path );
    return false;	
  }
  // Проверяем переменные cookie на недопустимые символы
  $name = preg_replace( "#[^- _0-9a-zА-Яа-я]#i", '', $_COOKIE['username'] );
  // Т.к. пароль зашифрован с помощью md5, то он представляет собой 
  // 32-значное шестнадцатеричное число
  $password = substr( $_COOKIE['password'], 0, 32 );
  $password = preg_replace( "#[^0-9a-f]#i", '', $password );

  // Выполняем запрос на получение данных пользователя из БД
  $query = "SELECT *, UNIX_TIMESTAMP(last_visit) as unix_last_visit 
            FROM ".TABLE_USERS." 
            WHERE name='".mysql_real_escape_string( $name )."'
			AND passw='".mysql_real_escape_string( $password )."'
			LIMIT 1";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при авторизации пользователя';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  // Если пользователь с таким логином и паролем не найден - 
  // значит данные неверные и надо их удалить
  if ( mysql_num_rows( $res ) == 0 ) {
    $tmppos = strrpos( $_SERVER['PHP_SELF'], '/' ) + 1;
	$path = substr( $_SERVER['PHP_SELF'], 0, $tmppos );
	setcookie( 'autologin', '', time() - 1, $path );
	setcookie( 'username', '', time() - 1, $path );
	setcookie( 'password', '', time() - 1, $path );
    return false;	
  }
  
  $user = mysql_fetch_assoc( $res );
  if ( !empty( $user['activation'] ) ) {
    $tmppos = strrpos( $_SERVER['PHP_SELF'], '/' ) + 1;
	$path = substr( $_SERVER['PHP_SELF'], 0, $tmppos );
	setcookie( 'autologin', '', time() - 1, $path );
	setcookie( 'username', '', time() - 1, $path );
	setcookie( 'password', '', time() - 1, $path );
    return showInfoMessage( 'Ваша учетная запись не активирована', '' );
  }
  
  // Если пользователь заблокирован
  if ( $user['locked'] ) {
    $tmppos = strrpos( $_SERVER['PHP_SELF'], '/' ) + 1;
	$path = substr( $_SERVER['PHP_SELF'], 0, $tmppos );
	setcookie( 'autologin', '', time() - 1, $path );
	setcookie( 'username', '', time() - 1, $path );
	setcookie( 'password', '', time() - 1, $path );
    return showInfoMessage( 'Ваша учетная запись заблокирована. Обратитесь к администратору.', '' );
  }
  
  $_SESSION['user'] = $user;

  // Функция getNewThemes() помещает в массив $_SESSION['newThemes'] ID тем, 
  // в которых были новые сообщения со времени последнего посещения пользователя
  getNewThemes();
  
  return true;
}

// Выход из системы
function logout()
{
  unset( $_SESSION['user'] );
  if ( isset( $_SESSION['newThemes'] ) ) unset( $_SESSION['newThemes'] );
  $tmppos = strrpos( $_SERVER['PHP_SELF'], '/' ) + 1;
  $path = substr( $_SERVER['PHP_SELF'], 0, $tmppos );
  if ( isset( $_COOKIE['autologin'] ) ) setcookie( 'autologin', '', time() - 1, $path );
  if ( isset( $_COOKIE['username'] ) ) setcookie( 'username', '', time() - 1, $path );
  if ( isset( $_COOKIE['password'] ) ) setcookie( 'password', '', time() - 1, $path ); 
  header( 'Location: '.$_SERVER['PHP_SELF'] );
  die();  
}

// Функция возвращает html формы для поиска по форуму
function searchForm()
{
  $html = '';
  
  $query = "SELECT id_forum, name FROM ".TABLE_FORUMS." WHERE 1 ORDER BY pos";
  $res = mysql_query( $query );
  if ( !$res ) {
    $msg = 'Ошибка при формировании формы для поиска';
	$err = 'Ошибка при выполнении запроса: <br/>'.
	       $query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.
	       '(Файл '. __FILE__ .', строка '. __LINE__ .')';
	return showErrorMessage( $msg, $err, true, '' );
  }
  if ( mysql_num_rows( $res ) > 0 ) {
    $options = '<option value="0">Все имеющиеся</option>'."\n";
    while( $forum = mysql_fetch_row( $res ) ) {
	  $options = $options.'<option value="'.$forum[0].'">'.$forum[1].'</option>'."\n";
	}
    $html = file_get_contents( './templates/searchForm.html' );
	$action = $_SERVER['PHP_SELF'].'?action=searchResult';
	$html = str_replace( '{options}', $options, $html );
	$html = str_replace( '{action}', $action, $html );
  }
  
  return $html;
}

function searchResult()
{

  if ( isset( $_POST['words'] ) and
	   isset( $_POST['id_forum'] ) and
       isset( $_POST['where'] ) )
  {	
    if ( empty( $_POST['words'] ) ) {
      header( 'Location: '.$_SERVER['PHP_SELF'].'?action=searchForm' );
      die();  
    }
	// Обрезаем строку до длины, указанной в атрибуте maxlength
    $search = substr( $_POST['words'], 0, 64 );
    // Убираем пробелы в начале и конце строки поиска
    $search = trim( $search );
    // Убираем все "ненормальные" символы
    $good = preg_replace("#[^a-zа-я\s\-]#i", " ", $search);
	$good = trim( $good );
    if ( empty( $good ) ) {
      header( 'Location: '.$_SERVER['PHP_SELF'].'?action=searchForm' );
      die();  
    }
    // Сжимаем двойные пробелы
    $good = ereg_replace(" +", " ", $good);
		
    // Получаем корни искомых слов
    $stemmer = new Lingua_Stem_Ru();
    $tmp = explode( " ", $good );
    foreach ( $tmp as $wrd ) {
      // Если слово слишком короткое - не используем его
      if ( strlen($wrd) < 3 ) continue;
      $words[] = $stemmer->stem_word($wrd);
    }
    // Склеиваем массив $words обратно в строку
    $string = implode( "* ", $words );
    $string = $string."*";
  
    // Теперь надо выяснить, где будем искать
    $where = $_POST['where'];
    $whereArray = array( 'themes', 'posts', 'everywhere' );
    if ( !in_array( $where, $whereArray ) ) $where = 'themes';
	
    // Записываем все данные в сессию - это нам понадобится при 
    // построении постраничной навигации результатов поиска
    $_SESSION['search']['query'] = $search;
    $_SESSION['search']['good'] = $good;
	$_SESSION['search']['words'] = $words;
	// Это нам потребуется для подсветки искомых слов
	$_SESSION['search']['words'] = implode( '|', $words );
    $_SESSION['search']['string'] = $string;
	$_SESSION['search']['where'] = $where;
	$id_forum = (int)$_POST['id_forum'];
	if ( $id_forum < 0 ) $id_forum = 0;
	$_SESSION['search']['id_forum'] = $id_forum;
	
    header( 'Location: '.$_SERVER['PHP_SELF'].'?action=searchResult' );
    die();
  }
  
  if ( !isset( $_SESSION['search'] ) ) {
    header( 'Location: '.$_SERVER['PHP_SELF'] );
    die();  
  }
	
  // Если поиск осуществляется по названиям тем
  if ( $_SESSION['search']['where'] == 'themes' )
    $result = searchResultThemes();
  else if ( $_SESSION['search']['where'] == 'posts' )
    $result = searchResultPosts();
  else
    $result = searchResultEverywhere();
	
  $html = '<table class="showTable">'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'<th>Результаты поиска</th>'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'</table>'."\n";

  $html = $html.$result."\n";

  return $html;
}

// Поиск только в названиях тем
function searchResultThemes()
{
  // Составляем запрос к БД, чтобы узнать количество записей в результатах поиска - 
  // это нужно для построения постраничной навигации
  $forum = '';
  if ( $_SESSION['search']['id_forum'] ) $forum = ' AND id_forum='.$_SESSION['search']['id_forum'];
  $query = "SELECT COUNT(*)
            FROM ".TABLE_THEMES." 
            WHERE MATCH (name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE)".$forum;		   
  $res = mysql_query( $query );
  $total = mysql_result( $res, 0, 0 );
  if ( $total == 0 ) return 'По вашему запросу ничего не найдено';
 
  // Число страниц результатов поиска (постраничная навигация)
  $cntPages = ceil( $total / SEARCH_THEMES_PER_PAGE );
  
  // Проверяем передан ли номер текущей страницы (постраничная навигация)
  if ( isset($_GET['page']) ) {
    $page = (int)$_GET['page'];
    if ( $page < 1 ) $page = 1;
  } else {
    $page = 1;
  }

  if ( $page > $cntPages ) $page = $cntPages;
  // Начальная позиция (постраничная навигация)
  $start = ( $page - 1 ) * SEARCH_THEMES_PER_PAGE;

  // Строим постраничную навигацию, если это необходимо
  if ( $cntPages > 1 ) {
    // Функция возвращает html меню для постраничной навигации
    $pages = pageIterator( $page, $cntPages, $_SERVER['PHP_SELF'].'?action=searchResult' );		   
  }

  $query = "SELECT id_theme, name, id_forum 
            FROM ".TABLE_THEMES." 
            WHERE MATCH (name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE)".$forum."
			ORDER BY MATCH (name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE) DESC 
			LIMIT ".$start.", ".SEARCH_THEMES_PER_PAGE;			
  $res = mysql_query( $query );
  $html = "<ul>\n";
  while ( $theme = mysql_fetch_array( $res ) ) {
    $html = $html.'<li><a class="topictitle" href="'.$_SERVER["PHP_SELF"]."?action=showTheme".
	        '&idForum='.$theme['id_forum'].'&id_theme='.$theme['id_theme'].'&page=1">'.
			$theme['name'].'</a></li>'."\n";	
  }
  $html = $html."</ul>\n";

  // Постраничная навигация
  if ( isset( $pages ) ) $html = $html.$pages."\n";
  
  return $html;	
}

// Поиск в сообщениях (постах)
function searchResultPosts()
{
  // Составляем запрос к БД, чтобы узнать количество записей в результатах поиска - 
  // это нужно для построения постраничной навигации
  $forum = '';
  if ( $_SESSION['search']['id_forum'] ) $forum = ' AND b.id_forum='.$_SESSION['search']['id_forum'];
  $query = "SELECT COUNT(*) 
            FROM ".TABLE_POSTS." a INNER JOIN ".TABLE_THEMES." b
            ON a.id_theme=b.id_theme 
            WHERE MATCH (a.name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE)".$forum;
			
  $res = mysql_query( $query );
  $total = mysql_result( $res, 0, 0 );

  if ( $total == 0 ) return 'По вашему запросу ничего не найдено';
 
  // Число страниц результатов поиска (постраничная навигация)
  $cntPages = ceil( $total / SEARCH_POSTS_PER_PAGE );
  
  // Проверяем передан ли номер текущей страницы (постраничная навигация)
  if ( isset($_GET['page']) ) {
    $page = (int)$_GET['page'];
    if ( $page < 1 ) $page = 1;
  } else {
    $page = 1;
  }

  if ( $page > $cntPages ) $page = $cntPages;
  // Начальная позиция (постраничная навигация)
  $start = ( $page - 1 ) * SEARCH_POSTS_PER_PAGE;

  // Строим постраничную навигацию, если это необходимо
  if ( $cntPages > 1 ) {
    // Функция возвращает html меню для постраничной навигации
    $pages = pageIterator( $page, $cntPages, $_SERVER['PHP_SELF'].'?action=searchResult' );		   
  }

  $query = "SELECT a.id_post, a.name, a.id_theme, b.id_forum, b.name 
            FROM ".TABLE_POSTS." a INNER JOIN ".TABLE_THEMES." b
            ON a.id_theme=b.id_theme 
            WHERE MATCH (a.name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE)".$forum."
			ORDER BY MATCH (a.name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE) DESC
			LIMIT ".$start.", ".SEARCH_POSTS_PER_PAGE;
		
  $res = mysql_query( $query );
  $html = '';
  while ( $post = mysql_fetch_row( $res ) ) {
    
    $html = $html.'<div style="margin: 5px 0 5px 0">'."\n";
	$html = $html.'<img src="./images/folder.gif" width="19" height="18" alt="" align="top" />
	        <a class="topictitle" href="'.$_SERVER["PHP_SELF"]."?action=showTheme".
	        '&idForum='.$post[3].'&id_theme='.$post[2].'&page=1">'.$post[4].'</a>'."\n";
	$html = $html.'</div>'."\n";		
	
	$html = $html.'<table class="postTable">'."\n";
    $html = $html.'<tr>'."\n";
	$html = $html.'<td>'."\n";
	$message = print_page( $post[1] );
	$message = preg_replace("/\b(".$_SESSION['search']['words'].")(.*?)\b/i", 
	           "<span style='color:red; font-weight:bold'>\\0</span>", $message);
    $html = $html.$message."\n";
	$html = $html.'</td>'."\n";
	$html = $html.'</tr>'."\n";
	$html = $html."</table>\n";
  }

  // Постраничная навигация
  if ( isset( $pages ) ) $html = $html.$pages."\n";
  
  return $html;	
}

// Поиск в названиях тем и сообщениях
function searchResultEverywhere()
{
  // Составляем запрос к БД, чтобы узнать количество записей в результатах поиска - 
  // это нужно для построения постраничной навигации
  $forum = '';
  if ( $_SESSION['search']['id_forum'] ) $forum = ' AND b.id_forum='.$_SESSION['search']['id_forum'];
  $query = "SELECT COUNT(*) 
            FROM ".TABLE_POSTS." a INNER JOIN ".TABLE_THEMES." b
            ON a.id_theme=b.id_theme 
            WHERE MATCH (a.name, b.name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE)".$forum;
			
  $res = mysql_query( $query );
  $total = mysql_result( $res, 0, 0 );

  if ( $total == 0 ) return 'По вашему запросу ничего не найдено';
 
  // Число страниц результатов поиска (постраничная навигация)
  $cntPages = ceil( $total / SEARCH_EVERYWHERE_PER_PAGE );
  
  // Проверяем передан ли номер текущей страницы (постраничная навигация)
  if ( isset($_GET['page']) ) {
    $page = (int)$_GET['page'];
    if ( $page < 1 ) $page = 1;
  } else {
    $page = 1;
  }

  if ( $page > $cntPages ) $page = $cntPages;
  // Начальная позиция (постраничная навигация)
  $start = ( $page - 1 ) * SEARCH_EVERYWHERE_PER_PAGE;

  // Строим постраничную навигацию, если это необходимо
  if ( $cntPages > 1 ) {
    // Функция возвращает html меню для постраничной навигации
    $pages = pageIterator( $page, $cntPages, $_SERVER['PHP_SELF'].'?action=searchResult' );		   
  }

  $query = "SELECT a.id_post, a.name, a.id_theme, b.id_forum, b.name 
            FROM ".TABLE_POSTS." a INNER JOIN ".TABLE_THEMES." b
            ON a.id_theme=b.id_theme 
            WHERE MATCH (a.name, b.name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE)".$forum."
			ORDER BY MATCH (a.name, b.name) AGAINST ('".$_SESSION['search']['string']."' IN BOOLEAN MODE) DESC
			LIMIT ".$start.", ".SEARCH_EVERYWHERE_PER_PAGE;
		
  $res = mysql_query( $query );
  $html = ''; 
  while ( $post = mysql_fetch_row( $res ) ) {
  
    $html = $html.'<div style="margin: 5px 0 5px 0">'."\n";
	$html = $html.'<img src="./images/folder.gif" width="19" height="18" alt="" align="top" />
	        <a class="topictitle" href="'.$_SERVER["PHP_SELF"]."?action=showTheme".
	        '&idForum='.$post[3].'&id_theme='.$post[2].'&page=1">'.$post[4].'</a>'."\n";
	$html = $html.'</div>'."\n";		
	
	$html = $html.'<table class="postTable">'."\n";	
    $html = $html.'<tr>'."\n";
	$html = $html.'<td>'."\n";
	$message = print_page( $post[1] );
	$message = preg_replace("/\b(".$_SESSION['search']['words'].")(.*?)\b/i", 
	           "<span style='color:red; font-weight:bold'>\\0</span>", $message);
    $html = $html.$message."\n";
	$html = $html.'</td>'."\n";
	$html = $html.'</tr>'."\n";
	$html = $html."</table>\n";
  }
  
  // Постраничная навигация
  if ( isset( $pages ) ) $html = $html.$pages."\n";
  
  return $html;
}

// Функция getNewThemes() помещает в массив $_SESSION['newThemes'] ID тем, 
// в которых были новые сообщения со времени последнего посещения пользователя
function getNewThemes()
{
  // Получаем список тем форума, где были новые сообщения
  $query = "SELECT a.id_theme, MAX(UNIX_TIMESTAMP(b.time)) AS unix_last_post
	        FROM ".TABLE_THEMES." a INNER JOIN ".TABLE_POSTS." b
			ON a.id_theme=b.id_theme 
			GROUP BY a.id_theme
			HAVING unix_last_post>".$_SESSION['user']['unix_last_visit'];

  $res = mysql_query( $query );
  if ( $res ) {
    while ( $id = mysql_fetch_row( $res ) ) {
	  $_SESSION['newThemes'][$id[0]] = $id[0];	  
	}	
  }
}

// Функция countNewMessages() возвращает количество
// личных сообщений, которые пользователь еще не прочитал
function countNewMessages()
{

  $query = "SELECT COUNT(*)
	        FROM ".TABLE_MESSAGES."
			WHERE to_user=".$_SESSION['user']['id_author']." 
            AND viewed=0 AND id_rmv<>".$_SESSION['user']['id_author'];
  $res = mysql_query( $query );
  if ( $res ) 
    return mysql_result( $res, 0, 0 );
  else
    return 0;

}

// Функция возвращает html меню для постраничной навигации
function pageIterator( $page, $cntPages, $url )
{

  $html = '<div class="pagesDiv">&nbsp;Страницы: ';
  // Проверяем нужна ли стрелка "В начало"
  if ( $page > 3 )
    $startpage = '<a class="pages" href="'.$url.'&page=1"><<</a> ... ';
  else
    $startpage = '';
  // Проверяем нужна ли стрелка "В конец"
  if ( $page < ($cntPages - 2) )
    $endpage = ' ... <a class="pages" href="'.$url.'&page='.$cntPages.'">>></a>';
  else
    $endpage = '';

  // Находим две ближайшие станицы с обоих краев, если они есть
  if ( $page - 2 > 0 )
    $page2left = ' <a class="pages" href="'.$url.'&page='.($page - 2).'">'.($page - 2).'</a> | ';
  else
    $page2left = '';
  if ( $page - 1 > 0 )
    $page1left = ' <a class="pages" href="'.$url.'&page='.($page - 1).'">'.($page - 1).'</a> | ';
  else
    $page1left = '';
  if ( $page + 2 <= $cntPages )
    $page2right = ' | <a class="pages" href="'.$url.'&page='.($page + 2).'">'.($page + 2).'</a>';
  else
    $page2right = '';
  if ( $page + 1 <= $cntPages )
    $page1right = ' | <a class="pages" href="'.$url.'&page='.($page + 1).'">'.($page + 1).'</a>';
  else
    $page1right = '';

  // Выводим меню
  $html = $html.$startpage.$page2left.$page1left.'<strong>'.$page.'</strong>'.
          $page1right.$page2right.$endpage."\n";

  $html = $html.'</div>'."\n";

  return $html;
}

// Статистика форума
function getStat()
{
  $html = '<table class="showTable">'."\n";
  $html = $html.'<tr><th>Статистика</th></tr>'."\n";
  $html = $html.'<tr>'."\n";
  $html = $html.'<td>'."\n";
  $html = $html.'<div class="details">'."\n";
  $query = 'SELECT COUNT(*) FROM '.TABLE_POSTS;
  $res = mysql_query( $query );
  if ( !$res ) return '';
  $html = $html.'Наши пользователи оставили сообщений: '.mysql_result( $res, 0, 0 ).'<br/>'."\n";
  $query = 'SELECT COUNT(*) FROM '.TABLE_USERS;
  $res = mysql_query( $query );
  if ( !$res ) return '';
  $html = $html.'Всего зарегистрированных пользователей: '.mysql_result( $res, 0, 0 ).'<br/>'."\n";
  $query = 'SELECT id_author, name FROM '.TABLE_USERS.' ORDER BY id_author DESC LIMIT 1';
  $res = mysql_query( $query );
  if ( !$res ) return '';
  list( $id_user, $name ) = mysql_fetch_array( $res );
  $html = $html.'Последний зарегистрированный пользователь: '.
          '<a href="'.$_SERVER['PHP_SELF'].'?action=showUserInfo&idUser='.
		  $id_user.'">'.$name.'</a><br/>'."\n";
  // Пользователи on-line
  if ( isset( $_SESSION['usersOnLine'] ) ) {
    $cnt = count( $_SESSION['usersOnLine'] );
	$onLine = '';
	if ( $cnt > 0 ) {
      $onLine = $onLine.'Сейчас на форуме: ';
	  foreach ( $_SESSION['usersOnLine'] as $id => $name ) {
	    $onLine = $onLine.'<a href="'.$_SERVER['PHP_SELF'].
		          '?action=showUserInfo&idUser='.$id.'">'.$name.'</a>, ';	
	  }
	  $onLine = substr( $onLine, 0, (strlen( $onLine )-2) );
	}
	$html = $html.$onLine."\n";
  }
  $html = $html.'</div>'."\n";
  $html = $html.'</td>'."\n";
  $html = $html.'</tr>'."\n";
  $html = $html.'</table>'."\n";
  
  return $html;
}

// Функция помещает в массив $_SESSION['usersOnLine'] список зарегистрированных 
// пользователей, которые в настоящий момент просматривают форум
function getUsersOnLine()
{
  $query = "SELECT id_author, name 
            FROM ".TABLE_USERS." 
			WHERE UNIX_TIMESTAMP(last_visit)>".( time() - 60 * TIME_ON_LINE )."
			ORDER BY status DESC";
  $res = mysql_query( $query );
  if ( $res ) {
    if ( isset( $_SESSION['usersOnLine'] ) ) unset( $_SESSION['usersOnLine'] );
    $cnt = mysql_num_rows( $res );
    if ( $cnt > 0 ) {
      for ( $i = 0; $on = mysql_fetch_array( $res ); $i++ ) {
	    $_SESSION['usersOnLine'][$on['id_author']] = $on['name'];
      }
    }
  }
  return;
}

// Функция возвращает форму для быстрого ответа в тему
function getQuickReplyForm( $id_theme )
{
  $html = file_get_contents( './templates/quickReplyForm.html' );
  $action = $_SERVER['PHP_SELF'].'?action=quickReply&idForum='.$_GET['idForum'].'&id_theme='.$id_theme;
  $html = str_replace( '{action}', $action, $html );
  return $html;
}

// Возвращает размер файла в Кб
function getFileSize( $file )
{
  return number_format( (filesize($file)/1024), 2, '.', '' );
}

?>