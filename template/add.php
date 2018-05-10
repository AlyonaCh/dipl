<?php
echo $_SERVER['REQUEST_URI'];
//include ('../connect.php');
//include ('../lib/Router.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Добавление Вопроса</title>
  </head>
  <body>
    <a href="logaut.php">Выход</a>
    <form method="POST" action="">
        <label>Выбирите тему</label><br>
        <select name="catego">{% for sel in selec %}
          <option>{{sel['catego']}}</option>{% endfor %}
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
