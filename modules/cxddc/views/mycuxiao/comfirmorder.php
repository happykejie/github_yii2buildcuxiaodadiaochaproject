
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


<!doctype html>
<html>

	<head>
		<meta charset="utf-8">
		<title>促销信息详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<!--标准mui.css-->
		  <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
		<!--App自定义的css-->
		<?=Html::cssFile('@web/web/assets/cxddc/css/user.css')?>
             <?=Html::cssFile('@web/web/assets/cxddc/css/publishdetail.css')?>


               <script type="text/javascript">
                   //调用微信JS api 支付
		
		
                   var  onclicknum = 0;
		
                   function jsApiCall()
                   {
                       WeixinJSBridge.invoke(
                            'getBrandWCPayRequest',
                           <?php echo $jsApiParameters; ?>,
                   
			                function(res){

			    if(res.err_msg=='get_brand_wcpay_request:ok'){

			        //支付成功在这里添加用户爱听
			        var publishid = document.getElementById("publishid").value;
			       // alert(publishid);
			        $.ajax({
			            url: '/cxddc/mycuxiao/paysuccess',
			            type: 'post',
			            data: {'publishid':publishid},
			            dataType: "text",
			            success: function (data) {
							
			                if(!data)
			                {
			                    alert("支付失败，请联系管理员！");
			                    return false;
			                }
			           
			                   /// alert('恭喜您，支付成功!');
			                    window.location.href='/cxddc/mycuxiao/index';
			                    return false;
			                
			            },
						
			            error: function (xhr, errorType, error) {
						

			                alert("支付失败，请联系管理员！");
							
			            }
			        });

			        
			    }else{
			        WeixinJSBridge.log(res.err_msg);
			        //支付成功后执行
					
					
			        alert('支付失败：'+res.err_code+res.err_desc+res.err_msg);
					
			        onclicknum=0;
			    }
			}
		);
        }

        function callpay()
        {
            
            if(onclicknum==0)
            {
                onclicknum = onclicknum+1;
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else{
                    jsApiCall();
                }
            }
        }
			 
			 
      



		
		

    </script>





    

	</head>

	<body>
		
		<div class="mui-content">
			<div class="mui-content-padded">

                <input type="hidden" id="publishid" name="publishid" value="<?php echo $item->id?>" />

                <div id="headtitle">
                    <?=$item->name?>
                </div>

			     <div id="headimg">
					<img src="<?=$item->surface?>" data-preview-src="" data-preview-group="1" />
				</div>
               <p class="showlable">开始时间:</p>
            <p class="showcontent">  <?=$item->start_time?></p>  
                <p class="showlable">结束时间:</p>
              
                <p class="showcontent">  <?=$item->end_time?> </p> 
                
              <p class="showlable">地址:</p>

              
                <p class="showcontent">  <?=$item->address?></p> 

               <p class="showlable">咨询方式:</p>
               
              
                <p class="showcontent">  <?=$item->contacttype?> </p> 
               
                <p class="showlable">活动介绍:</p>

                <div id="acdescription">   <p class="showcontent"> <?=$item->intro?></p> </div>
             
				

                <div id="cxxc">
                   促销现场
                </div>

               <div id="divdetailimg">


               

                   <?php if(count($arryimg)>0):?>
                <?php foreach($arryimg as $m):?>
                <?php if(strlen($m)>5):?>

                <div class="detailimg">
                      <img src="<?=$m?>" data-preview-src="" data-preview-group="1" />
                </div>

                <?endif?>
                <?endforeach?>
                <?endif?>


                   </div>

                
               
			</div>
              
            
		</div>

          <div class="my-teacher">
        
                <a href="#" onclick="callpay()">确认支付</a>  
            
            </div>

        <div style="height:100px"></div>
  
   
       
                 
	</body>
       <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
       <?=Html::jsFile('@web/web/assets/mui/js/mui.zoom.js')?>
       <?=Html::jsFile('@web/web/assets/mui/js/mui.previewimage.js')?>

     <?=Html::jsFile('@web/web/js/jquery.js')?>

	<script>
	    mui.previewImage();



	</script>

    <input type="hidden" value="<?= $currentuserid?>" id="userid"/>
      	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxzhuyejs.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->

</html>