<?php
  session_start();
  $number_1 = rand(1, 99); // Генерируем 1-е случайное число
  $number_2 = rand(1, 99); // Генерируем 2-е случайное число
  $_SESSION['rand_code'] = $number_1 + $number_2; // Записываем их сумму в сессию
  $dir = "fonts/"; // Директория с шрифтами
  $image = imagecreatetruecolor(130, 50); // Создаём изображение
  $color = imagecolorallocate($image, 80, 100, 220); // Задаём 1-й цвет
  $white = imagecolorallocate($image, 255, 255, 255); // Задаём 2-й цвет
  imagefilledrectangle($image, 0, 0, 399, 99, $white); // Делаем капчу с белым фоном
  imagettftext ($image, 20, 0, 10, 35, $color, $dir."verdana.ttf", "$number_1 + $number_2"); // Пишем текст
  header("Content-type: image/png"); // Отсылаем заголовок о том, что это изображение png
  imagepng($image); // Выводим изображение
?>