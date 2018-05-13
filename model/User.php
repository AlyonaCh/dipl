<?php
class User {
    public $db = null;
    function __construct($db)
    {
		    $this->db = $db;
    }
    //Все администраторы
    public function GetAdm()
    {
        $sel =$this->db->prepare("SELECT id, login, password FROM admins");
        if ($sel->execute()) {
            $ad=$sel->fetchAll();
            return $ad;
        }
    }
    //Вход !!!!
    public function Vhod($params)
    {
        $user=$this->GetAdm();
        foreach ($user as $us){
            if($params['login']==$us['login'] && $params['pass']==$us['password']){
                $id=$us['id'];
                return $id;
            }else {
              exit;
            }
        }
    }
    //добавление нового администратора
    public function NewAdm($params)
    {
        $Login=$params['newadmnam'];
        $Pas=$params['newadmpas'];
        $sth =$this->db->prepare("INSERT INTO `admins` (`login`,`password`) VALUES (?,?);");
        $sth->execute([$Login,$Pas]);
    }
    //Замена пароля администратора
    public function NewPassword($params)
    {
        $aid=$params['id'];
        $text=$params['newpas'];
        $sth1 =$this->db->prepare("update admins set password=:password where id=:id");
        $sth1->bindParam(':password',$text);
        $sth1->bindParam(':id',$aid);
        $sth1->execute();
    }
    //Удаление администратора
    public function DeletAdm($params)
    {
        $aidd=$params['id'];
        $sth2 =$this->db->prepare("DELETE FROM admins WHERE id =:id LIMIT 1 ");
        $sth2->bindParam(':id',$aidd);
        $sth2->execute();
    }
}
?>
