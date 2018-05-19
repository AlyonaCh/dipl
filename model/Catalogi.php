<?php
class Catalogi {
    public $db = null;
    function __construct($db)
    {
		    $this->db = $db;
    }
    //Добавление новой категории
    public function newCategory($category)
    {
        $sth =$this->db->prepare("INSERT INTO `category` (`catego`) VALUES (?);");
        $sth->execute([$category]);
    }
    //Удаление категории
    public function deletCategory($id)
    {
        $sth =$this->db->prepare("DELETE  FROM category WHERE id =:id LIMIT 1 ;
          DELETE  FROM question WHERE id_cat =:id ");
        $sth->bindParam(':id', $id);
        $sth->execute();
    }
}
?>
