<?php
class Catalogi {
    public $db = null;
    function __construct($db)
    {
		    $this->db = $db;
    }
    //Добавление новой категории
    public function NewCategory($params)
    {
        $cat=$params['newcatecory'];
        $sth =$this->db->prepare("INSERT INTO `category` (`catego`) VALUES (?);");
        $sth->execute([$cat]);
    }
    //Удаление категории
    public function DeletCategory($params)
    {
        $aidd=$params['catid'];
        $sth2 =$this->db->prepare("DELETE  FROM category WHERE id =:id LIMIT 1 ;
          DELETE  FROM question WHERE id_cat =:id ");
        $sth2->bindParam(':id',$aidd);
        $sth2->execute();
    }
}
?>
