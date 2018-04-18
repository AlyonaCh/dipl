<?php
session_start();
//include ('../connect.php');
//include ('../model/baza.php');
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Вопросы и ответы</title>
  </head>
  <body>
    <h1>Страница вопрсов</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Логин</label><br>
        <input type="text" name="login" size="20"><br>
        <label>Пароль</label><br>
        <input type="password" name="pass" size="20"><br>
        <input type="submit" name="vhod" value="Войти">
    </form>
    <form method="GET" action="add.php">
        <input type="submit" name="addd" value="Задать вопрос">
    </form>
    <table border="1">
    <tr>
      <th>Категория</th>
      <th>Вопрос</th>
      <th>Ответ</th>
    </tr>
	 <?php foreach ($questions as $quest) { ?>
		<tr>
			<td><?= $quest['catego'];?></td>
			<td><?= $quest['quest'];?></td>
			<td><?= $quest['answer'];?></td>
		</tr>
	<?php } ?>
</table>
  </body>
</html>
