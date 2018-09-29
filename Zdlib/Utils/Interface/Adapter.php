<?
require_once ('autoload.inc');
/**
 * 数据库适配器
 * 
 * 数据库连接适配器类，提供各种数据库的基本属性构造（主机，用户名，密码等)，目前只实现了mysql数据库相关操作
 * 
 * @package Utils-Interface
 * @access public
 * @abstract 抽象类
 * @author 张东宇
 * @version 1.0
 */
abstract class Utils_Interface_Adapter {
	protected $host; //定义连接数据库主机
	protected $username; //定义连接数据库用户名
	protected $password = ""; //定义连接数据库密码
	protected $database; //定义连接数据库名<br>
	protected $charset = 'UTF-8'; //字符集
	protected $cachedir;
	protected $cachetime;
	protected $section = null; //配置文件中配置片段
	private $properties; //配置数组
	private $fileName; //配置文件名称
	private $isprocess = false; //是否按照片段进行加载
	private $isOracle = false; //是否为Oracle数据库
	protected $service_name;
	

	/**
	 * 构造函数
	 *
	 * @param mixed 数据库配置文件名称
	 * @param mixed 配置文件中配置片段，默认为null
	 */
	protected function __construct($fileName, $section = null ,$isOracle = 0) {
		if (! is_null ( $section ))
			$this->isprocess = true;
		$this->section = $section;
		$this->isOracle = $isOracle;
		$this->fileName = $fileName;
		//		if(!$this->isprocess&&!is_null($this->section))
		//			throw new Exception_CEException('$this->isprocess is must be true!');
		$this->properties = parse_ini_file ( $this->fileName, $this->isprocess );
//		print_r($this->properties);
		
		//		foreach($this->properties as $value)
		//		{
		//			if(is_array($value))
		//				$this->is2Array=true;
		//			break;
		//		}
		$this->binding ();
	}
	/**
	 * 通过配置文件中的配置，绑定数据库连接相关参数
	 *
	 * @return void 无
	 */
	function binding() {
		if (! $this->isprocess) {
			$this->host = $this->properties ['host'];
			$this->port = $this->properties ['port'];
			$this->username = $this->properties ['username'];
			$this->password = $this->properties ['password'];
			$this->database = $this->properties ['database'];
			$this->charset = $this->properties ['charset'];
		} else {
			if (is_array ( $this->section )) { //自定义数据库连接参数,如数据库配置信息来自数据库，需动态拼装
				$this->host = $this->section ['host'];
				$this->port = $this->properties ['port'];
				$this->username = $this->section ['username'];
				$this->password = $this->section ['password'];
				$this->database = $this->section ['database'];
				$this->charset = $this->section ['charset'];
			} else {
				if($this->isOracle)
				{
					$this->properties = $this->properties [$this->section];
					$this->host = $this->properties ['oracle.host'];
					$this->port = $this->properties ['oracle.port'];
					$this->username = $this->properties ['oracle.username'];
					$this->password = $this->properties ['oracle.password'];
					$this->service_name = $this->properties ['oracle.service_name'];
					$this->charset = $this->properties['oracle.charset'];
				}else 
				{
					$this->properties = $this->properties [$this->section];
					$this->host = $this->properties ['mysql.host'];
					$this->port = $this->properties ['mysql.port'];
					$this->username = $this->properties ['mysql.username'];
					$this->password = $this->properties ['mysql.password'];
					$this->database = $this->properties ['mysql.database'];
					$this->charset = $this->properties ['mysql.charset'];
// 					$this->cachedir = $this->properties ['mysql.cachedir'];
// 					$this->cachedir = $this->properties ['mysql.cachetime'];
				}
//				print_r($this->properties);
//				exit;
			}
		}
	
	}
}