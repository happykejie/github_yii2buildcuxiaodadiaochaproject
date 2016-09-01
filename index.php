<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define("APP_ROOT",dirname(__FILE__));



require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');


$domain =  $_SERVER['HTTP_HOST'];//获取当前域名


if($domain =='www.tatahaoyun.com')
{
    require(__DIR__ . '/config/tatahaoyunconfig.php'); ///引入".DOMAITDESC."公共常量
}



else if($domain =='www.boshizhidao.com')
{
    require(__DIR__ . '/config/boshizhidaoconfig.php'); ///引入博士知道公共常量
}

else
{
    require(__DIR__ . '/config/cuxiaodadiaochaconfig.php'); ///引入默认公共常量
}



define('BASE_PATH',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
  
$config = require(__DIR__ . '/config/web.php');

(new yii\web\Application($config))->run();

?>

