    <?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>


<?php
require_once "models/WxJsSdk.php";
$jssdk = new WxJsSdk(WX_APPID, WX_APPSECRET);
$signPackage = $jssdk->GetSignPackage();
?>


	




<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		
	</style>

	<title>系统维修升级</title>





  

</head>
<body>
  

    <div style="color:red;font-size:30px;">

        <div style="margin-top:50px;">
       系统维护中.........
            </div>

        <div style="margin-top:50px">

        维护完成时间：
            </br>
            </br>
            2016年9月12日12:00
            </div>

        </div>



       <div style="margin-top:50px">

        如有业务需求：
            </br>
            </br>
            访问官网：<a href="http://www.ahuanajie.com">阿欢阿杰科技</a>
            </div>

        </div>
  

</body>

</html>


