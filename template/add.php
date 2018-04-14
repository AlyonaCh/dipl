<?php
include ('../connect.php');
include ('../model/baza.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Добавление Вопроса</title>
  </head>
  <body>
    <a href="logaut.php">Выход</a>
    <form method="GET" enctype="multipart/form-data">
        <label>Выбирите тему</label><br>
        <select name="catego"><?php foreach ($selec as $sel) : ?>
          <option><?= $sel['catego'];?></option><?php endforeach ?>
        <select><br>
        <label>Ваш вопрос</label><br>
        <input type="text" name="qwest" size="50"><br>
        <label>Ваше имя</label><br>
        <input type="text" name="name" size="20"><br>
        <label>Ваш email</label><br>
        <input type="text" name="email" size="20"><br>
        <input type="submit" name="add" value="Задать вопрос">
    </form>
  </body>
</html>
