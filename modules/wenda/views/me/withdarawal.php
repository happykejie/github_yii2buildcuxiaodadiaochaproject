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
    <title>收入</title>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/withdarawal.css')?>
</head>
<body>
    <div class="mui-content" style="position: relative;margin-bottom:50px;">
		    <div class="withdarawal">
		    	<h4 class="income">当前总收入（元）</h4>
				 
				<h3  id="money" class="money">
				

                    <?php  $moneyfloat=($items->incomecost3mnumber()->sum('incomecostnum'))+($items->incomecost2mnumber()->sum('incomecostnum'))+($items->incomecost5mnumber()->sum('incomecostnum'))+($items->incomecost6mnumber()->sum('incomecostnum')); ?>
                    <?php $format_number = number_format($moneyfloat, 2, '.', ''); ?>

                    <?php $money=$format_number;
                          if(!isset($money)):?>
                    0
                     <?endif?>
                    <?php if(isset($money)):
                              echo $money?>
                    <?endif?>
				
				</h3>
		    	
		    </div>
		    <div class="weix">
		    	<p class="weix-text">平台提现说明：平台收取总提现整数金额的10%为平台费用,小于1元提现不收取任何费用。申请提现后会进入系统审核期，审核期不超过2天。审核通过金额会入库微信钱包，请在微信钱包中查看收入情况。</p>
		    </div>
		    <div class="withdarawal-footer">
			    <?php $form=ActiveForm::begin(['id'=>'withdarawal','enableAjaxValidation'=>false]); ?>

             
				  
				  <input type="hidden" id="Enterprisepay-money" class="form-control" name="Enterprisepay[money]" value="<?=$money?>">

				<?=Html::submitButton('立即提现',['id'=>'sub','class'=>'withdarawal-button mui-btn mui-btn-primary mui-btn-block'])?>
				


				  <?php ActiveForm::end()?>
		    </div>
		    
		   
		</div>

    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript" charset="UTF-8">
        mui.init();

    </script>
	
	
		<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs1.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->
</body>
</html>