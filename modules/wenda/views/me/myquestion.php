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
    <title>我的问题</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--标准mui.css-->
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>

    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>
    <?=Html::cssFile('@web/web/assets/wenda/css/went.css')?>
</head>
<body>
    <div class="mui-content" style="position: relative;">
        <div style="padding: 10px 10px;">
            <div class="mui-col-xs-12" style="position:relative;">
                <div class="mui-col-xs-6 myq-se">
                    <div id="segmentedControl" class="mui-segmented-control">
                        <a class="mui-control-item mui-active" href="#Answer">已回答</a>
                        <a class="mui-control-item" href="#AnswerTo">未回答
                           
                        </a>
                    </div>
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
        <div>
            <div id="Answer" class="mui-control-content mui-active">
                <div id="scroll" class="">
                    <div class="mui-scroll myquestion">
                        <ul class="mui-table-view" style="margin-bottom: 50px;">
                  
                            <?php if(count($itemsok)>0):?>
                            <?php foreach($itemsok as $v):
                                      $hour=ceil((time()-strtotime($v->asktime))/3600);?>
                            <li class="love-li">
                                <div class="love-span">
                                <p class="mui-ellipsis" style="width:70%;font-size: 13px;"><?=$v->getuser()->nickname?> | <?=$v->getuser()->title?> 回答了我
                                   <span class="mui-pull-right  dhd">&yen;<?=$v->askfee?></span>
                                </p>
                            </div>
                                <div class="mui-slider-cell">
                                    <div class="oa-contact-cell mui-table">
                                       
                                        <div class="oa-contact-content myq-right mui-table-cell">
                                            <div class="mui-clearfix my-title">
                                                <h4 class="oa-contact-name"><?=$v->questiondescription?>?</h4>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="mui-slider-cell">
                                        <div class="oa-contact-cell mui-table">
                                            <div class="oa-contact-avatar mui-table-cell">
                                                <img src="<?=$v->getUseranswer()->headimgurl?>" />
                                            </div>
                                            <div class="oa-contact-content mui-table-cell myq-right ">
											  <a href="/wenda/wenda/paywenda?id=<?=$v->id?>">
                                                     <div class="love-right">
                                                    <button type="button" id="<?=$v->getAnswerquestion()->answercontent?>"   value="<?=$v->getAnswerquestion()->answercontent?>" class="mui-btn-primary love-backcolor">
                                                        <img src="/web/assets/mui/images/icon5.png">
                                                           点击播放
															<audio id="<?=$v->getAnswerquestion()->answercontent?>audio" autoplay >
																<source id="<?=$v->getAnswerquestion()->answercontent?>source" src="" type="audio/mpeg" />
															</audio>
													</button>
													<button type="button" id="<?=$v->getAnswerquestion()->answercontent?>stop" onclick="stopVoiceout()" style="display:none;" class="mui-btn-primary love-backcolor">
                                                        <img src="/web/assets/mui/images/strk.gif">
                                                        暂停播放</button>
                                                    <span class="love-span1"> <? echo $v->getAnswerquestionsum(); ?>"</span>
                                                    
                                                </a>
												</div>
                                                <div class="my-title1-color">
                                                    <span class="mui-pull-left" style="color:#999999; font-size: 15px;">                  <?php 
                                      if($hour>48):?>
                                                        <? echo ceil((time()-strtotime($v->asktime))/3600/24)?>天前

                                         <?endif?>

                                                        <?php  if ($hour<=48&&1<=$hour):?>

                                                        <? echo $hour ?> 小时前
                                        <?endif?>

                                                        <?php   $minute=ceil((time()-strtotime($v->asktime))/60); 
                                                                if ($minute<=59):?>

                                                        <? echo $minute ?> 分钟前
                                        <?endif?>

                                                    </span>
                                                    <a class="mui-pull-right went-icon3">
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
									<p class="remind-text">您暂时还没有问题哦！</p>
									<div class="mui-button-row">
										<button type="button"  class="mui-btn-primary remind-button">找人问</button>
								  
									</div>
								</div>
                            </li>
                            <?endif?>
                        </ul>
                    </div>
					<div style="height:12px;"></div>
                </div>
            </div>
            <div id="AnswerTo" class="mui-control-content">
                <div class="myquestion">
                    <ul class=" mui-table-view mui-table-view-striped mui-table-view-condensed" style="    margin-bottom: 50px;">
                        <?php if(count($itemsno)>0):?>
                        <?php foreach($itemsno as $v):
                                  $hour=ceil((time()-strtotime($v->asktime))/3600);?>
                        <li class="love-li">
                            <div class="love-span">
                                <p class="mui-ellipsis" style="width:70%;font-size:13px;"><?=$v->getUseranswer()->nickname?> &#124; <?=$v->getUseranswer()->title?>


                                    <?php 
                                  if($hour>72):?>
                                    
                                     <span class="mui-pull-right  ygq" style="font-size:17px;">已过期</span>
                                    <?endif?>

                                    <?php  if ($hour<=72):?>

                                   <span style="font-size:17px;" class="mui-pull-right  dhd">待回答</span>
                                </p>
                                <?endif?>



                            </div>
                            <div class="love-h4">
                                <h4><?=$v->questiondescription?>?</h4>
                            </div>
                            <div class="mui-slider-cell  love-top">
                                <div class="oa-contact-cell mui-table">
                                    <div class="oa-contact-avatar mui-table-cell">
                                        <img src="<?=$v->getUser()->headimgurl?>" />
                                    </div>
                                    <div class="oa-contact-content mui-table-cell ">

                                        <div>
                                            <button type="button" class="mui-btn  went-back mui-btn-primary">
                                                <img src="/web/assets/mui/images/icon21.png" />

                                                <?php 
                                  if($hour>48):?>
                                                <? echo ceil((time()-strtotime($v->asktime))/3600/24)?>天前

                                         <?endif?>

                                                <?php  if ($hour<=48&&1<$hour):?>

                                                <? echo $hour ?> 小时前
                                        <?endif?>

                                                <?php   $minute=ceil((time()-strtotime($v->asktime))/60); 
                                                        if ($minute<=59):?>

                                                <? echo $minute ?> 分钟前
                                        <?endif?>


                                            </button>
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
									<p class="remind-text">您暂时还没有问题哦！</p>
									<div class="mui-button-row">
										<button type="button"  class="mui-btn-primary remind-button">找人问</button>
								  
									</div>
								</div>
                            </li>
                        <?endif?>
                    </ul>
                </div>
				<div style="height:12px;"></div>
            </div>
        </div>
    </div>
	<div style="height:1px;"></div>
    <script src="../js/mui.min.js"></script>
    
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        mui.init({
            swipeBack: true //启用右滑关闭功能
        });
	
    </script>

	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs1.php'); ///引入微信分享1
    ?> 
    <!--End 结束分享功能-->
	

</body>
</html>
