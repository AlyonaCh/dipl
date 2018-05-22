
<?php
class QuestionsAnswersController
{
    private $model = null;
    private $twig = null;
    function __construct($db, $twig )
    {
        include 'model/QuestionsAnswers.php';
        $this->modelQuestionsAnswers = new QuestionsAnswers($db);
        include 'model/User.php';
        $this->modelUser = new User($db);
        $this->twig = $twig ;

    }
    /**
    *Получение всех опубликованных вопросов
    */
    public function getlist()
    {
        $template = $this->twig->loadTemplate('list.php') ;
        $questions = $this->modelQuestionsAnswers->findAll();
        echo $template->render(['questions'=>$questions]);
    }
    public function getAdd()
    {
      $template = $this->twig->loadTemplate('add.php') ;
      $selec=$this->modelQuestionsAnswers->selectCategory();
      echo $template->render(['selec'=>$selec]);
    }
    public function postAdd($params, $post)
    {
      if(isset($post['add'])){
          if (isset($post['name'])&&isset($post['email'])){
              $idAdd=$this->modelQuestionsAnswers->addQwestion([
                'email'=>$post['email'],
                'name'=>$post['name'],
                'catego'=>$post['catego'],
                'qwest'=>$post['qwest'],
              ]);
            echo $back = $this->getAdd();
            echo 'Вопрос задан';
          }
      }
    }
    public function vhod()
    {
        $user=$this->modelUser->getAdmin();
        $_SESSION['userid']=NULL;
        foreach ($user as $us){
            if($_POST['login']==$us['login'] && $_POST['pass']==$us['password']){
                $id=$us['id'];
                return $id;
            }
        }
    }
}
?>
