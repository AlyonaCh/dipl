
<?php
class BazaController
{
    private $model = null;
    function __construct($db)
    {
        include 'model/baza.php';
        $this->modelBaza = new Baza($db);
        include 'model/User.php';
        $this->modelUser = new User($db);

    }
    public function getTwig($tem)
    {
        require_once 'vendor/autoload.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('template');
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate($tem);
        return $template;
    }
    /**
    *Получение всех опубликованных вопросов
    */
    public function getlist()
    {
        $template = $this->getTwig('list.php');
        $questions = $this->modelBaza->findAll();
        echo $template->render(array('questions'=>$questions));
    }
    public function getAdd()
    {
      $template = $this->getTwig('add.php');
      $selec=$this->modelBaza->SelectCategory();
      echo $template->render(array('selec'=>$selec));
    }
    public function postAdd($params,$post)
    {
      if(isset($post['add'])){
          if (isset($post['name'])&&isset($post['email'])){
              $idAdd=$this->modelBaza->AddQwestion([
                'email'=>$post['email'],
                'name'=>$post['name'],
                'catego'=>$post['catego'],
                'qwest'=>$post['qwest'],
              ]);
          }
      }
    }
    public function Vhod()
    {
        $user=$this->modelUser->GetAdm();
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
