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
    <?=Html::cssFile('@web/web/assets/wenda/css/teacher.css')?>

</head>
<body>
	
    <div class="mui-content" style="position: relative;">
        <div class="my-header">
            <div class="oa-contact-avatar ">
                <img src="<?=$items->headimgurl?>">
            </div>
			
			<input type="hidden" value="<?=$items->id?>" id="userid"/>
            <h4 class="text-h4"><?=$items->nickname?></h4>

            <p class="t-p">向专家提问，每被人收听一次，你与专家各获得0.5元。成为老师，邀请好友向你提问，回答后被他人收听，可持续获得收入哦</p>
            <p class="t-p smn">总收入
					<span class="sum-span">

                        <?php echo $items->incomecost3mnumber()->sum('incomecostnum'); ?>
                        <?php echo $items->incomecost2mnumber()->sum('incomecostnum'); ?>
                        <?php echo $items->incomecost5mnumber()->sum('incomecostnum'); ?>
                        <?php echo $items->incomecost6mnumber()->sum('incomecostnum'); ?>


               <?php  $moneyfloat=($items->incomecost3mnumber()->sum('incomecostnum'))+($items->incomecost2mnumber()->sum('incomecostnum'))+($items->incomecost5mnumber()->sum('incomecostnum'))+($items->incomecost6mnumber()->sum('incomecostnum')); ?>
                 <?php $format_number = number_format($moneyfloat, 2, '.', ''); ?>  

                     <?php $money=$format_number;
                          if($money==0):?>
                    0 元
                     <?endif?>
                    <?php if($money>0):
                              echo $money?> 元 
                 <?php if($items->banfee==1):?>
                                         <span style="color:green">当前用户资金账户被冻结，请联系平台商解冻</span>
                                <?php else:?>
                        <span>
                <a class="withdarawal" href="withdarawal">立即提现</a>  （平台将收取收益10%的服务费）</span>
                                <?endif?>
                    <?endif?>
               
			</p>
			<div class="my-teacher">
                <?php if($items->userstate==2):?>
                <a href="#">审核中……</a>
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
