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
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>找老师</title>
  
    <?=Html::cssFile('@web/web/assets/mui/css/mui.indexedlist.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/zhaoren.css')?>
    <style>
	</style>
</head>
<body>
    <div class="mui-content" style="overflow-y: auto;  position: relative;">
        <div class="mui-indexed-list-search mui-input-row mui-search">
            <!--<input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="输入名称">-->
            <div id="look-header" class="row">
                <?php $form=ActiveForm::begin(['id'=>'search','enableAjaxValidation'=>false]); ?>
                <div class="col-md-8">
                    <?=$form->field($search,'nickname')->textinput(['placeholder'=>'请输入学校、专业、领域等关键词找人']);?>
                </div>
                <div class="col-md-1" style="position: relative;">
                    <?=Html::submitButton(' ',['class'=>'btn btn-primary mui-input-clear mui-indexed-list-search-input mui-icon mui-icon-search'])?>
                </div>
                <?php ActiveForm::end()?>
            </div>
        </div>

        <div id="s" style="padding: 10px 10px; background-color: #FFFFFF; position: relative;">
            <div id="segmentedControl" class="segmented-control segmented-control-inverted segmented-control-primary">

                <a class="mui-control-item look-a mui-active" aindex='1' href="#item1">全部
                </a>

                <?php if(count($category)>0):?>
                <?php foreach($category as $v):?>

                <a class="mui-control-item look-a" aindex='<?=$v->id+1?>' href="#item<?=$v->id+1?>"><?=$v->categoryname;?> </a>

                <?endforeach?>
                <?endif?>
                <a class="doctor-index-a" onclick="segmentedControl()"></a>
            </div>
        </div>

        <div id="tab1" class="mui-slider" style="position: absolute; left: 0; top: 68px; width: 100%; height: 100%; z-index: 999; display: none;">
            <h4 class="doctor-text" onclick="tab1()">切换频道</h4>
            <ul class=" mui-segmented-control mui-segmented-control-inverted mui-segmented-control-primary mui-table-view mui-grid-view mui-grid-9">
                <li class="active mui-table-view-cell mui-media   mui-col-xs-3 mui-col-sm-3">
                    <a aindex='1' href="#item1" class="mui-control-item  tab-a">全部</a>
                </li>
                <?php if(count($category)>0):?>
                <?php foreach($category as $v):?>
                <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                    <a aIndex='<?=$v->id+1?>'  href="#item<?=$v->id+1?>" class="mui-control-item  tab-a"><?=$v->categoryname;?></a>
                </li>
                <?endforeach?>
                <?endif?>

            </ul>
        </div>
        <!--全部-->
        <div id="item1" class="mui-control-content mui-active">
            <ul  class="mui-table-view mui-table-view-striped mui-table-view-condensed">
                <?php if(count($items)>0):?>
                <?php foreach($items as $v):?>
                <li class="mui-table-view-cell">
                    <div class="mui-slider-cell">
                        <a class="lookforpeople-a" href="/wenda/lookforpeople/expert?id=<?=$v->id?>">
                            <div class="oa-contact-cell mui-table">
                                <div class="oa-contact-avatar mui-table-cell">
                                    <img src="<?=$v->headimgurl?>" />
                                </div>
                                <div class="oa-contact-content mui-table-cell ">
                                    <div class="mui-clearfix">
                                        <h4 class="oa-contact-name"><?=$v->nickname?></h4>

                                        <h6 class="oa-contact-position look-h6"><?=$v->title?></h6>
                                    </div>
                                    <p class="oa-contact-email mui-h6" style="display: inline-block; color: #999999;">
                                        <?=$v->askproblemnumber()->count()?>个回答,<?=$v->attentionnumber()->count()?>个人关注
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
                <?else:?>
                <?endif?>
            </ul>
        </div>
   

    <?php if(count($category)>0):?>
    <?php foreach($category as $c):?>
    <div id="item<?=$c->id+1?>" class="mui-control-content">
        <ul class="wenda-ul mui-table-view mui-table-view-striped mui-table-view-condensed">
            <?php if(count($items)>0):?>
            <?php foreach($items as $v):?>
            <?php  $num=explode($c->categoryname,$v->label);
                   if(count($num)>0):?>
            <li class="mui-table-view-cell">
                <div class="mui-slider-cell">
                    <a class="lookforpeople-a" href="/wenda/lookforpeople/expert?id=<?=$v->id?>">
                        <div class="oa-contact-cell mui-table">
                            <div class="oa-contact-avatar mui-table-cell">
                                <img src="<?=$v->headimgurl?>" />
                            </div>
                            <div class="oa-contact-content mui-table-cell ">
                                <div class="mui-clearfix">
                                    <h4 class="oa-contact-name"><?=$v->nickname?></h4>

                                    <h6 class="oa-contact-position look-h6"><?=$v->title?></h6>
                                </div>
                                <p class="oa-contact-email mui-h6" style="display: inline-block; color: #999999;">
                                    <?=$v->askproblemnumber()->count()?>个回答,<?=$v->attentionnumber()->count()?>个人关注
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
        <?endif?>
       
        <?endforeach?>
        <?endif?>
          </ul>
    </div>
     <?endforeach?>
     <?endif?>

    <div style="height: 12px"></div>
         </div>
    <div style="height: 1px"></div>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>


    <script type="text/javascript" charset="utf-8">
        mui.init({
            gestureConfig: {
                tap: true, //默认为true
                doubletap: true, //默认为false
                longtap: true, //默认为false
                swipe: true, //默认为true
                drag: true, //默认为true
                hold: false, //默认为false，不监听
                release: false //默认为false，不监听
            }
        })
        /*添加心形图片*/
        add();
        function add() {
            var guanz = $('.guanz');
            var icon10 = $('<img>').attr('src', '/web/assets/mui/images/icon10.png');
            var span = $('<span>').html('fdf')
            guanz.prepend(icon10);
            var yiguanz = $('.yiguanz');
            var icon14 = $('<img>').attr('src', '/web/assets/mui/images/icon14.png');
            yiguanz.prepend(icon14);
        }
        /*添加心形图片*/
        function segmentedControl() {
            document.getElementById('tab1').style.display = 'block';
        }
        function tab1() {
            document.getElementById('tab1').style.display = 'none';
        }
        /*变量*/
        var indextmp = null;

        mui("#tab1").on('tap', 'a', function () {
            indextmp = $(this).attr('aIndex') - 1;
            $(this).parent().siblings().removeClass('active');
            $(this).parent().addClass('active');
            $('#segmentedControl .look-a').removeClass('mui-active')
            $($('#segmentedControl .look-a')[indextmp]).addClass('mui-active');
            document.getElementById('segmentedControl').style.display = 'block';
            document.getElementById('tab1').style.display = 'none';
        })

        mui('#segmentedControl').on('tap', '.look-a', function () {
            indextmp = $(this).attr('aIndex') - 1;
            $(this).siblings().removeClass('mui-active');
            $(this).addClass('mui-active');
            $('#tab1 .tab-a').parent().siblings().removeClass('active');
            $($('#tab1 .tab-a')[indextmp]).parent().addClass('active')
        })
        /**/
        $('#tab1').click(function () {

            document.getElementById('tab1').style.display = 'none';
        })

        $('#look-header').click(function () {

            document.getElementById('tab1').style.display = 'none';
        })

        navnone();
        function navnone() {
            var nanlenght = $('.mui-control-item').length;
            for (j = 1 ; j < nanlenght; j++) {
                $('.mui-control-item')[j];
            }

            $($('.mui-control-item')[5]).css('display', 'none');
            $($('.mui-control-item')[6]).css('display', 'none');
            $($('.mui-control-item')[7]).css('display', 'none');
            $($('.mui-control-item')[8]).css('display', 'none');
            $($('.mui-control-item')[9]).css('display', 'none');
            $($('.mui-control-item')[10]).css('display', 'none');
            //  $('.index-nav:last').css('display','none');
        }
    </script>

    <!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs1.php'); ///引入微信分享1
    ?> 
    <!--End 结束分享功能-->
</body>
</html>
