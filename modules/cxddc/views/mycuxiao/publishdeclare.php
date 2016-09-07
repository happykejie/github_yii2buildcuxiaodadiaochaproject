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
    <?=Html::cssFile('@web/web/assets/cxddc/css/user.css')?>
</head>
<body>
	
    <div class="mui-content" >
        
    
            <div style="text-align:left;margin-top:20px; margin-left:10px"> <p style="color:#23ac38">促销大调查发布说明：</p></div>
                <div style="border-bottom-style:groove;margin-left:20px;margin-right:20px;border:1px solid #23ac38;padding:20px 20px 20px 20px;margin-bottom:10px">
             
               <div style="padding-left:2px;text-align:left"> <p>(1)发布消息可以选择免费发布和付费发布</p></div>
               <div style="padding-left:2px;text-align:left"> <p>(2)为了防止恶意发布和恶意占用资源，一个账号每月可以免费发布一次促销消息。发布时间默认只限当天促销活动。</p></div>
               <div style="padding-left:2px;text-align:left"> <p>(3)为了提供产品质量和不乱发促销信息，付费发布从发布当前到结束时间期间每天收取10元的平台维护费用。</p></div>
               <div style="padding-left:2px;text-align:left"> <p>(4)为防止长期占用资源，发布促销信息超过时间跨度超过10天的请联系平台服务商解决。</p></div>
               <div style="padding-left:2px;text-align:left"> <p>(5)大型活动发布，以及自定义设计促销页面等特殊需求请联系平台服务商解决。</p></div>
                </div>

            </div>

            <div class="my-teacher">
                <a  href="publishinfopay">付费发布促销消息</a>  
               </div>
       
			<div class="my-teacher" >
               
                 <a href="publishinfofree">免费发布促销消息</a>  
            </div>

            

          <div style="height:50px"></div>
        

    

                
              
          

           
        </div>
        
  
	
	

    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript" charset="UTF-8">
        mui.init();
    </script>
	
  <input type="hidden" value="<?= $currentuserid?>" id="userid"/>
      	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxzhuyejs.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->
	
	
</body>
</html>
