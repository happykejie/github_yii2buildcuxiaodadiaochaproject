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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我</title>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/cxddc/css/teacher.css')?>
</head>
<body>
	
    <div class="mui-content"  style="height:600px">
        
           
         

           <div style="text-align:left;margin-top:10px">
            <p style="color:green">请详细阅读发布发布信息说明</p>
</div>
            <div >
            <div style="text-align:left"> <p style="color:blue">促销大调查发布说明：</p></div>
               <div style="padding-left:20px;text-align:left"> <p>发现信息除了遵守促销大调查平台协议外。还有如下发布说明：</p></div>
               <div style="padding-left:20px;text-align:left"> <p>(1)发布消息可以选择免费发布和付费发布</p></div>
               <div style="padding-left:20px;text-align:left"> <p>(2)为了防止恶意发布和恶意占用资源，一个账号每月可以免费发布一次促销消息。发布时间默认只限当天促销活动。</p></div>
               <div style="padding-left:20px;text-align:left"> <p>(3)为了提供产品质量和不乱发促销信息，付费发布从发布当前到结束时间期间每天收取10元的平台维护费用。</p></div>
               <div style="padding-left:20px;text-align:left"> <p>(4)为防止长期占用资源，发布促销信息超过时间跨度超过10天的请联系平台服务商解决。</p></div>
               <div style="padding-left:20px;text-align:left"> <p>(5)倾销分类，和大型活动发布，以及自定义设计促销页面等特殊需求请联系平台服务商解决。</p></div>

            </div>

            <div class="my-teacher">
                <a  href="publishinfopay">付费发布促销消息</a>  
               </div>
       
			<div class="my-teacher" >
               
                 <a href="publishinfofree">免费发布促销消息</a>  
            </div>


        

    

                
              
          

           
        </div>
        
  
	
	

    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript" charset="UTF-8">
        mui.init();
    </script>
	
	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->
	
	
</body>
</html>
