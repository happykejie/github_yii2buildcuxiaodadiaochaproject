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
    <title>我关注的老师</title>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.indexedlist.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/new_file.css')?>
</head>
<body>
    <div class="mui-content" style="position:relative;">
 <?php if((count($items)>0)||(strlen($search->nickname)>0)):?>
        <div class="mui-indexed-list-search mui-input-row mui-search">
           <!-- <input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="输入名称">-->
              
			   <div class="row">
                <?php $form=ActiveForm::begin(['id'=>'search','enableAjaxValidation'=>false]); ?>

                <div class="col-md-8">
                    <?=$form->field($search,'nickname')->textinput(['placeholder'=>'请输入昵称、关键词找人']);?>
                </div>

                <div class="col-md-1"  style="position:relative;">
                    <?=Html::submitButton(' ',['class'=>'btn btn-primary mui-icon mui-icon-search'])?>
                </div>
                <?php ActiveForm::end()?>
            </div>
			
        </div>
		        <?endif?>
        <ul id="" class="mui-table-view mui-table-view-striped mui-table-view-condensed">
            <?php if(isset($items)):?>
            <?php foreach($items as $v):?>
            <li class="mui-table-view-cell">
                <div class="mui-slider-cell">
                    <a class="follow-a" href="/wenda/lookforpeople/expert?id=<?=$v->id?>">
                        <div class="oa-contact-cell mui-table">
                            <div class="oa-contact-avatar mui-table-cell">
                                <img src="<?=$v->headimgurl?>" />
                            </div>
                            <div class="oa-contact-content mui-table-cell">
                                <div class="mui-clearfix" style="">
                                    <h4 class="oa-contact-name"><?=$v->nickname?></h4>
									
                                   <h6 class="oa-contact-position follow-h6"><?=$v->title?></h6>
                                </div>
								 
                                <p class="oa-contact-email mui-h6">
                                     <?=$v->askproblemnumber()->count()?>个回答,<?=$v->attentionnumber()->count()?>个人关注
                                </p>
                                </p>
                            </div>
							 <?php $form=ActiveForm::begin(['id'=>'lookforpeople','enableAjaxValidation'=>false]); ?>
                    <input type="hidden" id="userattention-id" class="form-control" name="Userattention[id]" value="<?=$v->attenuserattention()->id?>">
                    <input type="hidden" id="userattention-attentionuserid" class="form-control" name="Userattention[attentionuserid]" value="<?=$v->id?>">
                    <?php if($v->attenuserattention()->id>0):?>
                    <?=Html::submitButton('已关注',['class'=>'mui-btn  zhaor-button yiguanz mui-btn-primary'])?>
                    <?else:?>

                    <?=Html::submitButton('关注',['class'=>'mui-btn zhaor-button guanz mui-btn-primary'])?>
                    <?endif?>
                    <?php ActiveForm::end()?>
                        </div>
                    </a>
                   
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
									<p class="remind-text">您暂时还没有关注任何人哦！</p>
									<div class="mui-button-row">
										<a href="/wenda/lookforpeople/lookforpeople"><button type="button"  class="mui-btn-primary remind-button">去找人</button>
										</a>
									</div>
								</div>
                            </li>
                    <?endif?>
        </ul>
		<div style="height:12px"></div>
    </div>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.indexedlist.js')?>
    <script type="text/javascript" charset="utf-8">
        mui.init();
		//document.getElementById("user-nickname").setAttribute("placeholder","请输入昵称、关键词找人");
			add();
		function add(){
			var guanz = $('.guanz');
			var icon10 = $('<img>').attr('src','/web/assets/mui/images/icon10.png');
			
			
			guanz.prepend(icon10); 
			
			var yiguanz = $('.yiguanz');
			var icon14 = $('<img>').attr('src','/web/assets/mui/images/icon14.png');
			yiguanz.prepend(icon14); 

		}
	</script>
	
	
		<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs1.php'); ///引入微信分享1
    ?> 
    <!--End 结束分享功能-->
</body>

</html>
