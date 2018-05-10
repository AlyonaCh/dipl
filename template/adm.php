
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Админка</title>
  </head>
  <body>
  <a href="logaut.php">Выход</a>
  <h2>{{aa}}</h2>
  <h2>{{qq}}</h2>
  <form method="POST">
      <input type="text" name="newadmnam">
      <input type="text" name="newadmpas">
      <input type="submit" name="newadm" value="Добавить нового администратора">
  </form>
  <table border="1">
    <tr>
      <th>Имя</th>
      <th>Пароль</th>
      <th>Замена пароля</th>
      <th>Действия</th>
    </tr>
    {% for admin in admins %}
    <tr>
      <td>{{admin['login']}}</td>
      <td>{{admin['password']}}</td>
      <td>
        <form method="POST" enctype="multipart/form-data">
          <input type="text" name="newpas">
          <input type="hidden" name="id" value="{{admin['id']}}">
          <input type="submit" name ="gonewpas"Value="Изменить">
        </form>
      </td>
      <td>
        <form method="POST"  enctype="multipart/form-data">
          <input type="hidden" name="id" value="{{admin['id']}}">
          <input type="submit" name ="godeladm" Value="Удалить">
        </form>
     </td>
  </tr>
  {% endfor %}
  </table>
  <h2>Управление темами</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="newcatecory">
        <input type="submit" name="addad" value="Добавить новую тему">
    </form>
    <h3>Список тем</h3>
    <table border="1">
     <tr>
       <th>Название темы</th>
       <th>Удалить категорию и все вопросы в ней</th>
     </tr>
     {% for categor in categorys %}
     <tr>
       <td>{{categor['catego']}}</td>
       <td>
         <form method="POST"  enctype="multipart/form-data">
            <input type="hidden" name="catid" value="{{categor['id']}}">
            <input type="submit" name ="godelcate" Value="Удалить">
        </form>
      </td>
    </tr>
     {% endfor %}
   </table>
   <h3>Содержание тем</h3>
    <table border="1">
       {% set i = 0 %}{% for qwev in qwes %}
     <tr>
       <th colspan="3">Название темы</th>
       <th colspan="3">Количество вопросов</th>
       <th colspan="3">Количество опубликованных</th>
       <th colspan="2">Количество не отвеченных</th>
     </tr>
     <tr>
       <td colspan="3">{{categco['name'][i]['catego']}}<?= $categco['name'][$i]['catego'];?></td>
       <td colspan="3">{{categco['q'][i]['count(quest)']}}<?=$categco['q'][$i]['count(quest)']; ?></td>
       <td colspan="3">{{categco['s'][i]['count(status)']}}<?=$categco['s'][$i]['count(status)']; ?></td>
       <td colspan="2">{{categco['a'][i]['count(quest)']}}<?=$categco['a'][$i]['count(quest)']; $i++?>{% set i = i + 1 %}</td>
       </td>
     </tr>
     <tr>
       <th colspan="11">Вопросы к теме</th>
     </tr>
     <tr>
       <th>Вопрос</th>
       <th>Ответ</th>
       <th>Дата создания</th>
       <th>Статус</th>
       <th>Автор</th>
       <th>Удалить</th>
       <th>Скрыть</th>
       <th>Опубликовать</th>
       <th>Изменить вопрос</th>
       <th>Ответить</th>
       <th>Изменить автора</th>
     </tr>
     {% for qw in qwev %}
     <tr>
       <td>{{qw['quest']}}</td>
       <td>{{qw['answer']}}</td>
       <td>{{qw['date']}}</td>
       <td>{{qw['status']}}</td>
       <td>{{qw['name']}}</td>
       <form method="POST"  enctype="multipart/form-data">
             <td>
               <input type="hidden" name="qwid" value="{{qw['0']}}">
               <input type="submit" name ="godelqwes" Value="Удалить">
             </td>
             <td>
               <input type="submit" name ="skrit" Value="Скрыть">
             </td>
             <td>
               <input type="submit" name ="opub" Value="Опубликовать">
             </td>
             <td>
               <input type="text" name="newqw">
               <input type="submit" name ="zamqw" Value="Изменить">
             </td>
             <td>
               <input type="text" name="newansw">
               <input type="submit" name ="zamansw" Value="Ответить">
             </td>
             <td>
               <input type="hidden" name="id_user" value="{{qw['id_user']}}">
               <input type="text" name="newavt">
               <input type="submit" name ="zamavt" Value="Изменить">
             </td>
             <td>
               <select name="categori">{% for categor in categorys %}
                 <option>{{categor['catego']}}</option>{% endfor %}
               <select>
               <input type="submit" name ="zamacat" Value="Переместить">
             </td>
        </form>
     </tr>
     {% endfor %}
    {% endfor %}
    </table>
      <h3>Список не отвеченных вопросов</h3>
      <table border="1">
       <tr>
         <th>Вопрос</th>
         <th>Дата добавления</th>
         <th>Ответить</th>
         <th>Изменить вопрос</th>
       </tr>
       {% for all in allans %}
       <tr>
         <td>{{all['quest']}}</td>
         <td>{{all['date']}}</td>
         <td>
           <form method="POST"  enctype="multipart/form-data">
               <select name="status">
                 <option>Скрыть</option>
                 <option>Опубликовать</option>
               <select>
               <input type="hidden" name="allid" value="{{all['id']}}">
               <input type="text" name="newansw">
               <input type="submit" name ="answ" Value="Ответить">
          </form>
        </td>
        <td>
          <form method="POST"  enctype="multipart/form-data">
            <input type="hidden" name="qwid" value="{{all['id']}}">
            <input type="text" name="newqw">
            <input type="submit" name ="zamqw" Value="Изменить">
          </form>
        </td>
      </tr>
    {% endfor %}
     </table>
  </body>
</html>
