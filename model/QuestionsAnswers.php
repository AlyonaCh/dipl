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
    public function SelectCategory()
    {
        $select =$this->db->prepare("SELECT id, catego FROM category");
        if ($select->execute()) {
            $result=$select->fetchAll();
            return $result;
        }
    }
    //Пользователи но в в и о
    //все пользователи
    public function getUser()
    {
        $getuser =$this->db->prepare("SELECT id, name, email FROM users");
        if ($getuser->execute()) {
            $user=$getuser->fetchAll();
            return $user;
        }
    }
    //Пользователи но в и о
    //Новый пользователь
    public function newUser($params)
    {
        $testuser=$this->getUser();
        $i=0;
        foreach ($testuser as $test){
            if($params['email']==$test['email']){
                $i++;
                break;
            }
        }
        if ($i!=1){
            $newuser =$this->db->prepare('INSERT INTO users ( name, email) VALUES ( ?, ?);');
            $newuser->execute([$params['name'],$params['email']]) ;
        }
    }
    // в и о
    //Добавление вопроса
    public function AddQwestion($params)
    {
        $newuser=$this->newUser($params);
        $usid=$this->getUser();
        foreach ($usid as $id){
            if($params['email']==$id['email']){
                $id=$id['id'];
                break;
            }
        }
        $categoryid=$this->SelectCategory();
        foreach ($categoryid as $categoig){
            if($params['catego']==$categoig['catego']){
                $categoryid=$categoig['id'];
                break;
            }
        }
        $sth =$this->db->prepare("INSERT INTO `question` (`id`, `id_cat`, `quest`, `id_user`, `date`, `status`)
                                  VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, '2');");
        $sth->execute([$categoryid,$params['qwest'],$id]);
    }
    //Каталоги но в и о
    public function GetCategory()
    {
        $sel =$this->db->prepare("SELECT id, catego FROM category");
        if ($sel->execute()) {
            while ($row=$sel->fetch()) {
                $cat[]=['id'=>$row['id'],'catego'=>$row['catego']];
            }//print_r($cat);
            return $cat;
        }
    }
    //в и о
    //Подсчёт количества
    public function GetCountQwestion()
    {
        $categorysid=$this->GetCategory();
        foreach ($categorysid as $categoryid){
            $cid=$categoryid['id'];
            $nam[]=['idcat'=>$cid,'catego'=>$categoryid['catego']];
            $sel1=$this->db->prepare("SELECT COUNT(quest) FROM question WHERE id_cat=?");
            $sel1->execute(array($cid));
            while ($row=$sel1->fetch()) {
                $countQwes[]=$row;
            }
            $sel2=$this->db->prepare("SELECT COUNT(status) FROM question WHERE id_cat=? AND status=1");
            $sel2->execute(array($cid));
            while ($row2=$sel2->fetch()) {
                $countPubQwest[]=$row2;
            }
            $sel2=$this->db->prepare("SELECT COUNT(quest) FROM question WHERE id_cat=? AND answer=''");
            $sel2->execute(array($cid));
            while ($row2=$sel2->fetch()) {
                $countNoAnswe[]=$row2;
            }
            $sth2=$this->db->prepare("SELECT * FROM question INNER JOIN category  ON question.id_cat=category.id WHERE question.id_cat=? ");
            if ($sth2->execute(array($cid))) {
                while ($row=$sth2->fetch()) {
                    $qwestion[]=$row;
                }
            }
        }
        $result=array('CountQwestion'=>$countQwes,'CountPublishQwestion'=>$countPubQwest,'CountNoAnswerQwestion'=>$countNoAnswe,'qwe'=>$qwestion,'name'=>$nam);
        return $result;
    }
    //в и о
    public function GivQwestion()
    {
        $catid=$this->GetCategory();
        foreach ($catid as $cidi){
            $idgw=$cidi['id'];
            $sth2 =$this->db->prepare("SELECT question.id, id_cat, quest, answer, id_user, date, status, users.id, users.name, users.email
                                       FROM question INNER JOIN users  ON question.id_user=users.id WHERE id_cat=? ");
            if ($sth2->execute([$idgw])) {
			          while ($row=$sth2->fetch()) {
                    if($row['status']==1){
                        $row['status']='Опубликован';
                    }else if($row['status']==2){
                        $row['status']='Ожидает ответа';
                    }else{
                        $row['status']='Скрыт';
                    }
                    $qwe[$cidi['catego']][]=$row;
                }
            }
        }
        return $qwe;
    }
    //в и о
    // Редактор удаление вопроса
    public function DeletQwestion($params)
    {
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("DELETE  FROM question WHERE id =:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    //в и о
    // Редактор скрыть вопрос
    public function HideQwestion($params)
    {
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("UPDATE question SET status=3 WHERE id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    // Редактор опубликовать вопрос
    //в и о
    public function PublishQwestion($params)
    {
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("UPDATE question SET status=1 WHERE id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    //в и о
    //Редактор изменить вопрос
    public function ReplaceQwestion($params)
    {
        $qwidd=$params['qwid'];
        $quest=$params['newqw'];
        $sth2 =$this->db->prepare("UPDATE question SET quest=:quest WHERE id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':quest',$quest);
        $sth2->execute();
    }
    //в и о
    //Редактор изменить ответ или ответить
    public function ReplaceAnswer($params)
    {
        $qwidd=$params['qwid'];
        $answer=$params['newansw'];
        $sth2 =$this->db->prepare("UPDATE question SET answer=:answer, status=3 WHERE id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':answer',$answer);
        $sth2->execute();
    }
    //в и о
    //Редактор изменить автора
    public function ReplaceAvtorName($params)
    {
        $qwidd=$params['id_user'];
        $name=$params['newavt'];
        $sth2 =$this->db->prepare("UPDATE users SET name=:name WHERE id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':name',$name);
        $sth2->execute();
    }
    //в и о
    //Редактор переместить в другую категорию
    public function ReplaceCategory($params)
    {
        $catid=$this->SelectCategory();
        foreach ($catid as $cid){
            if($params['categori']==$cid['catego']){
                $catid=$cid['id'];
            }
        }
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("UPDATE question SET id_cat=:id_cat WHERE id=:id");
        $sth2->bindParam(':id_cat',$catid);
        $sth2->bindParam(':id',$qwidd);
        $sth2->execute();
    }
    //в и о
    public function FindAllAnswer()
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
    public function NewAnswer($params)
    {
        if ($params['status']=='Скрыть') {
            $status=3;
        }else{
            $status=1;
        }
        $qwidd=$params['allid'];
        $answer=$params['newansw'];
        $sth2 =$this->db->prepare("UPDATE question SET answer=:answer, status=:status WHERE id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':status',$status);
        $sth2->bindParam(':answer',$answer);
        $sth2->execute();
    }
}
?>
