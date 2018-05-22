<?php
class AdminController
{
    private $model = null;
    private $twig = null;
    function __construct($db, $twig)
    {
        include 'model/QuestionsAnswers.php';
        $this->modelQuestionsAnswers = new QuestionsAnswers($db);
        include 'model/User.php';
        $this->modelUser = new User($db);
        include 'model/Catalogi.php';
        $this->modelCatalogi = new Catalogi($db);
        $this->twig = $twig;

    }
    //предеача данных в adm
    public function vhodAdmin($params, $post)
    {
        $userId = $this->modelUser->authorization($post['login'], $post['password']);
        if($userId){
            $_SESSION['userlogin']=$post['login'];
            $_SESSION['userpassword']=$post['password'];
        }else{
            header("Location:http://university.netology.ru/u/achernyaeva/dip/");
        }
        $template = $this->twig->loadTemplate('adm.php') ;
        $admins = $this->modelUser->getAdmin();
        $categorys = $this->modelQuestionsAnswers->selectCategory();
        $qwestions = $this->modelQuestionsAnswers->givQwestion();
        $categco = $this->modelQuestionsAnswers->getCountQwestion();
        $allanswer = $this->modelQuestionsAnswers->findAllAnswer();
        echo $template->render(['admins'=>$admins,'categorys'=>$categorys,
        'qwestions'=>$qwestions,'categco'=>$categco,'allanswer'=>$allanswer,'session'=>$_SESSION['userid']]);
    }
    /*public function getTwig($tem)
    {
        require_once 'vendor/autoload.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('template');
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate($tem);
        return $template;
    }*/
    //Добавление нового администратора
    public function newAdmin($params,$post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['newadm'])){
            if (isset($post['newadmnam'])&&isset($post['newadmpas'])){
                $admins=$this->modelUser->getAdmin();
                $i=0;
                foreach ($admins as $key ) {
                    if($key['login']==$post['newadmnam']){
                        $i++;
                        break;
                    }
                }
                if($i!=1){
                    $admAdd=$this->modelUser->newAdmin($post['newadmnam'], $post['newadmpas']);
                }
                $this->vhodAdmin($params, $vhod);
            }
        }
    }
    //Замена пароля администратора
    public function changeAdminPassword($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['gonewpas'])){
            if(isset($post['newpas'])){
                $pasAdd=$this->modelUser->newPassword($post['id'],$post['newpas']);
                $this->vhodAdmin($params, $vhod);
            }
        }
    }
    //Удаление администратора
    public function deleteAdmin($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['godeladm'])){
            $pasAdd=$this->modelUser->deletAdmin($post['id']);
            $this->vhodAdmin($params,$vhod);
        }
    }
    //Добавление новой категории
    public function adminNewCatecory($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['addnewcatecory'])){
            if(isset($post['newcatecory'])){
                $catAdd=$this->modelCatalogi->newCategory($post['newcatecory']);
                $this->vhodAdmin($params, $vhod);
            }
        }
    }
    //Удаление категории
    public function adminDeletCategory($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['godelcategory'])){
            $pasAdd=$this->modelCatalogi->deletCategory($post['categoryid']);
            $this->vhodAdmin($params, $vhod);
        }
    }
    //Редактор
    //Удаление вопроса
    public function adminDeletQwestion($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['godelqwes'])){
            $gweDel=$this->modelQuestionsAnswers->deletQwestion($post['qwestid']);
            $this->vhodAdmin($params, $vhod);
        }
    }
    //скрыть вопрос
    public function adminHideQwestion($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['Hide'])){
            $gweDel=$this->modelQuestionsAnswers->hideQwestion($post['qwestid']);
            $this->vhodAdm($params, $vhod);
        }
    }
    //опубликовать вопрос
    public function adminPublishQwestion($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['Publish'])){
            $gweDel=$this->modelQuestionsAnswers->publishQwestion($post['qwestid']);
            $this->vhodAdmin($params, $vhod);
        }
    }
    //изменить вопрос
    public function adminReplaceQwestion($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['Replaceqwestion'])){
            $gweDel=$this->modelQuestionsAnswers->replaceQwestion($post['qwestid'], $post['newqw']);
            $this->vhodAdmin($params, $vhod);
        }
    }
    //изменить ответ или ответить
    public function adminReplaceAnswer($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['Replaceanswer'])){
            $gweDel=$this->modelQuestionsAnswers->replaceAnswer($post['qwestid'], $post['newansw']);
            $this->vhodAdmin($params, $vhod);
        }
    }
    //изменить автора
    public function adminReplaceAvtorName($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['Replaceavtor'])){
            $gweDel=$this->modelQuestionsAnswers->replaceAvtorName($post['id_user'], $post['newavt']);
            $this->vhodAdmin($params, $vhod);
        }
    }
    // переместить в другую категорию
    public function adminReplaceCategory($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['Replacecategory'])){
            $gweDel=$this->modelQuestionsAnswers->replaceCategory($post['categori'], $post['qwestid']);
            $this->vhodAdmin($params, $vhod);
        }
    }
    //Ответить с выбором статуса
    public function adminNewAnswer($params, $post)
    {
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['Newanswer'])){
            if (isset($post['newanswer'])){
                $admAdd=$this->modelQuestionsAnswers->newAnswer($post['newanswer'], $post['allid'], $post['status']);
                $this->vhodAdmin($params, $vhod);
            }
        }
    }
}
?>
