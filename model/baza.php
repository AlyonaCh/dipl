<?php
include ('../connect.php');
session_start();
class Baza {
    public $db = null;
    function __construct($db)
    {
		    $this->db = $db;
    }
    public $res=[];
    public $resu=[];
    public $us=[];
    public $ad=[];
    public function findAll()
    {
        $sth =$this->db->prepare("SELECT * FROM question inner JOIN category  on question.id_cat=category.id where status=1");
        if ($sth->execute()) {
			      while($row=$sth->fetch()){
                $resu[]=$row;
            }
        return $resu;
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
    public function newUser()
    {
        $tes=$this->getUser();
        $i=0;
        foreach ($tes as $test){
            if($_GET['email']==$test['email']){
                $i++;
            }
        }if ($i!=1){
            $newusq =$this->db->prepare('INSERT INTO users ( name, email) VALUES ( ?, ?);');
            $newusq->execute(array($_GET['name'],$_GET['email'])) ;
        }
    }
    public function addQwes()
    {
        $tes=$this->newUser();
        $usid=$this->getUser();
        foreach ($usid as $id){
            if($_GET['email']=$id['email']){
                $id=$id['id'];
            }
        }
        $catid=$this->selectCategory();
        foreach ($catid as $cid){
            if($_GET['catego']=$cid['catego']){
                $catid=$cid['id'];
            }
        }
        $sth =$this->db->prepare("INSERT INTO `question` (`id`, `id_cat`, `quest`, `id_user`, `date`, `status`)
                                  VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, '2');");
        $sth->execute(array($catid,$_GET['qwest'],$id));
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
    public function newPassword()
    {
        $aid=$_GET['id'];
        $text=$_GET['newpas'];
        $sth1 =$this->db->prepare("update admins set password=:password where id=:id");
        $sth1->bindParam(':password',$text);
        $sth1->bindParam(':id',$aid);
        $sth1->execute();
    }
    public function delAdm()
    {
        $aidd=$_GET['id'];
        $sth2 =$this->db->prepare("DELETE FROM admins WHERE id =:id LIMIT 1 ");
        $sth2->bindParam(':id',$aidd);
        $sth2->execute();
    }
    public function newCategory()
    {
        $cat=$_GET['newcatecory'];
        $sth =$this->db->prepare("INSERT INTO `category` (`catego`) VALUES (?);");
        $sth->execute(array($cat));
    }
    public function newAdm()
    {
        $Login=$_GET['newadmnam'];
        $Pas=$_GET['newadmpas'];
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
            //echo'<pre>';
            //print_r($h);
            return $h;
    }
    public function delCat()
    {
        $aidd=$_GET['catid'];
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
            $sth2 =$this->db->prepare("SELECT * FROM question  where id_cat=? ");
            if ($sth2->execute(array($idgw))) {
			          while($row=$sth2->fetch()){
                    $user=$this->getUser();
                    foreach ($user as $us){
                        if($us['id']==$row['id_user']){
                            $row['name_user']=$us['name'];
                        }
                    }
                    if($cidi['id']=$row['id_cat']){
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
        }
        return $qwe;
    }
    public function delQwe()
    {
        $qwidd=$_GET['qwid'];
        $sth2 =$this->db->prepare("DELETE  FROM question WHERE id =:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    public function skQwe()
    {
        $qwidd=$_GET['qwid'];
        $sth2 =$this->db->prepare("update question set status=3 where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    public function opubQwe()
    {
        $qwidd=$_GET['qwid'];
        $sth2 =$this->db->prepare("update question set status=1 where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->execute();
    }
    public function zamQwe()
    {
        $qwidd=$_GET['qwid'];
        $quest=$_GET['newqw'];
        $sth2 =$this->db->prepare("update question set quest=:quest where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':quest',$quest);
        $sth2->execute();
    }
    public function zamAnsw()
    {
        $qwidd=$_GET['qwid'];
        $answer=$_GET['newansw'];
        $sth2 =$this->db->prepare("update question set answer=:answer, status=3 where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':answer',$answer);
        $sth2->execute();
    }
    public function zamAvt()
    {
        $qwidd=$_GET['id_user'];
        $name=$_GET['newavt'];
        $sth2 =$this->db->prepare("update users set name=:name where id=:qid");
        $sth2->bindParam(':qid',$qwidd);
        $sth2->bindParam(':name',$name);
        $sth2->execute();
    }
    public function Vhod()
    {
        $user=$this->getAdm();
        $_SESSION['userid']=NULL;
        foreach ($user as $us){
            if($_POST['login']==$us['login'] && $_POST['pass']==$us['password']){
                $id=$us['id'];
                return $id;
            }
        }
    }
}
$question = new Baza($db);
$questions=$question->findAll();
$selec=$question->selectCategory();
if(isset($_GET['add'])){
    if (!empty($_GET['name'])&&!empty($_GET['email'])){
        $add=$question->addQwes();
        header("Location:list.php");
    }
}
$admins=$question->getAdm();
if(isset($_GET['gonewpas'])){
    $newpas=$question->newPassword();
    header("Location:adm.php");
}
if(isset($_GET['godeladm'])){
    $deladm=$question->delCat();
    header("Location:adm.php");
}
$catego=$question->getCateg();
//echo'<pre>';
//print_r($catego);
$categco=$question->getCountQwe();
if(isset($_GET['godelcate'])){
    $delcat=$question->delCat();
    header("Location:adm.php");
}
$qwes=$question->givQwe();
if(isset($_GET['godelqwes'])){
    $delque=$question->delQwe();
    header("Location:adm.php");
}
if(isset($_GET['skrit'])){
    $skr=$question->skQwe();
    header("Location:adm.php");
}
if(isset($_GET['opub'])){
    $skr=$question->opubQwe();
    header("Location:adm.php");
}
if(isset($_GET['zamqw'])){
    $zam=$question->zamQwe();
    header("Location:adm.php");
}
if(isset($_GET['zamansw'])){
    $zam=$question->zamAnsw();
    header("Location:adm.php");
}
if(isset($_GET['zamavt'])){
    $zam=$question->zamAvt();
    header("Location:adm.php");
}

if(isset($_GET['addad'])){
    $zam=$question->newCategory();
    header("Location:adm.php");
}
if(isset($_GET['newadm'])){
    $zam=$question->newAdm();
    header("Location:adm.php");
}
session_start();
if (!empty($_POST)){
    if(isset($_POST['vhod'])){
        $zam=$question->Vhod();
        $_SESSION['userid']=$zam;
        if($_SESSION['userid']!=NULL){
            header("Location:adm.php");
        }
    }
}

?>
