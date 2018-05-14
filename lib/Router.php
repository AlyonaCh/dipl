<?php
class Router
{
	private $dirConroller = '';
	private $db = '';
	private $urls = [];
	function __construct($dirConroller, $db)
	{
		$this->dirConroller = $dirConroller;
		$this->db = $db;
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
					$controller = new $urlData['controller']($this->db);
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
$router = new Router('controller/', $db);
$router->get('/','QuestionsAnswersController@getList');
$router->get('/add/','QuestionsAnswersController@getAdd');
$router->post('/add/','QuestionsAnswersController@postAdd');
$router->post('/adm/','AdmController@vhodAdm');
$router->post('/adm/NewAdm/','AdmController@NewAdm');
$router->post('/adm/ChangeAdmPassword/','AdmController@ChangeAdmPassword');
$router->post('/adm/DeleteAdmin/','AdmController@DeleteAdmin');
$router->post('/adm/AdmNewCatecory/','AdmController@AdmNewCatecory');
$router->post('/adm/AdmDeletCategory/','AdmController@AdmDeletCategory');
$router->post('/adm/AdmDeletQwestion/','AdmController@AdmDeletQwestion');
$router->post('/adm/AdmHideQwestion/','AdmController@AdmHideQwestion');
$router->post('/adm/AdmPublishQwestion/','AdmController@AdmPublishQwestion');
$router->post('/adm/AdmReplaceQwestion/','AdmController@AdmReplaceQwestion');
$router->post('/adm/AdmReplaceAnswer/','AdmController@AdmReplaceAnswer');
$router->post('/adm/AdmReplaceAvtorName/','AdmController@AdmReplaceAvtorName');
$router->post('/adm/AdmReplaceCategory/','AdmController@AdmReplaceCategory');
$router->post('/adm/AdmNewAnswer/','AdmController@AdmNewAnswer');
$currentUrl = str_replace(['/u/achernyaeva/dip', '/?'],['', ''], $_SERVER['REQUEST_URI']);
$router->run($currentUrl);
?>
