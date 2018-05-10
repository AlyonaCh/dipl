
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Вопросы и ответы</title>
  </head>
  <body>
    <h2>{{qu}}</h2>
    <h1>Страница вопрсов</h1>
    <form method="POST" enctype="multipart/form-data" action="?/adm/">
        <label>Логин</label><br>
        <input type="text" name="login" size="20"><br>
        <label>Пароль</label><br>
        <input type="password" name="pass" size="20"><br>
        <input type="submit" name ="v" Value="Войти">
        <a href="?/adm/">Войти</a><!-- <input type="submit" name="vhod" value="Войти"> -->
    </form>
    <a href="?/add/">Создать вопрос</a>
    <table border="1">
    <tr>
      <th>Категория</th>
      <th>Вопрос</th>
      <th>Ответ</th>
    </tr>

  <!-- или -->
  {% for quest in questions %}
   <tr>
     <td>{{quest['catego']}} </td>
     <td>{{quest['quest']}}</td>
     <td>{{quest['answer']}}</td>
   </tr>
 {% endfor %}
</table>
  </body>
</html>
