<?php
class Router
{
	private $dirConroller = '';
	private $db = '';
	private $twig = '';
	private $urls = [];
	function __construct($dirConroller, $db, $twig)
	{
		$this->dirConroller = $dirConroller;
		$this->db = $db;
		$this->twig = $twig;
	}
	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@getUpdate
	 */
	public function get($url, $controllerAndAction, $params = [])
	{
		$this->add('GET', $url, $controllerAndAction, $params);
	}
	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@postUpdate
	 */
	public function post($url, $controllerAndAction, $params = [])
	{
		$this->add('POST', $url, $controllerAndAction, $params);
	}
	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@list
	 */
	public function add($method, $url, $controllerAndAction, $params)
	{
		list($controller, $action) = explode('@', $controllerAndAction);
		$this->urls[$method][$url] = [
			'controller' => $controller,
			'action' => $action,
			'params' => $params
		];
	}
	/**
	 * Подключение контроллеров
	 * @param $url текущий урл
	 */
	public function run($currentUrl)
	{
		if (isset($this->urls[$_SERVER['REQUEST_METHOD']])) {
			foreach ($this->urls[$_SERVER['REQUEST_METHOD']] as $url => $urlData) {
				if (preg_match('(^'.$url.'$)', $currentUrl, $matchList)) {
					$params = [];
					foreach ($urlData['params'] as $param => $i) {
						$params[$param] = $matchList[$i];
					}
					include $this->dirConroller.$urlData['controller'].'.php';
					$controller = new $urlData['controller']($this->db, $this->twig);
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$controller->$urlData['action']($params, $_POST);
					} else {
						$controller->$urlData['action']($params);
					}
				}
			}
		}
	}
}
$router = new Router('controller/', $db, $twig);
$router->get('/','QuestionsAnswersController@getList');
$router->get('/add/','QuestionsAnswersController@getAdd');
$router->post('/add/','QuestionsAnswersController@postAdd');
$router->post('/adm/','AdminController@vhodAdmin');
$router->post('/adm/New-Admin/','AdminController@newAdmin');
$router->post('/adm/Change-Admin-Password/','AdminController@changeAdminPassword');
$router->post('/adm/Delete-Admin/','AdminController@deleteAdmin');
$router->post('/adm/Admin-New-Catecory/','AdminController@adminNewCatecory');
$router->post('/adm/Admin-Delet-Category/','AdminController@adminDeletCategory');
$router->post('/adm/Admin-Delet-Qwestion/','AdminController@adminDeletQwestion');
$router->post('/adm/Admin-Hide-Qwestion/','AdminController@adminHideQwestion');
$router->post('/adm/Admin-Publish-Qwestion/','AdminController@adminPublishQwestion');
$router->post('/adm/Admin-Replace-Qwestion/','AdminController@adminReplaceQwestion');
$router->post('/adm/Admin-Replace-Answer/','AdminController@adminReplaceAnswer');
$router->post('/adm/Admin-Replace-Avtor-Name/','AdminController@adminReplaceAvtorName');
$router->post('/adm/Admin-Replace-Category/','AdminController@adminReplaceCategory');
$router->post('/adm/Admin-New-Answer/','AdminController@adminNewAnswer');
$currentUrl = str_replace(['/u/achernyaeva/dip', '/?'],['', ''], $_SERVER['REQUEST_URI']);
$router->run($currentUrl);
?>
