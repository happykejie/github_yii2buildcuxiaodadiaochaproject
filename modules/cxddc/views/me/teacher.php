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
    <?=Html::cssFile('@web/web/assets/mui/css/css/teacher.css')?>
</head>
<body>
    <div id="teacher" class="mui-content " style="position: relative;">
        <div class="my-header">

            <div class="oa-contact-avatar ">
                <img src="<?=$items->headimgurl?>">
            </div>
            <input type="hidden" value="<?=$items->id?>" id="userid"/>
            <h4 class="text-h4"><?=$items->nickname?></h4>
            <h4 class="lh-h4"><?=$items->description?></h4>
            <p class="teacher-p">向我提问需要支付<span class="text-span"> &yen; <?=$items->questionprice?></span></p>
            <p class="teacher-p"><?=$items->attentionnumber()->count()?>人关注我</p>
            <p class="teacher-p">
                回答了<?=$items->askproblemnumber()->count()?>问题<label style="font-size: 16px; margin-left: 3px; margin-right: 3px;">|</label>
                总收入<span class="text-span">

                    <?php  $moneyfloat=($items->incomecost3mnumber()->sum('incomecostnum'))+($items->incomecost2mnumber()->sum('incomecostnum'))+($items->incomecost5mnumber()->sum('incomecostnum'))+($items->incomecost6mnumber()->sum('incomecostnum')); ?>
                    <?php $format_number = number_format($moneyfloat, 2, '.', ''); ?>

                    <?php $money=$format_number;
                          if($money==0):?>
                    0 元
                     <?endif?>
                    <?php if($money>0):
                              echo $money?> 元 
                <?php if($items->banfee==1):?>
                    <a class="withdrawals" href="#">当前用户资金账户被冻结，请联系平台商解冻</a>
                    <?php else:?>
                    <a class="withdrawals" href="withdarawal">立即提现</a> </span>（平台将收取收益10%的服务费）
            </p>
            <?endif?>
            <?endif?>

            <ul class="teacher-ul">
                <li>
                    <a href="info">
                        <img src="/web/assets/mui/images/icon8.png" alt="" />
                        <div class="">二维码</div>
                    </a>
                </li>
                <li>
                    <a href="becometeacher?id=<?=$items->id?>">
                        <img style="width: 28px;" src="/web/assets/mui/images/icon9.png" alt="" />
                        <div class="bj">编辑</div>
                    </a>
                </li>
            </ul>

        </div>
        <ul class="mui-table-view mui-table-view-chevron">
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right" href="follow?id=<?=$items->id?>">
                    <div class="mui-pull-left border-radius love">
                        <img class="" src="/web/assets/mui/images/icon10.png">
                    </div>
                    <div class="mui-media-body">
                        我的关注
                    </div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media">
                <a class='mui-navigate-right' href="myanswer?id=<?=$items->id?>">
                    <div class="mui-pull-left border-radius my-answer">
                        <img class="" src="/web/assets/mui/images/icon11.png">
                    </div>
                    <div class="mui-media-body">
                        我的回答
                         <?php $answercount=$items->Myanswer();
                               if(count($answercount)>0):?>
                        <span class="mui-badge mui-badge-danger mui-pull-right">

                            <? echo count($answercount)?>

                        </span>
                        <?endif?>
                    </div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media">
                <a class='mui-navigate-right' href="myquestion?id=<?=$items->id?>">
                    <div class="mui-pull-left border-radius question-mark">
                        <img class="" src="/web/assets/mui/images/icon12.png">
                    </div>
                    <div class="mui-media-body">
                        我的问题

                         <?php $questioncount=$items->Myquestion();
                               if(count($questioncount)>0):?>
                        <span class="mui-badge mui-badge-danger mui-pull-right">

                            <? echo count($questioncount)?>

                        </span>
                        <?endif?>
                    </div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media">
                <a class="mui-navigate-right" href="love?id=<?=$items->id?>">
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


    <!--Start 引入分享功能-->
    <?php 
    require(BASE_PATH . '/config/wxfxjs.php'); ///引入微信分享
    ?>
    <!--End 结束分享功能-->


</body>
</html>
