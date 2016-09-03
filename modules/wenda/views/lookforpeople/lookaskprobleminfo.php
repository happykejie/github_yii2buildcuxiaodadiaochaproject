<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>

<?php
require_once "models/WxJsSdk.php";
$jssdk = new WxJsSdk(WX_APPID, WX_APPSECRET);  ///张杰 wxf861f60fbb144cb9，2da66bd2cf0dccf0fb8d5db1e3ca6122
$signPackage = $jssdk->GetSignPackage();


?>


<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>提问详情</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <?=Html::cssFile('@web/web/assets/wenda/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/PaymentAnswer.css')?>

</head>
<body>
    <div class="mui-content" style=" position: relative;">

        <ul class="pay-header-ul mui-table-view mui-table-view-striped mui-table-view-condensed">
            <li class="mui-table-view-cell">
                <input type="hidden" id="questionid" name="questionid" value="<?php echo $item->id?>" />
                <div class="mui-slider-cell">
                    <div class="oa-contact-cell mui-table">
                        <div class="oa-contact-avatar mui-table-cell">
                            <img src="<?php echo $item->getUser()->headimgurl?>" />
                        </div>
                        <div class="oa-contact-content mui-table-cell">
                            <div class="mui-clearfix">
                                <h4 class="oa-contact-name" style="position: relative;top: 15px;font-size: 17px;width:75%;">
									<?php echo $item->getUser()->nickname?>
								</h4>
                                <span class="pay-span">&yen; <?php echo $item->askfee?></span>
                            </div>

                        </div>
                    </div>
                    <h4 style="font-size: 16px;"><?php echo $item->questiondescription?>?</h4>
                    
                </div>
            </li>
        </ul>

        <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
            <li class="mui-table-view-cell">
                <a  class="mui-navigate-right" href="/wenda/lookforpeople/expert?id=<?php echo $item->getUseranswer()->id?>">
                    <div class="mui-slider-cell">
                        <div class="oa-contact-cell mui-table">
                            <div class="oa-contact-avatar lookask mui-table-cell">
                                <img src="<?php echo $item->getUseranswer()->headimgurl?>" />
                            </div>
                            <div class="oa-contact-content mui-table-cell">
                                <div class="mui-clearfix">
                                    <h4 class="oa-contact-name"><?php echo $item->getUseranswer()->nickname?></h4>  
                                     <h6 class="oa-contact-position  paywenda-h6"><?php echo $item->getUseranswer()->title?></h6>
                                   
                                </div>
                                <p class="oa-contact-email mui-h6">
                                    <?php echo $item->getUseranswer()->attentionnumber()->count()?>人关注
                               
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
	 <?=Html::jsFile('@web/web/js/jquery.js')?>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

    <script type="text/javascript">
        mui.init()

    </script>
	
	
	<script>
	
	$(function(){
		//alert("testinfo");
		var questionid = $("#questionid").val();
		$.ajax({
			            url: '/wenda/lookforpeople/sendmsgtoteacher',
			            type: 'get',
			            data: {'questionid':questionid},
			            dataType: "text",
			            success: function (data) {
							alert(data);

			            },
			            error: function (xhr, errorType, error) {
							
							alert(error+'微信模板消息发送失败');
			            }
			});
		
	})
	
	
	</script>

   <!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxjs1.php'); ///引入微信分享
    ?> 

    <!--End 结束分享功能-->



</body>
</html>
