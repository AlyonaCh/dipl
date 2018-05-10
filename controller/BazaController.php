
<?php
class BazaController
{
    private $model = null;
    function __construct($db)
    {
        include 'model/baza.php';
        $this->model = new Baza($db);

    }
    public function vhodAdm()
    {
            $template = $this->getTwig('adm.php');
            $admins = $this->model->getAdm();
            $categorys = $this->model->selectCategory();
            $qwes=$this->model->givQwe();
            $categco=$this->model->getCountQwe();
            $allans=$this->model->findAllAns();
            echo $template->render(array('admins'=>$admins,'categorys'=>$categorys,
            'qwes'=>$qwes,'categco'=>$categco,'allans'=>$allans));
    }
    /**
    *Получение всех опубликованных вопросов
    */
    public function getTwig($tem)
    {
        require_once 'vendor/autoload.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('template');
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate($tem);
        return $template;
    }
    public function getlist()
    {
        $template = $this->getTwig('list.php');
        $questions = $this->model->findAll();
        echo $template->render(array('questions'=>$questions));
    }
    public function getAdd()
    {
      $template = $this->getTwig('add.php');
      $selec=$this->model->selectCategory();
      echo $template->render(array('selec'=>$selec));

    }
    public function postAdd($params,$post)
    {
      if(isset($post['add'])){
          if (isset($post['name'])&&isset($post['email'])){
              $idAdd=$this->model->addQwes([
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
        $user=$this->model->getAdm();
        $_SESSION['userid']=NULL;
        foreach ($user as $us){
            if($_POST['login']==$us['login'] && $_POST['pass']==$us['password']){
                $id=$us['id'];
                return $id;
            }
        }

    }
    //Добавление нового администратора
    public function postNewadm($params,$post)
    {
      if(isset($post['newadm'])){
          if (isset($post['newadmnam'])&&isset($post['newadmpas'])){
              $admAdd=$this->model->newAdm([
                'newadmnam'=>$post['newadmnam'],
                'newadmpas'=>$post['newadmpas'],
              ]);
          }
      }
    //Замена пароля администратора
        if(isset($post['gonewpas'])){
            if(isset($post['newpas'])){
                $pasAdd=$this->model->newPassword([
                    'id'=>$post['id'],
                    'newpas'=>$post['newpas'],
                ]);
            }
        }
    //Удаление администратора
        if(isset($post['godeladm'])){
            $pasAdd=$this->model->delAdm([
                'id'=>$post['id'],
            ]);
        }
    //Добавление новой категории НЕ РАБОТАЕТ
        if(isset($post['addad'])){
            if(isset($post['newcatecory'])){
                $catAdd=$this->model->newCategory([
                    'newcatecory'=>$post['newcatecory'],
                ]);
            }
        }
    //Удаление категории
        if(isset($post['godelcate'])){
            $pasAdd=$this->model->delCat([
                'catid'=>$post['catid'],
            ]);
        }
    //Редактор
        //Удаление вопроса
        if(isset($post['godelqwes'])){
            $gweDel=$this->model->delQwe([
                'qwid'=>$post['qwid'],
            ]);
        }
        //скрыть вопрос
        if(isset($post['skrit'])){
            $gweDel=$this->model->skQwe([
                'qwid'=>$post['qwid'],
              ]);
        }
        //опубликовать вопрос
        if(isset($post['opub'])){
            $gweDel=$this->model->opubQwe([
                'qwid'=>$post['qwid'],
            ]);
        }
        //изменить вопрос
        if(isset($post['zamqw'])){
            $gweDel=$this->model->zamQwe([
                'qwid'=>$post['qwid'],
                'newqw'=>$post['newqw'],
            ]);
        }
        //изменить ответ или ответить
        if(isset($post['zamansw'])){
            $gweDel=$this->model->zamAnsw([
                'qwid'=>$post['qwid'],
                'newansw'=>$post['newansw'],
            ]);
        }
        //изменить автора
        if(isset($post['zamavt'])){
            $gweDel=$this->model->zamAvt([
                'id_user'=>$post['id_user'],
                'newavt'=>$post['newavt'],
            ]);
        }
        // переместить в другую категорию
        if(isset($post['zamacat'])){
            $gweDel=$this->model->zamCat([
                'categori'=>$post['categori'],
                'qwid'=>$post['qwid'],
            ]);
        }
    //Ответить с выбором статуса
        if(isset($post['answ'])){
            if (isset($post['newansw'])){
                $admAdd=$this->model->newAnsw([
                    'newansw'=>$post['newansw'],
                    'allid'=>$post['allid'],
                    'status'=>$post['status'],
                ]);
            }
        }

    }
}
?>
