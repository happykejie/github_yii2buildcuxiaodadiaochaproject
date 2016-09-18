<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>移动端后台管理主页</title>
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
         

           

			<div class="my-teacher">
               
          
                <a href="publishinfo">发布促销消息</a>  
                
                
            </div>

            
        </div>
        <ul class="mui-table-view mui-table-view-chevron">
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right"  href="mypublished">
                    <div class="mui-pull-left border-radius love">
                        <img class="" src="/web/assets/mui/images/send.png">
                    </div>
                    <div class="mui-media-body">
                       我的发布
                    </div>
                </a>
            </li>

            <li class="mui-table-view-cell mui-media">
                <a class='mui-navigate-right' href="allpublished">
                    <div class="mui-pull-left border-radius question-mark">
                        <img class="" src="/web/assets/mui/images/myinfo.png">
                    </div>
                    <div class="mui-media-body">
                        所有发布
                    </div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right" href="usermanage">
                    <div class="mui-pull-left border-radius headset">
                        <img class="" src="/web/assets/mui/images/hezuo.png">
                    </div>
                    <div class="mui-media-body">
                        人员管理
                    </div>
                </a>
            </li>

              <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right" href="banuser">
                    <div class="mui-pull-left border-radius headset">
                        <img class="" src="/web/assets/mui/images/hezuo.png">
                    </div>
                    <div class="mui-media-body">
                        禁用人员
                    </div>
                </a>
            </li>


              <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right" href="statistics">
                    <div class="mui-pull-left border-radius headset">
                        <img class="" src="/web/assets/mui/images/hezuo.png">
                    </div>
                    <div class="mui-media-body">
                        系统统计
                    </div>
                </a>
            </li>


            
        </ul>
    </div>
	
	

    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript" charset="UTF-8">
        mui.init();
    </script>
	


	
	
</body>
</html>
