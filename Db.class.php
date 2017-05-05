<?php
/**
 * 接口封装
 * User: WT
 * Date: 2017/5/5
 * Time: 16:09
 */
error_reporting(0);
class Response
{
	static $_instance	=	null;
	static $_link		=	null;
	// 提示信息
    protected $message	=	array(
		'paramError'	=>	'参数错误',
		'codeError'		=>	'请输入正确的提示代码',
		'queryError'	=>	'查询失败',
	);
	
	// 构造函数设置外部无实例化权限
	private function __construct()
	{
		
	}
	
	/*
	 * 内部实例化对象本身
	 * @return object
	 */
	static function getInstance()
	{
		if (!self::$_instance)
		{
			self::$_instance	=	new self();
			if (!self::$_link)
			{
				self::$_link	=	self::$_instance->connect();
			}
		}
		return self::$_instance;
	}
	
	/*
	 * 数据库连接
	 * @return object
	 */
	private function connect()
	{
		// 获取数据库配置信息
		$config	=	include('config.php');
		$link	=	mysqli_connect($config['dbHost'], $config['dbUser'], $config['dbPwd'], $config['dbName']);
		if ($link == false)
		{
			$this->p('数据库连接出错： '.mysqli_connect_error());
		}
		return $link;
	}
	
	/*
	 * 私有方法提示信息输出
	 * @param string $message 	提示信息
	 * @return string
	 */
	public function p($message)
	{
		die($message);
	}
	
	/*
	 * 接口json数据输出
	 * @param int 	 $code 		提示代码
	 * @param string $message 	提示信息
	 * @param array	 $data		输出的数据
	 * @return string
	 */
    public function show($code = 200, $message = '查询成功', $data = array())
    {
        if (!is_numeric($code))
        {
            die($this->message['codeError']);
        }
		$data	=	array(
			'code'		=>	$code,
			'message'	=>	$message,
			'data'		=>	$data
		);
		echo json_encode($data);
    }
	
	/*
	 * 数据库查询
	 * @param int 	 $sql 		查询语句
	 * @return array
	 */
	public function query($sql = '')
	{
		$res	=	mysqli_query(self::$_link, $sql);
		if ($res == false)
		{
			return '';
		}
		
		$data	=	array();
		while($row = mysqli_fetch_assoc($res))
		{
			$data[]	=	$row;
		}
		return $data;
	}
}
$test	=	Response::getInstance();
$data	=	$test->query('SELECT * FROM zhcweb_admin');		// 查询
print_r($data);die;































