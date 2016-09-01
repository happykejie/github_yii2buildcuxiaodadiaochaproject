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
    <title>专家资料</title>

    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.indexedlist.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/expert.css')?>
	
     <script type="text/javascript">
         //调用微信JS api 支付
         function jsApiCall()
         {
             WeixinJSBridge.invoke(
                 'getBrandWCPayRequest',
                 <?php echo $jsApiParameters; ?>
			function(res){
			    if(res.err_msg=='get_brand_wcpay_request:ok'){

			        //支付成功在这里添加题的问题到数据库
			        var askprice = document.getElementById("askprice").value;
			        var questiondescription = document.getElementById("askcontent").value;
			        var answerpersonid = document.getElementById("answerpersonid").value;
			        var ischbox =   document.getElementById('isopenask');
			        var trade_no =   document.getElementById('trade_no').value;					
			        //alert(trade_no);
			        var  isopenask =0;					
			        if(ischbox.checked)
			        {
			            isopenask =1;
			        }
			
			        $.ajax({
			            url: '/wenda/lookforpeople/addaskproblem',
			            type: 'get',
			            data: {'answerpersonid':answerpersonid,'trade_no':trade_no,'problemdescription':questiondescription,'problemprice':askprice,'isopenask':isopenask},
			            dataType: "text",
			            success: function (data) {
			                if(data>0)
			                {
			                    //alert('恭喜您，支付成功!');
			                    window.location.href="/wenda/lookforpeople/lookaskprobleminfo?id="+data;
			                }
			            },
			            error: function (xhr, errorType, error) {
			            }
			        });
			    }else{
			        WeixinJSBridge.log(res.err_msg);
			        //支付成功后执行
			        //alert('支付失败：'+res.err_code+res.err_desc+res.err_msg);
			    }
			}
		);
         }

         function callpay()
         {
            
             var questiondescription = document.getElementById("askcontent").value;
             if(questiondescription == ""){
                 alert("请先输入你的提问信息!");
                 return false;
             }
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




             
     

    </script>

            <script type="text/javascript">
                //获取共享地址
                function editAddress()
                {
                    //WeixinJSBridge.invoke(
                    //    'editAddress',
                    //    <?php echo $editAddress; ?>,
                    //function(res){
                    //    var value1 = res.proviceFirstStageName;
                    //    var value2 = res.addressCitySecondStageName;
                    //    var value3 = res.addressCountiesThirdStageName;
                    //    var value4 = res.addressDetailInfo;
                    //    var tel = res.telNumber;
                    //	
                    //    alert(value1 + value2 + value3 + value4 + ":" + tel);
                    //}
                    //);
                }
	
                window.onload = function(){
                    if (typeof WeixinJSBridge == "undefined"){
                        if( document.addEventListener ){
                            document.addEventListener('WeixinJSBridgeReady', editAddress, false);
                        }else if (document.attachEvent){
                            document.attachEvent('WeixinJSBridgeReady', editAddress); 
                            document.attachEvent('onWeixinJSBridgeReady', editAddress);
                        }
                    }else{
                        editAddress();
                    }
                };
	
    </script>
</head>
<body>

    <div id="detail" class="mui-content expert">
        <?php $form=ActiveForm::begin(['id'=>'search','enableAjaxValidation'=>false]); ?>
        <?php ActiveForm::end()?>
		
        <div class="expert-header">
            <div class="oa-contact-avatar1 ">
                <img src="<?=$model->headimgurl?>">
            </div>
			
			<input type="hidden" value="<?=$model->id?>" id="userid"/>
			<input type="hidden" value="<?=$trade_no?>" id="trade_no"/>
            <input type="hidden" id="answerpersonid" value="<?=$model->id?>" />
            <h4 class="expert-text"><?=$model->nickname?></h4>
            <p class="expert-text" style="font-size:13px;color:#999999;"><?=$model->title?></p>
            <h4 class="expert-h4"><?=$model->description?></h4>
            <div class="expert-text-right">
                <span class="expert-rs"><?=$model->attentionnumber()->count()?>人关注</span>

                <?php $form=ActiveForm::begin(['id'=>'expert','enableAjaxValidation'=>false]); ?>
                <input type="hidden" id="userattention-id" class="form-control" name="Userattention[id]" value="<?=$userattention->id?>">

                <?php if($userattention->id>0):?>
                <?=Html::submitButton('已关注',['class'=>'expert-button mui-btn mui-btn-primary ygz'])?>
                <?else:?>

                <?=Html::submitButton('关注',['class'=>'expert-button mui-btn mui-btn-primary gz'])?>
                <?endif?>
                <?php ActiveForm::end()?>
                <!--<button type="button" class="">
                    <img src="/web/assets/mui/images/icon10.png" />
                </button>-->
            </div>
        </div>

        <div class="expert-cont">
            <div class="mui-input-row expert-cont-wb">
                <textarea id="askcontent" rows="3" maxlength="50" placeholder="向Ta提问，Ta给你回答；超过24小时未回答，将按支付路径全额退款，每被人爱听1次，你将从中分成0.5元（问题限50字内）"></textarea>
                <p class="expert-cont-p" style="display: none;">
                    回答了<?php $askcount=$model->askproblemnumber()->count(); echo $askcount; ?>个问题|总收入<span class="expert-cont-span">

                        <?php $asksum=$model->askproblemnumber()->sum('askfee');
                              if($asksum):?>
                        <?=$asksum;?>
                        <?else:?>
                        0
                        <?endif?>
                    </span>元
                </p>
                <div class="mui-checkbox">
                    <div class="tongy" style="color:#999999;font-size: 14px;">
					
					  <input style="position: static;" name="checkbox" id="isopenask" value="Item 2" type="checkbox" checked="">
                        公开提问，答案每被人爱听1次，你就将获得0.5元
                    </div>
                </div>
                <h4 class="expert-cont-h4">&yen; <?=$model->questionprice?></h4>
                <input type="hidden" id="askprice" value="<?=$model->questionprice?>" />
                <div class="mui-button-row">
                    <button type="button" onclick="callpay()" class="mui-btn-primary expert-button1">问好了</button>
                </div>
            </div>
        </div>
        <div class="expert-interlocution">
            <?php if($askcount>0):?>
            <div class="huida">
                <h4 class="">Ta的回答</h4>
            </div>
            <?endif?>
            <ul class="mui-table-view">

                <?php if($askcount>0):?>

                <?php foreach($model->askproblemnumber()->all() as $v):?>

                <li class="love-li">
                    <div class="mui-slider-cell">
                        <div class="oa-contact-cell mui-table">
                            <div class="oa-contact-avatar mui-table-cell myq-img-left">
                                <img src="<?=$v->getUser()->headimgurl?>" />
                            </div>
                            <div class="oa-contact-content myq-right mui-table-cell">
                                <div class="mui-clearfix my-title">
                                    <h4 class="oa-contact-name"><?=$v->questiondescription?>?</h4>
                                </div>
                                <div class="my-title-color">&yen; <?=$v->askfee?></div>
                            </div>
                        </div>
                        <div class="mui-slider-cell expert-footer">



                            <div class="oa-contact-cell mui-table">
                                <div class="oa-contact-avatar mui-table-cell">
                                    <img src="<?=$model->headimgurl?>" />
                                </div>
                                <div class="oa-contact-content mui-table-cell myq-right ">
                                    <div class="love-right">
									<a   href="/wenda/wenda/paywenda?id=<?=$v->id?>">
                                        <button type="button" class="mui-btn love-button ta mui-btn-primary">
                                            <img src="/web/assets/mui/images/icon5.png">
                                            &yen;1.00元爱听
                                        </button>
									</a>	
                                        <span class="love-span1"><?=$v->getAnswerquestion()->answertimelength?>"</span>
                                    </div>
                                    <div class="my-title1-color">
                                        <span class="mui-pull-left" style="font-size: 15px;color:#999999;">
										
                                            <?php $hour=ceil((time()-strtotime($v->asktime))/3600);
                                                  if($hour>48):?>
                                            <? echo ceil((time()-strtotime($v->asktime))/3600/24)?>天前

                                         <?endif?>

                                            <?php   $minute=ceil((time()-strtotime($v->asktime))/60); 
                                                    if ($hour<48&&1<$hour):?>
                                            <? echo $hour ?> 小时前
                                        <?endif?>
                                            <?php   $minute=ceil((time()-strtotime($v->asktime))/60); 
                                                    if ($minute<=59):?>
                                            <? echo $minute ?> 分钟前
                                        <?endif?>


                                        </span>
                                        <a class="mui-pull-right expert-footer-img">
                                            <img src="/web/assets/mui/images/icon3.png" />
                                            <?=count($v->getLovelistenquestion())?>人爱听
                                        </a>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </li>
                <?endforeach?>
              
                <?endif?>

            </ul>
        </div>
    </div>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript" charset="UTF-8">
        mui.init();
        add();
        function add(){
            var guanz = $('.gz');
            var icon10 = $('<img>').attr('src','/web/assets/mui/images/icon10.png');
            var span = $('<span>').html('fdf')
			
            guanz.prepend(icon10); 
			
            var ygz = $('.ygz');
            var icon14 = $('<img>').attr('src','/web/assets/mui/images/icon14.png');
            ygz.prepend(icon14); 
	
        }
	
		 
    </script>
	
	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxjs.php'); ///引入微信分享1
    ?> 

    <!--End 结束分享功能-->
</body>
</html>
