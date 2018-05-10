<?php
class Baza {
    public $db = null;
    function __construct($db)
    {
		    $this->db = $db;
    }
    public function findAll()
    {
        $sth =$this->db->prepare("SELECT * FROM question inner JOIN category  on question.id_cat=category.id where status=1  and question.answer!=''");
        if ($sth->execute()) {
			      while($row=$sth->fetch()){
                $resul[]=$row;
            }
        return $resul;
		    }

    }
    public function selectCategory()
    {
        $sel =$this->db->prepare("SELECT * FROM category");
        if ($sel->execute()) {
            while($row=$sel->fetch()){
              $resu[]=$row;
            }
            return $resu;
        }
    }
    public function getUser()
    {
        $sel =$this->db->prepare("SELECT * FROM users");
        if ($sel->execute()) {
            while($row=$sel->fetch()){
                $us[]=$row;
            }
            return $us;
        }
    }
    public function newUser($params)
    {
        $tes=$this->getUser();
        $i=0;
        foreach ($tes as $test){
            if($params['email']==$test['email']){
                $i++;
                break;
            }
        }if ($i!=1){
            $newusq =$this->db->prepare('INSERT INTO users ( name, email) VALUES ( ?, ?);');
            $newusq->execute(array($params['name'],$params['email'])) ;
        }
    }
    public function addQwes($params)
    {
        $tes=$this->newUser($params);
        $usid=$this->getUser();
        foreach ($usid as $id){
            if($params['email']==$id['email']){
                $id=$id['id'];
                break;
            }
        }
        $catid=$this->selectCategory();
        foreach ($catid as $cid){
            if($params['catego']==$cid['catego']){
                $catid=$cid['id'];
                break;
            }
        }
        $sth =$this->db->prepare("INSERT INTO `question` (`id`, `id_cat`, `quest`, `id_user`, `date`, `status`)
                                  VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, '2');");
        $sth->execute(array($catid,$params['qwest'],$id));
    }
    public function getAdm()
    {
        $sel =$this->db->prepare("SELECT * FROM admins");
        if ($sel->execute()) {
            while($row=$sel->fetch()){
                $ad[]=$row;
            }
            return $ad;
        }
    }
    //Замена пароля администратора
    public function newPassword($params)
    {
        $aid=$params['id'];
        $text=$params['newpas'];
        $sth1 =$this->db->prepare("update admins set password=:password where id=:id");
        $sth1->bindParam(':password',$text);
        $sth1->bindParam(':id',$aid);
        $sth1->execute();
    }
    //Удаление администратора
    public function delAdm($params)
    {
        $aidd=$params['id'];
        $sth2 =$this->db->prepare("DELETE FROM admins WHERE id =:id LIMIT 1 ");
        $sth2->bindParam(':id',$aidd);
        $sth2->execute();
    }
    //Добавление новой категории
    public function newCategory($params)
    {
        $cat=$params['newcatecory'];
        $sth =$this->db->prepare("INSERT INTO `category` (`catego`) VALUES (?);");
        $sth->execute(array($cat));
    }
    //добавление нового администратора
    public function newAdm($params)
    {
        $Login=$params['newadmnam'];
        $Pas=$params['newadmpas'];
        $sth =$this->db->prepare("INSERT INTO `admins` (`login`,`password`) VALUES (?,?);");
        $sth->execute(array($Login,$Pas));
    }
    public function getCateg()
    {
        $sel =$this->db->prepare("SELECT * FROM category");
        if ($sel->execute()) {
            while($row=$sel->fetch()){
                $cat[]=['id'=>$row['id'],'catego'=>$row['catego']];
            }//print_r($cat);
            return $cat;
        }
    }
    public function getCountQwe()
    {
        $catid=$this->getCateg();
            foreach ($catid as $cidi){
              //print_r($cidi);
              $cid=$cidi['id'];
              $nam[]=['idcat'=>$cid,'catego'=>$cidi['catego']];
              //$nam[]=$cidi['catego'];
              //$nam_id[]=$cidi['id'];
                    $sel1 =$this->db->prepare("select count(quest) from question where id_cat=?");
                    $sel1->execute(array($cid));
                    while($row=$sel1->fetch()){
                        $colq[]=$row;
                    }
                    $sel2 =$this->db->prepare("select count(status) from question where id_cat=? AND status=1");
                    $sel2->execute(array($cid));
                    while($row2=$sel2->fetch()){
                        $cols[]=$row2;
                    }
                    $sel2 =$this->db->prepare("select count(quest) from question where id_cat=? AND answer=''");
                    $sel2->execute(array($cid));
                    while($row2=$sel2->fetch()){
                        $cola[]=$row2;
                    }
                    $sth2 =$this->db->prepare("SELECT * FROM question inner JOIN category  on question.id_cat=category.id where question.id_cat=? ");
                    if ($sth2->execute(array($cid))) {
            			      while($row=$sth2->fetch()){
                            $qwe[]=$row;
                        }

                }
            }
            $h=array('q'=>$colq,'s'=>$cols,'a'=>$cola,'qwe'=>$qwe,'name'=>$nam);
            return $h;
    }
    //Удаление категории
    public function delCat($params)
    {
        $aidd=$params['catid'];
        $sth2 =$this->db->prepare("DELETE  FROM category WHERE id =:id LIMIT 1 ;
          DELETE  FROM question WHERE id_cat =:id ");
        $sth2->bindParam(':id',$aidd);
        $sth2->execute();
    }
    public function givQwe()
    {
        $catid=$this->getCateg();
        foreach ($catid as $cidi){
            $idgw=$cidi['id'];
            $sth2 =$this->db->prepare("SELECT * FROM question inner JOIN users  on question.id_user=users.id where id_cat=? ");
            if ($sth2->execute(array($idgw))) {
			          while($row=$sth2->fetch()){
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
    // Редактор удаление вопроса
    public function delQwe($params)
    {
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("DELETE  FROM question WHERE id =:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    // Редактор скрыть вопрос
    public function skQwe($params)
    {
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("update question set status=3 where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    // Редактор опубликовать вопрос
    public function opubQwe($params)
    {
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("update question set status=1 where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    //Редактор изменить вопрос
    public function zamQwe($params)
    {
        $qwidd=$params['qwid'];
        $quest=$params['newqw'];
        $sth2 =$this->db->prepare("update question set quest=:quest where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':quest',$quest);
        $sth2->execute();
    }
    //Редактор изменить ответ или ответить
    public function zamAnsw($params)
    {
        $qwidd=$params['qwid'];
        $answer=$params['newansw'];
        $sth2 =$this->db->prepare("update question set answer=:answer, status=3 where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':answer',$answer);
        $sth2->execute();
    }
    //Редактор изменить автора
    public function zamAvt($params)
    {
        $qwidd=$params['id_user'];
        $name=$params['newavt'];
        $sth2 =$this->db->prepare("update users set name=:name where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':name',$name);
        $sth2->execute();
    }
    public function Vhod($params)
    {
        $user=$this->getAdm();
        foreach ($user as $us){
            if($params['login']==$us['login'] && $params['pass']==$us['password']){
                $id=$us['id'];
                return $id;
            }else {
              exit;
            }
        }
    }
    //Редактор переместить в другую категорию
    public function zamCat($params)
    {
        $catid=$this->selectCategory();
        foreach ($catid as $cid){
            if($params['categori']==$cid['catego']){
                $catid=$cid['id'];
            }
        }
        $qwidd=$params['qwid'];
        $sth2 =$this->db->prepare("update question set id_cat=:id_cat where id=:id");
        $sth2->bindParam(':id_cat',$catid);
        $sth2->bindParam(':id',$qwidd);
        $sth2->execute();
    }
    public function findAllAns()
    {
        $sth =$this->db->prepare("SELECT * FROM question  where answer='' ORDER BY date");
        if ($sth->execute()) {
			      while($row=$sth->fetch()){
                $resul[]=$row;
            }
            if (!empty($resul)) {
                return $resul;
            }
		    }

    }
    //Ответить с выбором статуса
    public function newAnsw($params)
    {
        if ($params['status']=='Скрыть') {
            $status=3;
        }else{
            $status=1;
        }
        $qwidd=$params['allid'];
        $answer=$params['newansw'];
        $sth2 =$this->db->prepare("update question set answer=:answer, status=:status where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':status',$status);
        $sth2->bindParam(':answer',$answer);
        $sth2->execute();
    }
}
?>
