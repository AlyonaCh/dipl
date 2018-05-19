<?php
class User {
    public $db = null;
    function __construct($db)
    {
		    $this->db = $db;
    }
    //Все администраторы
    public function getAdmin()
    {
        $sel =$this->db->prepare("SELECT id, login, password FROM admins");
        if ($sel->execute()) {
            $admin=$sel->fetchAll();
            return $admin;
        }
    }
    //Вход !!!!
    public function authorization($login, $password )
{
    $sth =$this->db->prepare("SELECT id FROM admins WHERE login=:login AND password=:password");
    $sth->bindParam(':password', $password);
    $sth->bindParam(':login', $login);
    $sth->execute();
    $userId = $sth->fetch(PDO::FETCH_ASSOC);
    $result = false;
    if ($userId) {
        $_SESSION['userId'] = $userId;
        $result = true;
    }
    return $result;
}
    //добавление нового администратора
    public function newAdmin($login, $password)
    {
        $sth =$this->db->prepare("INSERT INTO `admins` (`login`,`password`) VALUES (?,?);");
        $sth->execute([$login,$password]);
    }
    //Замена пароля администратора
    public function newPassword($id, $password)
    {
        $sth1 =$this->db->prepare("UPDATE admins SET password=:password WHERE id=:id");
        $sth1->bindParam(':password', $password);
        $sth1->bindParam(':id', $id);
        $sth1->execute();
    }
    //Удаление администратора
    public function deletAdmin($id)
    {
        $sth2 =$this->db->prepare("DELETE FROM admins WHERE id =:id LIMIT 1 ");
        $sth2->bindParam(':id', $id);
        $sth2->execute();
    }
}
?>
