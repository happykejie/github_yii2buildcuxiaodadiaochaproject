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
    <title></title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />

    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/search.css')?>


</head>
<body>

    <div class="mui-content" style=" position: relative;">

        <div class="mui-input-row  " style="padding: 10px">
            <a href="/wenda/wenda/index"><span class="mui-icon mui-icon-closeempty span-rmv mui-pull-left"></span></a>
            
           

            <?php $form=ActiveForm::begin(['id'=>'search','enableAjaxValidation'=>false]); ?>

            <div class="col-md-2">
                <?=$form->field($search,'nickname')->textinput(['placeholder'=>'请输入昵称、关键词找人']);?>
            </div>

            <div class="col-md-1">
                <?=Html::submitButton('搜索',['class'=>'shou mui-btn mui-btn-primary','id'=>'sub'])?>
            </div>
            <?php ActiveForm::end()?>
        </div>
        <!--答主-->
        <div class="">
		 <?php if(count($user)>0):?>
            <p class="sousuo-p" >答主</p>
            <ul id="zhao-ul" class="mui-table-view mui-table-view-striped mui-table-view-condensed">
               
                <?php foreach($user as $v):?>
                          <li id="zhao-ul-li" class="mui-table-view-cell">
                    <a class="lookforpeople-a" href="/wenda/lookforpeople/expert?id=<?=$v->id?>">
                        <div class="mui-slider-cell">
                            <div class="oa-contact-cell mui-table">
                                <div class="oa-contact-avatar mui-table-cell">
                                    <img src=" <?=$v->headimgurl?>" width="60px" height="60px" />
                                </div>
                                <div class="oa-contact-content mui-table-cell">
                                    <div class="mui-clearfix" >
                                        <h4 class="oa-contact-name"><?=$v->nickname?></h4>
                                        
                                        <h6 class="oa-contact-position look-h6"><?=$v->title?></h6>
                                    </div>
                                    <p class="oa-contact-email mui-h6">
                                        <?=$v->askproblemnumber()->count()?>个回答,<?=$v->attentionnumber()->count()?>个人关注
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <?endforeach?>
                <?else:?>
               
                <?endif?>
            </ul>
        </div>
        <div class="went">
		 <?php if(count($askproblem)>0):?>
            <p class="sousuo-p" >问题</p>
            <ul class="mui-table-view">
               
                <?php foreach($askproblem as $v):?>
                <li class="mui-table-view-cell mui-media">
					<a href="/wenda/wenda/paywenda?id=<?=$v->id?>">
                        <div class="mui-media-body">
                            <?=$v->questiondescription?>?
                            <p class="mui-ellipsis">答主:<?php echo $v->getUseranswer()->nickname?></p>
                        </div>
                    </a>

                </li>

                <?endforeach?>
                <?else:?>
              
                <?endif?>
            </ul>
        </div>
    </div>
        <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript">
        mui.init({
            tap: true
        });
        (function ($) {

            $("#daz-a").on("tap", "a", function () {
                var went = document.getElementById("went")
                went.style.display = "none";
            })
            $("#wen-a").on("tap", "a", function () {
                var daz = document.getElementById("daz")
                daz.style.display = "none";
            })
        })(mui);
		//document.getElementById("search-nickname").setAttribute("placeholder","请输入昵称、关键词找人");
		//search();
		function search(){
			var img = $('<img>').attr('src','/web/assets/mui/images/icon7.png');
			var sub = $('#sub');
			 sub.prepend(img);
		}
		
		</script>
	    <!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxjs1.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->
</body>

</html>
