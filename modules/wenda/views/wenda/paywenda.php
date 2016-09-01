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
    <meta charset="UTF-8">
    <title>支付问答</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <?=Html::cssFile('@web/web/assets/wenda/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/PaymentAnswer.css')?>

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
			        var questionid = document.getElementById("questionid").value;
			        // alert(questionid);
			        $.ajax({
			            url: '/wenda/wenda/addlovelisten',
			            type: 'post',
			            data: {'questionid':questionid},
			            dataType: "text",
			            success: function (data) {
							
							
							//alert(data);
			                if(data=='1')
			                {
			                    //alert('恭喜您，支付成功!');
			                    window.location.href='/wenda/wenda/paywenda?id='+questionid+'';
                                
			                }
			            },
						
			            error: function (xhr, errorType, error) {
						

							alert("支付失败，请联系管理员！");
							
			            }
			        });

			        
			    }else{
			        WeixinJSBridge.log(res.err_msg);
			        //支付成功后执行
					
					
			        //alert('支付失败：'+res.err_code+res.err_desc+res.err_msg);
					
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
					
                    <div class="mui-slider-cell pay-bottom">
                        <div class="oa-contact-cell mui-table">
                            <div class="oa-contact-avatar mui-table-cell">
                                <img src="<?php echo $item->getUseranswer()->headimgurl?>">
                            </div>
                            <div class="oa-contact-content mui-table-cell myq-right">	
								<!--<input id=\"input-none\" value=\"$content\" style=\"display:none\">-->
                                    <?php
									
                                    if (($item->questionstate==1&&$item->getIsPaywenda())||($item->questionstate==1&&$item->isfree==1))
                                    {
										
										
                                        $nswerquestions=$item->getAnswerquestions();
                                        if (isset($nswerquestions))
                                        {
											
                                            foreach ($nswerquestions as $v)
                                            {
												
												
												$i=1;
                                                $content = $v->answercontent;
												$timelength = $v->answertimelength;
                                            	$str1= " <div class=\"love-right\"><button type=\"button\" id=\"$content\" onclick=\"playvoiceout('$content')\"   class=\"mui-btn-primary love-backcolor\">
                                                        <img src=\"/web/assets/mui/images/icon5.png\">点击播放
														<audio id=\"$content-audio\" autoplay >
															<source id=\"$content-source\" src=\"\" type=\"audio/mpeg\" />
														</audio>
														
												</button>";
                                               
                                                
                                                $str2= "<button type=\"button\" id=\"$content-stop\" onclick=\"stopVoiceout()\" style=\"display:none\"  class=\"mui-btn-primary love-backcolor\">
                                                        <img src=\"/web/assets/mui/images/strk.gif\">停止播放</button>";
														
														 $str3= "<span id=\"$content-audioLength\" class=\"love-span1\">$timelength\"</span></div>";
												

                                
                                                $str4 =$str1.$str2.$str3;
                                                echo $str4;
												
												
												
                                            }
											
                                        }
                                    }
                                    else
                                    {
										$sum= $item->getAnswerquestionsum();
                                        $str1= "<div class=\"love-right\"> <button type=\"button\" onclick=\"callpay()\" class=\"mui-btn-primary love-backcolor df\">
                                                        <img src=\"/web/assets/mui/images/icon5.png\">
														&yen;1.00元爱听</button>";
                                        $str2= "<span class=\"love-span1\">$sum\"</span></div>";
                                        $str3 =$str1.$str2;
                                        echo $str3;
                                    }
                                    ?>
                                  
                                <div class="my-title1-color">
                                    <span class="mui-pull-left" style="font-size: 15px;">
                                        <?php $hour=ceil((time()-strtotime($item->asktime))/3600);
                                              if($hour>48):?>
                                        <? echo ceil((time()-strtotime($item->asktime))/3600/24)?>天前

                                         <?endif?>

                                        <?php  if ($hour<48&&1<$hour):?>

                                        <? echo $hour ?> 小时前
                                        <?endif?>

                                        <?php   $minute=ceil((time()-strtotime($item->asktime))/60); 
                                                if ($minute<=59):?>

                                        <? echo $minute ?> 分钟前
                                        <?endif?>

                                    </span>
                                    <div class="mui-pull-right patwenda-icon3 patwenda-position-right">
                                        <img src="/web/assets/mui/images/icon3.png">
                                        <?=count($item->getLovelistenquestion())?>人爱听
                                    </div>
									 <?php if(count($item->getLove())>0):?>
                                    <div class="mui-pull-right patwenda-icon4 patwenda-position-right">
                                        <img src="/web/assets/mui/images/icon4.png">
                                        <?=count($item->getLovelistenquestion())?>人爱听
                                    </div>
                                    <?endif?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed paywenda-ul-bottom">
            <li class="mui-table-view-cell">
                <a  class="mui-navigate-right " href="/wenda/lookforpeople/expert?id=<?php echo $item->getUseranswer()->id?>">
                    <div class="mui-slider-cell">
                        <div class="oa-contact-cell mui-table">
                            <div class="oa-contact-avatar mui-table-cell" style="vertical-align:middle;">
                                <img src="<?php echo $item->getUseranswer()->headimgurl?>" />
                            </div>
                            <div class="oa-contact-content mui-table-cell">
                                <div class="mui-clearfix" >
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
       mui.init({	});


			//备份mui.back，mui.back已将窗口关闭逻辑封装的比较完善（预加载及父子窗口），因此最好复用mui.back
		//var old_back = mui.back;
		//mui.back = function(){
			///document.getElementById("stopVoiceout").onclick(); 
		//}
		
	
		
		var currentplayid ="";
		var at = 0;
		var len = null ;
		var audio ;

		function  playvoiceout(id) {	
            len =  document.getElementById(id+"-audioLength").innerHTML;
					len = parseInt(len);
					
					timedCount();
				 if(currentplayid=="") ///first run 
				 {
					 playvoice(id);
				 }
				 if(id==currentplayid)
				 {
						playvoice(id);
				 }
				 
				 if(currentplayid.length>10)
				 {
				 if(id!=currentplayid)
				 {
					 ///停止当前音频播放
					stopVoiceout();
					
					if(id!=currentplayid)
					{
						playvoice(id);
					}
				 }
				 }		
				//return len;
	   }
			   
        function stopVoiceout(){
			at = " ";
        	document.getElementById(currentplayid+"-stop").style.display = "none";
        	document.getElementById(currentplayid).style.display = "initial";
        	document.getElementById(currentplayid).src = " "; 
        	audio.pause();//暂停播放 
        }
		
		function playvoice(id)
		{
				at = 0
			  audio = document.getElementById(id+"-audio");
				 currentplayid =id;
				document.getElementById(id).style.display = "none"; 
				document.getElementById(id+"-stop").style.display = "initial";
        	  		audio.play();//播放
		    	document.getElementById(id+"-source").src = "../../mediafile/"+id+""; 
		    	audio.load();
		}
		
		//判断时间
		function timedCount() {
			if(at == len){
				stopCount();
				at = 0;
				document.getElementById(currentplayid+"-stop").style.display = "none";
        	document.getElementById(currentplayid).style.display = "initial";
        	document.getElementById(currentplayid).src = " "; 
					return;
			}
				at++;
				console.log(at)
				t=setTimeout("timedCount()",1000) ;	
			} 
			function stopCount() 
			{ 
				clearTimeout(t) 
			} 
    </script>
	
    <!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxjs1.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->

</body>
</html>
