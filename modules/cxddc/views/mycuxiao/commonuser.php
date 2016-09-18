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

          <?php if(Yii::$app->session->hasFlash('notattention')):?>
                    <div class="alert alert-success text">
                       
                        <script>
                            alert('还没关注该公众号，请先关注')
                            setTimeout('window.location.href=\'/cxddc/mycuxiao/infoercode\'', 1000);
                        </script>
                    </div>
                    <?endif?>


        <div class="my-header">
            <div class="oa-contact-avatar " style="margin-top:5px">
                <img src="<?=$items->headimgurl?>">
            </div>
            <input type="hidden" value="<?=$items->id?>" id="userid"/>
            <h4 class="text-h4"><?=$items->nickname?></h4>
         


            <div >
            <div style="text-align:left"> <p style="color:blue">促销大调查成为发布者平台协议：</p></div>
               <div style="padding-left:20px;text-align:left"> <p>成为发布者， 可以免费及时发布自己的促销信息。及时让别人知道你的活动信息</p></div>
            </div>
        
            <div class="my-teacher">
                 <?php if($items->userstate==2):?>
                 <a href="">发布者申请审核中……</a>
                 <?else:?>
                        <div style="height:30px">
    
                 <a href="becomepublisher?id=<?=$items->id?>">成为发布者</a>
                            </div>
                <?endif?>
            </div>

             <ul class="teacher-ul">
                <li>
                    <a href="infoercode">
                        <img src="/web/assets/mui/images/icon8.png" alt="" />
                        <div class="">关注</div>
                    </a>
                </li>
                <li>
                    <a href="fxercode">
                        <img style="width: 28px;" src="/web/assets/mui/images/icon8.png" alt="" />
                        <div class="bj">分享</div>
                    </a>
                </li>
            </ul>

        </div>
        <ul class="mui-table-view mui-table-view-chevron">
           
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
