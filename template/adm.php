<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Админка</title>
  </head>
  <body>
    <a href="http://university.netology.ru/u/achernyaeva/dip/">Выход</a>

    <h2>Управление администраторами</h2>
    <form method="POST" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/New-Admin/">
      <input type="text" name="newadmnam">
      <input type="text" name="newadmpas">
      <input type="submit" name="newadm" value="Добавить нового администратора">
    </form>
    <br>
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
        <form method="POST" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Change-Admin-Password/">
          <input type="text" name="newpas">
          <input type="hidden" name="id" value="{{admin['id']}}">
          <input type="submit" name="gonewpas"Value="Изменить">
        </form>
        </td>
        <td>
          <form method="POST"  action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Delete-Admin/">
            <input type="hidden" name="id" value="{{admin['id']}}">
            <input type="submit" name="godeladm" Value="Удалить">
          </form>
     </td>
  </tr>
  {% endfor %}
  </table>

  <h2>Управление темами</h2>
    <form method="POST" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-New-Catecory/">
      <input type="text" name="newcatecory">
      <input type="submit" name="addnewcatecory" value="Добавить новую тему">
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
          <form method="POST"  action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Delet-Category/">
            <input type="hidden" name="categoryid" value="{{categor['id']}}">
            <input type="submit" name="godelcategory" Value="Удалить">
          </form>
        </td>
      </tr>
     {% endfor %}
   </table>

   <h3>Содержание тем</h3>
   <table border="1">
   {% set i = 0 %}{% for qwestion in qwestions %}
     <tr>
       <th colspan="3">Название темы</th>
       <th colspan="3">Количество вопросов</th>
       <th colspan="3">Количество опубликованных</th>
       <th colspan="3">Количество не отвеченных</th>
     </tr>
     <tr>
       <td colspan="3">{{categco['name'][i]['catego']}}</td>
       <td colspan="3">{{categco['CountQwestion'][i]['COUNT(quest)']}}</td>
       <td colspan="3">{{categco['CountPublishQwestion'][i]['COUNT(status)']}}</td>
       <td colspan="3">{{categco['CountNoAnswerQwestion'][i]['COUNT(quest)']}}{% set i = i + 1 %}</td>
     </tr>
     <tr>
       <th colspan="12">Вопросы к теме</th>
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
       <th>Переместить в категорию</th>
     </tr>
     {% for qwest in qwestion %}
     <tr>
       <td>{{qwest['quest']}}</td>
       <td>{{qwest['answer']}}</td>
       <td>{{qwest['date']}}</td>
       <td>{{qwest['status']}}</td>
       <td>{{qwest['name']}}</td>
       <form method="POST" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Delet-Qwestion/">
         <td>
           <input type="hidden" name="qwestid" value="{{qwest['0']}}">
           <input type="submit" name="godelqwes" Value="Удалить">
         </td>
       </form>
       <form method="POST"  action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Hide-Qwestion/">
         <td>
           <input type="hidden" name="qwestid" value="{{qwest['0']}}">
           <input type="submit" name="Hide" Value="Скрыть">
         </td>
       </form>
       <form method="POST"  enctype="multipart/form-data" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Publish-Qwestion/">
         <td>
           <input type="hidden" name="qwestid" value="{{qwest['0']}}">
           <input type="submit" name="Publish" Value="Опубликовать">
         </td>
       </form>
       <form method="POST"  enctype="multipart/form-data" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Replace-Qwestion/">
         <td>
           <input type="hidden" name="qwestid" value="{{qwest['0']}}">
           <input type="text" name="newqw">
           <input type="submit" name="Replaceqwestion" Value="Изменить">
         </td>
       </form>
       <form method="POST"  enctype="multipart/form-data" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Replace-Answer/">
         <td>
           <input type="hidden" name="qwestid" value="{{qwest['0']}}">
           <input type="text" name="newansw">
           <input type="submit" name="Replaceanswer" Value="Ответить">
         </td>
       </form>
       <form method="POST"  enctype="multipart/form-data" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Replace-Avtor-Name/">
         <td>
           <input type="hidden" name="qwestid" value="{{qwest['0']}}">
           <input type="hidden" name="id_user" value="{{qw['id_user']}}">
           <input type="text" name="newavt">
           <input type="submit" name="Replaceavtor" Value="Изменить">
         </td>
       </form>
       <form method="POST"  enctype="multipart/form-data" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Replace-Category/">
         <td>
           <input type="hidden" name="qwestid" value="{{qwest['0']}}">
           <select name="categori">{% for categor in categorys %}
             <option>{{categor['catego']}}</option>{% endfor %}
           <select>
           <input type="submit" name="Replacecategory" Value="Переместить">
         </td>
      </form>
      </tr>
      {% endfor %}
    {% endfor %}
    </table>

    <br>
    <a href="http://university.netology.ru/u/achernyaeva/dip/?/add/">Создать вопрос</a>

    <h3>Список не отвеченных вопросов</h3>
    <table border="1">
    <tr>
      <th>Вопрос</th>
      <th>Дата добавления</th>
      <th>Ответить</th>
      <th>Изменить вопрос</th>
    </tr>
    {% for answer in allanswer %}
    <tr>
      <td>{{answer['quest']}}</td>
      <td>{{answer['date']}}</td>
      <td>
      <form method="POST"  enctype="multipart/form-data" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-New-Answer/">
        <select name="status">
          <option>Скрыть</option>
          <option>Опубликовать</option>
        <select>
        <input type="hidden" name="allid" value="{{answer['id']}}">
        <input type="text" name="newanswer">
        <input type="submit" name="Newanswer" Value="Ответить">
      </form>
      </td>
      <td>
        <form method="POST"  enctype="multipart/form-data" action="http://university.netology.ru/u/achernyaeva/dip/?/adm/Admin-Replace-Qwestion/">
          <input type="hidden" name="qwestid" value="{{answer['id']}}">
          <input type="text" name="newqw">
          <input type="submit" name="Replaceqwestion" Value="Изменить">
        </form>
      </td>
    </tr>
    {% endfor %}
    </table>
  </body>
</html>
