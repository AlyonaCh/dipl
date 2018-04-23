<?php
class BazaController
{
    private $model = null;
    function __construct($db)
    {
        include 'model/baza.php';
        $this->model = new Baza($db);
    }
    private function render($template, $params = [])
    {
        $fileTemplate = 'template/'.$template;
        if (is_file($fileTemplate)){
            ob_start();
            if(count($params) > 0){
                extract($params);
            }
            include $fileTemplate;
            return ob_get_clean();
        }
    }
    /**
    *Получение всех опубликованных вопросов
    */
    public function getlist()
    {
        $questions = $this->model->findAll();
        echo $this->render('list.php', ['questions'=>$questions]);
    }
    /*function getAdd()
    {
        echo $this->render('/add.php');
    }*/
    function getAdd()
    {
      if(isset($_GET['addd'])){
          if (!empty($_GET['name'])&&!empty($_GET['email'])){
              $add=$this->model->addQwes();
              header("Location:list.php");
          }
      }
    }
}
?>
