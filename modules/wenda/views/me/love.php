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
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我爱听的</title>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.indexedlist.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/love.css')?>
</head>
<body>
    <div class="mui-content" style="position: relative;">
	<?php if((count($items)>0)||(strlen($search->questiondescription)>0)):?>
        <div id='list' class="mui-indexed-list">
            <div class="mui-indexed-list-search mui-input-row mui-search" style="display: none;">
                <input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="输入关键词">
            </div>
			
			 <div class="row">
                <?php $form=ActiveForm::begin(['id'=>'search','enableAjaxValidation'=>false]); ?>

                <div class="col-md-8">
                    <?=$form->field($search,'questiondescription')->textinput(['placeholder'=>'输入关键词']);?>
                </div>

                  <div class="col-md-1" style="position:relative;">
                            <?=Html::submitButton(' ',['class'=>'btn btn-primary mui-icon mui-icon-search'])?>
                        </div>
                <?php ActiveForm::end()?>
            </div>
			 
        </div>
		<?endif?>
            <div class="mui-indexed-list-alert"></div>
            <div class="mui-indexed-list-inner">
                <div class="mui-indexed-list-empty-alert">没有数据</div>
                <ul class=" mui-table-view mui-table-view-striped mui-table-view-condensed">
				
				
				 
				
                    <?php if(isset($items)):?>
                    <?php foreach($items as $v):?>
                    <li class="love-li">
                        <div class="love-span">
                            <p class="mui-ellipsis" style="width:65%;"><?=$v->askproblem()->getUser()->nickname?> &#124; <?=$v->askproblem()->getUser()->title?> 回答了 <span class="mui-pull-right">&yen; <?=$v->askproblem()->askfee?></span></p>
                        </div>
                        <div class="love-h4">
                            <h4 style="font-size: 17px;"><?=$v->askproblem()->questiondescription?>?</h4>
                        </div>
                        <div class="mui-slider-cell love-top ">
                            <div class="oa-contact-cell mui-table">
                                <div class="oa-contact-avatar mui-table-cell">
                                    <img src="<?=$v->askproblem()->getUser()->headimgurl?>" />
                                </div>

                                <div class="oa-contact-content mui-table-cell ">
										    <a href="/wenda/wenda/paywenda?id=<?=$v->questionid?>">
                                        <div class="love-right">
                                        <button type="button"  id=""  onclick="" value="<?=$v->getAnswerquestion()->answercontent?>" class="mui-btn love-button ta mui-btn-primary" style="">
                                        
											<img src="/web/assets/mui/images/yuy.png" />
												点击爱听
											<audio id="<?=$v->getAnswerquestion()->answercontent?>audio" autoplay >
									<source id="<?=$v->getAnswerquestion()->answercontent?>source" src="" type="audio/mpeg" />
								</audio>
							
                                        </button>
									<!--	<button type="button"  id="<?=$v->getAnswerquestion()->answercontent?>stop" onclick="stopVoiceout()" style="display:none;" class="mui-btn love-button ta mui-btn-primary" style="">
                                            <img src="/web/assets/mui/images/strk.gif" />
                                            暂停播放
                                        </button>-->
                                        <span class="love-span1"><?=$v->getAnswerquestion()->answertimelength?>"</span>
                                    </div> 
                                    	</a>
									<div class="my-title1-color">
                                        <span class="mui-pull-left">14小时以前</span>
                                        <a class="mui-pull-right">
                                            <img src="/web/assets/mui/images/icon3.png" />
                                            <?=$v->lovenumber()?>人爱听
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
					
                   
                    <?endforeach?>
					  <?endif?>
					  
					  <?php if (count($items)<=0):?>
                   
                         <li style="background-color:#efeff4;">
                                <div style="margin-top:30px;">
									<div class="face">
										<img src="/web/assets/mui/images/face.png"/>
									</div>
									<p class="remind-text">您暂时还没有爱听的问！</p>
									<div class="mui-button-row">
										<a href="/wenda/lookforpeople/lookforpeople"><button type="button"  class="mui-btn-primary remind-button">找爱听</button>
										</a>
									</div>
								</div>
                            </li>
                    <?endif?>

                </ul>
            </div>
        </div>
    </div>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" charset="utf-8">
	  mui.init();
		
		//document.getElementById("askproblem-questiondescription").setAttribute("placeholder","输入关键词");
     

    </script>
	
	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs1.php'); ///引入微信分享1
    ?> 
    <!--End 结束分享功能-->


</body>
</html>
