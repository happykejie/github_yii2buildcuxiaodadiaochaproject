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
     <?=Html::cssFile('@web/web/assets/wenda/css/my.css')?>
</head>
<body>
    <div class="mui-content" >
        <div class="my-header">
            <div class="oa-contact-avatar ">
                <img src="/web/assets/mui/images/header.png">
            </div>
            <h4><?=$items->username?></h4>
            <p>成为老师 ， 邀请好友向你提问，</p>
            <p>回答后被他人收听 ， 可持续获得收入哦</p>
            <div class="my-teacher">
                 <?php if($items->userstate==2):?>
                 <a href="">老师申请审核中……</a>
                 <?else:?>
                 <a href="becometeacher?id=<?=$items->id?>">成为老师</a>
                <?endif?>
            </div>
        </div>
        <ul class="mui-table-view mui-table-view-chevron">
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right"  href="follow?id=<?=$items->id?>">
                    <div class="mui-pull-left border-radius love">
                        <img class="" src="/web/assets/mui/images/icon10.png">
                    </div>
                    <div class="mui-media-body">
                        我的关注
                    </div>
                </a>
            </li>

            <li class="mui-table-view-cell mui-media">
                <a class='mui-navigate-right' href="MyQuestion.html">
                    <div class="mui-pull-left border-radius question-mark">
                        <img class="" src="/web/assets/mui/images/icon12.png">
                    </div>
                    <div class="mui-media-body">
                        我的问题
                    </div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right">
                    <div class="mui-pull-left border-radius headset">
                        <img class="" src="/web/assets/mui/images/icon13.png">
                    </div>
                    <div class="mui-media-body">
                        我的爱听
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
