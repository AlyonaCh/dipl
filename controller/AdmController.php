<?php
class AdmController
{
    private $model = null;
    function __construct($db)
    {
        include 'model/baza.php';
        $this->modelBaza = new Baza($db);
        include 'model/User.php';
        $this->modelUser = new User($db);
        include 'model/Catalogi.php';
        $this->modelCatalogi = new Catalogi($db);

    }
    //предеача данных в adm
    public function vhodAdm()
    {
        $template = $this->getTwig('adm.php');
        $admins = $this->modelUser->GetAdm();
        $categorys = $this->modelBaza->SelectCategory();
        $qwes=$this->modelBaza->GivQwestion();
        $categco=$this->modelBaza->GetCountQwestion();
        $allans=$this->modelBaza->FindAllAnswer();
        echo $template->render(array('admins'=>$admins,'categorys'=>$categorys,
        'qwes'=>$qwes,'categco'=>$categco,'allans'=>$allans));
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
    public function PostAdm($params,$post)
    {
        if(isset($post['newadm'])){
            if (isset($post['newadmnam'])&&isset($post['newadmpas'])){
                $admAdd=$this->modelUser->NewAdm([
                    'newadmnam'=>$post['newadmnam'],
                    'newadmpas'=>$post['newadmpas'],
                ]);
            }
        }
        //Замена пароля администратора
        if(isset($post['gonewpas'])){
            if(isset($post['newpas'])){
                $pasAdd=$this->modelUser->NewPassword([
                    'id'=>$post['id'],
                    'newpas'=>$post['newpas'],
                ]);
            }
        }
        //Удаление администратора
        if(isset($post['godeladm'])){
            $pasAdd=$this->modelUser->DeletAdm([
                'id'=>$post['id'],
            ]);
        }
        //Добавление новой категории НЕ РАБОТАЕТ
        if(isset($post['addad'])){
            if(isset($post['newcatecory'])){
                $catAdd=$this->modelCatalogi->NewCategory([
                    'newcatecory'=>$post['newcatecory'],
                ]);
            }
        }
        //Удаление категории
        if(isset($post['godelcate'])){
            $pasAdd=$this->modelCatalogi->DeletCategory([
                'catid'=>$post['catid'],
            ]);
        }
        //Редактор
        //Удаление вопроса
        if(isset($post['godelqwes'])){
            $gweDel=$this->modelBaza->DeletQwestion([
                'qwid'=>$post['qwid'],
            ]);
        }
        //скрыть вопрос
        if(isset($post['skrit'])){
            $gweDel=$this->modelBaza->HideQwestion([
                'qwid'=>$post['qwid'],
              ]);
        }
        //опубликовать вопрос
        if(isset($post['opub'])){
            $gweDel=$this->modelBaza->PublishQwestion([
                'qwid'=>$post['qwid'],
            ]);
        }
        //изменить вопрос
        if(isset($post['zamqw'])){
            $gweDel=$this->modelBaza->ReplaceQwestion([
                'qwid'=>$post['qwid'],
                'newqw'=>$post['newqw'],
            ]);
        }
        //изменить ответ или ответить
        if(isset($post['zamansw'])){
            $gweDel=$this->modelBaza->ReplaceAnswer([
                'qwid'=>$post['qwid'],
                'newansw'=>$post['newansw'],
            ]);
        }
        //изменить автора
        if(isset($post['zamavt'])){
            $gweDel=$this->modelBaza->ReplaceAvtorName([
                'id_user'=>$post['id_user'],
                'newavt'=>$post['newavt'],
            ]);
        }
        // переместить в другую категорию
        if(isset($post['zamacat'])){
            $gweDel=$this->modelBaza->ReplaceCategory([
                'categori'=>$post['categori'],
                'qwid'=>$post['qwid'],
            ]);
        }
        //Ответить с выбором статуса
        if(isset($post['answ'])){
            if (isset($post['newansw'])){
                $admAdd=$this->modelBaza->NewAnswer([
                    'newansw'=>$post['newansw'],
                    'allid'=>$post['allid'],
                    'status'=>$post['status'],
                ]);
            }
        }
    }
}
?>
