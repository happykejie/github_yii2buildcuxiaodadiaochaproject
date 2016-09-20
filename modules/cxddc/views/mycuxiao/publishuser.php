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
    <title>发布者</title>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/cxddc/css/user.css')?>
</head>
<body>
	
    <div class="mui-content" style="position: relative;">
        <div class="my-header">
            <div class="oa-contact-avatar ">
                <img src="<?=$items->headimgurl?>">
            </div>
			<input type="hidden" value="<?=$items->id?>" id="userid"/>
            <h4 class="text-h4"><?=$items->nickname?></h4>
         

           <div style="text-align:left">
            <p style="color:green">欢迎您成为发布者，现在可以发布自己的促销信息</p>
</div>
            <div >
            <div style="text-align:left"> <p style="color:blue">促销大调查平台协议：</p></div>
               <div style="padding-left:20px;text-align:left"> <p>在平台发布信息真实可靠，如果发现虚假信息立即封号并删除之前所有发布信息。发布的信息如果过期平台将自动清除不再展示。</p></div>
            </div>

			<div class="my-teacher">
                <?php if($items->userstate==1):?>
          
                <a href="publishdeclare">发布促销消息</a>  
                
                <?endif?>
            </div>

             <ul class="teacher-ul">
                <li>
                    <a href="infoercode">
                        <img src="/web/assets/mui/images/icon8.png" alt="" />
                        <div class="">二维码</div>
                    </a>
                </li>
                <li>
                    <a href="myinfo">
                        <img style="width: 28px;" src="/web/assets/mui/images/icon9.png" alt="" />
                        <div class="bj">编辑</div>
                    </a>
                </li>
            </ul>
        </div>
        <ul class="mui-table-view mui-table-view-chevron">
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right"  href="mypublished">
                    <div class="mui-pull-left border-radius love">
                        <img class="" src="/web/assets/mui/images/icon10.png">
                    </div>
                    <div class="mui-media-body">
                       我的发布
                    </div>
                </a>
            </li>



            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right"  href="/cxddc/mycuxiao/nopayorder">
                    <div class="mui-pull-left border-radius love">
                        <img class="" src="/web/assets/mui/images/icon10.png">
                    </div>
                    <div class="mui-media-body">
                       未支付发布
                    </div>
                </a>
            </li>
           


            <li class="mui-table-view-cell mui-media">
                <a class='mui-navigate-right' href="myinfo">
                    <div class="mui-pull-left border-radius question-mark">
                        <img class="" src="/web/assets/mui/images/icon12.png">
                    </div>
                    <div class="mui-media-body">
                        我的资料
                    </div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right" href="cooperation">
                    <div class="mui-pull-left border-radius headset">
                        <img class="" src="/web/assets/mui/images/icon13.png">
                    </div>
                    <div class="mui-media-body">
                        平台合作
                    </div>
                </a>
            </li>


            
        </ul>
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
