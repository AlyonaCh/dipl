<?php
class QuestionsAnswers {
    public $db = null;
    function __construct($db)
    {
		    $this->db = $db;
    }
    //в и о
    //все опубликованные вопросы
    public function findAll()
    {
        $sth =$this->db->prepare("SELECT question.id, id_cat, quest, answer, id_user, date, status, category.id, category.catego FROM question INNER JOIN category  ON question.id_cat=category.id WHERE status=1  AND question.answer!=''");
        if ($sth->execute()) {
			      $result=$sth->fetchAll();
            return $result;
		    }
    }
    //Каталоги но в и о
    //все категории
    public function selectCategory()
    {
        $sth =$this->db->prepare("SELECT id, catego FROM category");
        if ($sth->execute()) {
            $result=$sth->fetchAll();
            return $result;
        }
    }
    //Пользователи но в в и о
    //все пользователи
    public function getUser()
    {
        $sth =$this->db->prepare("SELECT id, name, email FROM users");
        if ($sth->execute()) {
            $user=$sth->fetchAll();
            return $user;
        }
    }
    //Пользователи но в и о
    //Новый пользователь
    public function newUser($params)
    {
        $sth =$this->db->prepare("SELECT id FROM users WHERE name=:name email=:email");
        $sth->bindParam(':email', $params['email']);
        $sth->bindParam(':name', $params['name']);
        $sth->execute();
        $userId = $sth->fetch(PDO::FETCH_ASSOC);
        if ($userId) {
            //пользователь уже есть
        }else{
            $newuser =$this->db->prepare('INSERT INTO users ( name, email) VALUES ( ?, ?);');
            $newuser->execute([$params['name'], $params['email']]) ;
        }
    }
    // в и о
    //Добавление вопроса
    public function addQwestion($params)
    {
        $newuser=$this->newUser($params);
        $userid=$this->getUser();
        foreach ($userid as $id){
            if($params['email']==$id['email']){
                $id=$id['id'];
                break;
            }
        }
        $categoryid=$this->selectCategory();
        foreach ($categoryid as $categoig){
            if($params['catego']==$categoig['catego']){
                $categoryid=$categoig['id'];
                break;
            }
        }
        $sth =$this->db->prepare("INSERT INTO `question` (`id`, `id_cat`, `quest`, `id_user`, `date`, `status`)
                                  VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, '2');");
        $sth->execute([$categoryid, $params['qwest'], $id]);
    }
    //Каталоги но в и о
    public function getCategory()
    {
        $sth =$this->db->prepare("SELECT id, catego FROM category");
        if ($sth->execute()) {
            while ($catategory=$sth->fetch()) {
                $catategorys[]=['id'=>$catategory['id'], 'catego'=>$catategory['catego']];
            }//print_r($cat);
            return $catategorys;
        }
    }
    //в и о
    //Подсчёт количества
    public function getCountQwestion()
    {
        $categorysid=$this->getCategory();
        foreach ($categorysid as $categoryid){
            $categorid=$categoryid['id'];
            $nam[]=['idcat'=>$categorid, 'catego'=>$categoryid['catego']];
            $sth1=$this->db->prepare("SELECT COUNT(quest) FROM question WHERE id_cat=?");
            $sth1->execute(array($categorid));
            while ($row=$sth1->fetch()) {
                $countQwes[]=$row;
            }
            $sth2=$this->db->prepare("SELECT COUNT(status) FROM question WHERE id_cat=? AND status=1");
            $sth2->execute(array($categorid));
            while ($row2=$sth2->fetch()) {
                $countPubQwest[]=$row2;
            }
            $sth2=$this->db->prepare("SELECT COUNT(quest) FROM question WHERE id_cat=? AND answer=''");
            $sth2->execute(array($categorid));
            while ($row2=$sth2->fetch()) {
                $countNoAnswe[]=$row2;
            }
            $sth3=$this->db->prepare("SELECT * FROM question INNER JOIN category  ON question.id_cat=category.id WHERE question.id_cat=? ");
            if ($sth3->execute(array($categorid))) {
                while ($row=$sth3->fetch()) {
                    $qwestion[]=$row;
                }
            }
        }
        $result=array('CountQwestion'=>$countQwes,'CountPublishQwestion'=>$countPubQwest,'CountNoAnswerQwestion'=>$countNoAnswe,'qwe'=>$qwestion,'name'=>$nam);
        return $result;
    }
    //в и о
    public function givQwestion()
    {
        $categorys=$this->getCategory();
        foreach ($categorys as $category){
            $idategory=$category['id'];
            $sth =$this->db->prepare("SELECT question.id, id_cat, quest, answer, id_user, date, status, users.id, users.name, users.email
                                       FROM question INNER JOIN users  ON question.id_user=users.id WHERE id_cat=? ");
            if ($sth->execute([$idategory])) {
			          while ($row=$sth->fetch()) {
                    if($row['status']==1){
                        $row['status']='Опубликован';
                    }else if($row['status']==2){
                        $row['status']='Ожидает ответа';
                    }else{
                        $row['status']='Скрыт';
                    }
                    $qwestion[$category['catego']][]=$row;
                }
            }
        }
        return $qwestion;
    }
    //в и о
    // Редактор удаление вопроса
    public function deletQwestion($id)
    {
        $sth =$this->db->prepare("DELETE  FROM question WHERE id =:qid");
        $sth->bindParam(':qid', $id);
        $sth->execute();
    }
    //в и о
    // Редактор скрыть вопрос
    public function hideQwestion($id)
    {
        $sth =$this->db->prepare("UPDATE question SET status=3 WHERE id=:qid");
        $sth->bindParam(':qid', $id);
        $sth->execute();
    }
    // Редактор опубликовать вопрос
    //в и о
    public function publishQwestion($id)
    {
        $sth =$this->db->prepare("UPDATE question SET status=1 WHERE id=:qid");
        $sth->bindParam(':qid', $id);
        $sth->execute();
    }
    //в и о
    //Редактор изменить вопрос
    public function replaceQwestion($id, $qwestion)
    {
        $sth =$this->db->prepare("UPDATE question SET quest=:quest WHERE id=:qid");
        $sth->bindParam(':qid', $id);
        $sth->bindParam(':quest', $qwestion);
        $sth->execute();
    }
    //в и о
    //Редактор изменить ответ или ответить
    public function replaceAnswer($id, $answer)
    {
        $sth =$this->db->prepare("UPDATE question SET answer=:answer, status=3 WHERE id=:qid");
        $sth->bindParam(':qid', $id);
        $sth->bindParam(':answer', $answer);
        $sth->execute();
    }
    //в и о
    //Редактор изменить автора
    public function replaceAvtorName($id, $avtor)
    {
        $sth =$this->db->prepare("UPDATE users SET name=:name WHERE id=:qid");
        $sth->bindParam(':qid', $id);
        $sth->bindParam(':name', $avtor);
        $sth->execute();
    }
    //в и о
    //Редактор переместить в другую категорию
    public function replaceCategory($category, $id)
    {
        $categorys=$this->selectCategory();
        foreach ($categorys as $categoryid){
            if($category==$categoryid['catego']){
                $newcategoryid=$categoryid['id'];
            }
        }
        $sth =$this->db->prepare("UPDATE question SET id_cat=:id_cat WHERE id=:id");
        $sth->bindParam(':id_cat', $newcategoryid);
        $sth->bindParam(':id', $id);
        $sth->execute();
    }
    //в и о
    public function findAllAnswer()
    {
        $sth =$this->db->prepare("SELECT id, id_cat, quest, answer, id_user, date, status FROM question  WHERE answer='' ORDER BY date");
        if ($sth->execute()) {
			      $resul=$sth->fetchAll();
            if (!empty($resul)) {
                return $resul;
            }
		    }

    }
    //в и о
    //Ответить с выбором статуса
    public function newAnswer($answer, $qwestionid, $status)
    {
        if ($status=='Скрыть') {
            $statusid=3;
        }else{
            $statusid=1;
        }
        $sth =$this->db->prepare("UPDATE question SET answer=:answer, status=:status WHERE id=:qid");
        $sth->bindParam(':qid', $qwestionid);
        $sth->bindParam(':status', $statusid);
        $sth->bindParam(':answer', $answer);
        $sth->execute();
    }
}
?>
