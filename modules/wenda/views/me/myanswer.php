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
    <meta charset="utf-8">
    <title>我的回答</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--标准mui.css-->
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>

    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/MyQuestion.css')?>
</head>
<body>
    <div class="mui-content" style="position: relative;" >
        <div style="padding: 10px 10px;">
            <div class="mui-col-xs-12" style="position:relative;">
                <div class="mui-col-xs-6 myq-se">
                    <div id="segmentedControl" class="mui-segmented-control">
                        <a class="mui-control-item mui-active" href="#Answer">已回答</a>
                        <a class="mui-control-item" href="#AnswerTo" id="answerTo">待回答
                                  
                        </a>
					
                    </div>
					 <!-- <span class="mui-badge mui-badge-danger mui-badge-inverted mui-pull-right" style="padding:3px;position:absolute;top:10px;right:0;"> </span>-->
						   <?php if(count($itemsno)>0):?>
                                  
									<span class="mui-badge mui-badge-danger " style="position:absolute;top:-3px;right:-9px;"><?php echo count($itemsno)?></span>
									<?endif?>
                </div>
                <div class="mui-col-xs-2 shou mui-pull-right">
                     <a href="/wenda/wenda/search">
                        <img class="shousuo" src="/web/assets/mui/images/shousuo.png" alt="" /></a>
                </div>
            </div>
        </div>
        
            <div id="Answer" class="mui-control-content mui-active">
                <div id="scroll" class="">
                    <div class="mui-scroll myquestion mabottom" style="margin-bottom:70px;">
                        <ul class="mui-table-view">
                            <?php if(count($itemsok)>0):?>
                            <?php foreach($itemsok as $v):?>
                            <li class="love-li ">
                                <div class="mui-slider-cell">
                                    <div class="oa-contact-cell mui-table">
                                        <div class="oa-contact-avatar mui-table-cell myq-img-left">
                                            <img src="<?=$v->getUser()->headimgurl?>" />
                                        </div>
                                        <div class="oa-contact-content myq-right mui-table-cell">
                                            <div class="mui-clearfix my-title">
                                                <h4 class="oa-contact-name"><?=$v->questiondescription?>?</h4>
                                            </div>
                                            <div class="my-title-color">&yen;<?=$v->askfee?></div>
                                        </div>
                                    </div>
                                    <div class="mui-slider-cell myquer-maigntop ">
                                        <div class="oa-contact-cell mui-table">
                                            <div class="oa-contact-avatar mui-table-cell">
                                                <img src="<?=$v->getUseranswer()->headimgurl?>" />
                                            </div>
                                            <div class="oa-contact-content mui-table-cell myq-right ">
											  <a href="/wenda/wenda/paywenda?id=<?=$v->id?>">
                                                <div class="love-right">
                                                     <button type="button" id="" class="mui-btn-primary love-backcolor" >
                                                        <img src="/web/assets/mui/images/icon5.png">
                                                        点击播放															
													</button>
                                                 <span id="audioLength" class="love-span1"> <? echo $v->getAnswerquestionsum(); ?>"</span>
                                                </div>
												</a>
                                                <div class="my-title1-color">
                                                    <span class="mui-pull-left" style="font-size: 15px;">

                                                        <?php $hour=ceil((time()-strtotime($v->asktime))/3600);
                                                              if($hour>48):?>
                                                        <? echo ceil((time()-strtotime($v->asktime))/3600/24)?>天前

                                         <?endif?>

                                                        <?php  if ($hour<48&&1<$hour):?>

                                                        <? echo $hour ?>小时前
                                        <?endif?>

                                                        <?php   $minute=ceil((time()-strtotime($v->asktime))/60); 
                                                                if ($minute<=59):?>

                                                        <? echo $minute ?>分钟前
                                        <?endif?>
                                                    </span>
                                                    <a class="mui-pull-right myquestion-icon3">
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
                            <?else:?>
								 <li style="background-color:#efeff4;">
										<div style="margin-top:30px;">
									<div class="face">
										<img src="/web/assets/mui/images/face.png"/>
									</div>
									<p class="remind-text">您暂时还没有已回答的问题哦！</p>
								<!--	<div class="mui-button-row">
										<a href="/wenda/lookforpeople/lookforpeople"><button type="button"  class="mui-btn-primary remind-button">去找人</button>
										</a>
									</div>-->
								</div>
                            </li>
                            <?endif?>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="AnswerTo" class="mui-control-content">
                <div class="myquestion mabottom">
                    <ul class=" mui-table-view mui-table-view-striped mui-table-view-condensed">
                        <?php if(count($itemsno)>0):?>
                        <?php foreach($itemsno as $v):
                                  $hour=ceil((time()-strtotime($v->asktime))/3600);?>
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
                                        <div class="my-title-color">&yen;<?=$v->askfee?></div>
                                    </div>
                                </div>


                                <div class="mui-slider-cell  love-top" >

                                    <div class="oa-contact-cell mui-table">
                                        <div class="oa-contact-avatar mui-table-cell">
                                            <img src="<?=$v->getUseranswer()->headimgurl?>" />
                                        </div>
                                        <div class="oa-contact-content mui-table-cell ">
                                            <?php  if($hour>72):?>
                                          <button type="button" disabled class="mui-btn yfq mui-btn-primary" style="">
                                                已过期
                                            </button>
                                         <?endif?>

                                            <?php  if ($hour<=72):?>

                                            <button type="button" class="mui-btn huida mui-btn-primary" style="">
                                                <a href="/wenda/wenda/recordings?id=<?=$v->id?>">马上回答 </a>
                                            </button>
                                            <?endif?>


                                            <a class="mui-pull-right huida-a">
                                                <img src="/web/assets/mui/images/icon20.png" />

                                                <?php  if($hour>48):?>
                                                <? echo ceil((time()-strtotime($v->asktime))/3600/24)?>天前

                                         <?endif?>

                                                <?php  if ($hour<48&&1<$hour):?>

                                                <? echo $hour ?> 小时前
                                        <?endif?>

                                                <?php   $minute=ceil((time()-strtotime($v->asktime))/60); 
                                                        if ($minute<=59):?>

                                                <? echo $minute ?> 分钟前
                                        <?endif?>

                                            </a>
                                        </div>
                                    </div>


                                </div>
                        </div>
                                 </li>
						

                        <?endforeach?>

                        <?else:?>
                              <li style="background-color:#efeff4;">
                                <div style="margin-top:30px;">
									<div class="face">
										<img src="/web/assets/mui/images/face.png"/>
									</div>
									<p class="remind-text">您暂时还没有待回答的问题哦！</p>
								<!--	<div class="mui-button-row">
										<a href="/wenda/lookforpeople/lookforpeople"><button type="button"  class="mui-btn-primary remind-button">去找人</button>
										</a>
									</div>-->
								</div>
                            </li>
                        <?endif?>
                    </ul>
                </div>
            </div>

       
	<div style="hight:12px;"></div>
    </div>
	<div style="hight:1px;"></div>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

    <script>

      

        mui.init({
            swipeBack: true //启用右滑关闭功能
        });
		//mui.ready(function(){
			//console.log("123412151")
			//$("#answerTo").click(function(){
				//console.log("123");
			//stopAllVoice();
			//})
		//})
        (function ($) {
            //$('#scroll').scroll({
            //    indicators: true //是否显示滚动条
            //});
            var segmentedControl = document.getElementById('segmentedControl');
            $('.mui-input-group').on('change', 'input', function () {
                if (this.checked) {
                    var styleEl = document.querySelector('input[name="style"]:checked');
                    var colorEl = document.querySelector('input[name="color"]:checked');
                    if (styleEl && colorEl) {
                        var style = styleEl.value;
                        var color = colorEl.value;
                        segmentedControl.className = 'mui-segmented-control' + (style ? (' mui-segmented-control-' + style) : '') + ' mui-segmented-control-' + color;
                    }
                }
            });
        })(mui);

		
		
    </script>
	
	
		<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs1.php'); ///引入微信分享1
    ?> 
    <!--End 结束分享功能-->
	
	
	

</body>
</html>
