<?php
// Названое форума
define ( 'FORUM_TITLE', 'Форум владельцев электромобилей' );
// Описание форума форума
define ( 'FORUM_DESCRIPTION', 'Форум для тех, кто является владельцем электрического транспортного средства' );
// Названия таблиц в БД форума
define ( 'TABLE_FORUMS', 'forums' );
define ( 'TABLE_THEMES', 'themes' );
define ( 'TABLE_POSTS', 'posts' );
define ( 'TABLE_USERS', 'authors' );
define ( 'TABLE_MESSAGES', 'messages' );
// Максимальный размер файла вложения в байтах
define ( 'MAX_FILE_SIZE', 524288 );
// Максимальный размер файла аватара в байтах
define ( 'MAX_AVATAR_SIZE', 65536 );
// Минимальная длина пароля пользователя
define ( 'MIN_PASSWORD_LENGTH', 6 );
// Адрес электронной почты администратора; этот e-mail
// будет указан в поле FROM писем, которое один пользователь
// напишет другому; этот же e-mail будет указан в письмах
// с просьбой активировать учетную запись или пароль 
// (в случае его утери)
define ( 'ADMIN_EMAIL', 'admin-electromobile@mail.ru' );
// Под этим именем будет показано сообщение (пост) 
// не зарегистрированного пользователя
define ( 'NOT_REGISTERED_USER', 'Посетитель' );
// Максимальная длина тела сообщения (поста)
define ( 'MAX_POST_LENGTH', 12288 );
// Максимальная длина тела личного сообщения, которое
// один пользователь форума может написать другому
define ( 'MAX_MESSAGE_LENGTH', 8192 );
// Максимальная длина тела письма, которое один пользователь форума
// может написать другому
define ( 'MAX_MAILBODY_LENGTH', 8192 );
// Задержка в секундах перед редиректом; когда пользователь выполняет
// какое-то действие (например, добавляет сообщение) ему выдается
// сообщение, что "Ваше сообщение было успешно добавлено" и делается
// редирект на нужную страницу
define ( 'REDIRECT_DELAY', 2 );
// Включает режим отладки - на экран выдаются сообщения об ошибках
define ( 'DEBUG_MODE', 1 );
// Время жизни cookies в днях; в cookies сохраняются логин и пароль пользователя,
// если была выбрана опция "Автоматически входить при каждом посещении"
define ( 'COOKIE_TIME', 30 );
// Максимальное количество личных сообщений в папках
// "Входящие" и "Исходящие"
define ( 'MAX_COUNT_MESSAGES', 50 );
// Время в минутах, в течение которого считается, что пользователь "on-line"
define ( 'TIME_ON_LINE', 5 );
// Количество пользователей на одну страницу
// в списке зарегистрированных пользователей
define ( 'USERS_PER_PAGE', 20 );
// Количество сообщений (постов) на одной странице
define ( 'POSTS_PER_PAGE', 5 );
// Количество тем на одной странице
define ( 'THEMES_PER_PAGE', 20 );
?>