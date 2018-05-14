<?php
class AdmController
{
    private $model = null;
    function __construct($db)
    {
        include 'model/QuestionsAnswers.php';
        $this->modelQuestionsAnswers = new QuestionsAnswers($db);
        include 'model/User.php';
        $this->modelUser = new User($db);
        include 'model/Catalogi.php';
        $this->modelCatalogi = new Catalogi($db);

    }
    //предеача данных в adm
    public function vhodAdm($params,$post)
    {
        $user=$this->modelUser->GetAdm();
        foreach ($user as $us){
            if($post['login']==$us['login'] && $post['password']==$us['password']){
                session_start();
                $_SESSION['userid']=$us['id'];
                $_SESSION['userlogin']=$us['login'];
                $_SESSION['userpassword']=$us['password'];
            }elseif($_SESSION['userlogin']==$us['login'] && $_SESSION['userpassword']==$us['password']){
              session_start();
            }else{
                header("Location:http://university.netology.ru/u/achernyaeva/dip/");
            }
            $template = $this->getTwig('adm.php');
            $admins = $this->modelUser->GetAdm();
            $categorys = $this->modelQuestionsAnswers->SelectCategory();
            $qwestion=$this->modelQuestionsAnswers->GivQwestion();
            $categco=$this->modelQuestionsAnswers->GetCountQwestion();
            $allanswer=$this->modelQuestionsAnswers->FindAllAnswer();
            echo $template->render(['admins'=>$admins,'categorys'=>$categorys,
            'qwestion'=>$qwestion,'categco'=>$categco,'allanswer'=>$allanswer,'session'=>$_SESSION['userid']]);
            exit;
        }
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
    //Добавление нового администратора
    public function NewAdm($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['newadm'])){
            if (isset($post['newadmnam'])&&isset($post['newadmpas'])){
                $admins=$this->modelUser->GetAdm();
                $i=0;
                foreach ($admins as $key ) {
                    if($key['login']==$post['newadmnam']){
                        $i++;
                        break;
                    }
                }
                if($i!=1){
                    $admAdd=$this->modelUser->NewAdm([
                        'newadmnam'=>$post['newadmnam'],
                        'newadmpas'=>$post['newadmpas'],
                    ]);
                }
                echo $back = $this->vhodAdm($params,$vhod);
            }
        }
    }
    //Замена пароля администратора
    public function ChangeAdmPassword($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['gonewpas'])){
            if(isset($post['newpas'])){
                $pasAdd=$this->modelUser->NewPassword([
                    'id'=>$post['id'],
                    'newpas'=>$post['newpas'],
                ]);
                echo $back = $this->vhodAdm($params,$vhod);
            }
        }
    }
    //Удаление администратора
    public function DeleteAdmin($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['godeladm'])){
            $pasAdd=$this->modelUser->DeletAdm([
                'id'=>$post['id'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //Добавление новой категории
    public function AdmNewCatecory($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['addad'])){
            if(isset($post['newcatecory'])){
                $catAdd=$this->modelCatalogi->NewCategory([
                    'newcatecory'=>$post['newcatecory'],
                ]);
                echo $back = $this->vhodAdm($params,$vhod);
            }
        }
    }
    //Удаление категории
    public function AdmDeletCategory($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['godelcate'])){
            $pasAdd=$this->modelCatalogi->DeletCategory([
                'catid'=>$post['catid'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //Редактор
    //Удаление вопроса
    public function AdmDeletQwestion($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['godelqwes'])){
            $gweDel=$this->modelQuestionsAnswers->DeletQwestion([
                'qwid'=>$post['qwid'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //скрыть вопрос
    public function AdmHideQwestion($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['skrit'])){
            $gweDel=$this->modelQuestionsAnswers->HideQwestion([
                'qwid'=>$post['qwid'],
              ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //опубликовать вопрос
    public function AdmPublishQwestion($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['opub'])){
            $gweDel=$this->modelQuestionsAnswers->PublishQwestion([
                'qwid'=>$post['qwid'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //изменить вопрос
    public function AdmReplaceQwestion($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['zamqw'])){
            $gweDel=$this->modelQuestionsAnswers->ReplaceQwestion([
                'qwid'=>$post['qwid'],
                'newqw'=>$post['newqw'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //изменить ответ или ответить
    public function AdmReplaceAnswer($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['zamansw'])){
            $gweDel=$this->modelQuestionsAnswers->ReplaceAnswer([
                'qwid'=>$post['qwid'],
                'newansw'=>$post['newansw'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //изменить автора
    public function AdmReplaceAvtorName($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['zamavt'])){
            $gweDel=$this->modelQuestionsAnswers->ReplaceAvtorName([
                'id_user'=>$post['id_user'],
                'newavt'=>$post['newavt'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    // переместить в другую категорию
    public function AdmReplaceCategory($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['zamacat'])){
            $gweDel=$this->modelQuestionsAnswers->ReplaceCategory([
                'categori'=>$post['categori'],
                'qwid'=>$post['qwid'],
            ]);
            echo $back = $this->vhodAdm($params,$vhod);
        }
    }
    //Ответить с выбором статуса
    public function AdmNewAnswer($params,$post)
    {
        session_start();
        $vhod=['login'=>$_SESSION['userlogin'],'password'=>$_SESSION['userpassword']];
        if(isset($post['answ'])){
            if (isset($post['newanswer'])){
                $admAdd=$this->modelQuestionsAnswers->NewAnswer([
                    'newansw'=>$post['newanswer'],
                    'allid'=>$post['allid'],
                    'status'=>$post['status'],
                ]);
            echo $back = $this->vhodAdm($params,$vhod);
            }
        }
    }
}
?>
