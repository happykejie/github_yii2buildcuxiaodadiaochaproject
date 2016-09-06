<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

?><!DOCTYPE html>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我</title>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
   
         <?=Html::cssFile('@web/web/assets/cxddc/css/teacher.css')?>
</head>
<body>
    <div class="mui-content" >
        <div class="my-header">
            <div class="oa-contact-avatar " style="margin-top:5px">
                <img src="<?=$items->headimgurl?>">
            </div>
            <input type="hidden" value="<?=$items->id?>" id="userid"/>
            <h4 class="text-h4"><?=$items->nickname?></h4>
         


            <div >
            <div style="text-align:left"> <p style="color:blue">促销大调查成为发布者平台协议：</p></div>
               <div style="padding-left:20px;text-align:left"> <p>成为发布者， 可以免费及时发布自己的促销信息。每个发布者每天可以免费发布1条促销信息。</p></div>
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
                        <div class="">二维码</div>
                    </a>
                </li>
                <li>
                    <a href="becomepublisher?id=<?=$items->id?>">
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
</body>
</html>
