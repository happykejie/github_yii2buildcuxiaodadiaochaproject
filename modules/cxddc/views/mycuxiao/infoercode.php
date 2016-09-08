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
    <title>二维码</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!--标准mui.css-->
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
      <?=Html::cssFile('@web/web/assets/mui/css/css/info.css')?>
    <!--App自定义的css-->
    <link rel="stylesheet" type="text/css" href="../css/app.css" />
</head>

<body>

    <div class="mui-content infon" style="position: relative;">
        <div class="infon-cont">
            <div class="infon-h4">
                <h4>请关注促销大调查的官方微信</h4>
            </div>
            <div class="oa-contact-avatar ">
            </div>
            <h4 class="infon-text infon-text-h4" style="text-align:left"><span style="color:red;font-size:1em;">第一步：长按住二维码3秒以上出现弹层</span></h4>
            </br>
            <h4 class="infon-text infon-text-h4" style="text-align:left"><span style="color:red;font-size:1em;">第二步：在弹层中选择"识别图中的二维码"</span></h4>
                </br>
            <h4 class="infon-text infon-text-h4" style="text-align:left"><span style="color:red;font-size:1em;">第三步：点击下方的关注按钮关注</span></h4>
            </br>

            <p class="infon-text iofon-p">点击右上角分享二维码给朋友</p>
			
            <div class="mui-content-padded">

                <div class="qrcode">
                    <div id="qrcode"  width="70%" />

                     <img  src="/web/images/cxddcqrcode.jpg" width="80%">

                </div>
                    <a id="shortcut" style="width: 60%; margin: 15px auto; padding: 5px;" class="mui-hidden mui-btn mui-btn-block mui-btn-red">创建桌面图标</a>
                </div>
                <h5 class="infon-footer">赶紧加入，获取最新促销咨询</h5>
            </div>
        </div>

  <input type="text" class="input" id="mytxt" value="" style="display:none;"> 
<!--    <input id="s" type="type" name="name" value="" />-->
    <script type="text/javascript" charset="utf-8">
        mui.init({
            gestureConfig: {
                longtap: true
            },
            swipeBack: true //启用右滑关闭功能
        });
        //处理点击事件，需要打开原生浏览器
        mui('body').on('tap', 'a', function (e) {
            var href = this.getAttribute('href');
            if (href) {
                if (window.plus) {
                    plus.runtime.openURL(href);
                } else {
                    location.href = href;
                }
            }
        });

        var qrcodeEl = document.getElementById("qrcode");
        qrcodeEl.addEventListener('longtap', function () {
            plus.nativeUI.actionSheet({
                cancel: '取消',
                buttons: [{
                    title: '保存到相册'
                }]
            }, function (e) {
                var index = e.index;
                if (e.index === 1) {
                    plus.gallery.save(qrcodeEl.src, function () {
                        mui.toast('保存成功');
                    }, function () {
                        mui.toast('保存失败，请重试！');
                    });
                }
            });
        });
        if (mui.os.android && mui.os.stream) { //创建快捷方式
            var shortcutElem = document.getElementById("shortcut");
            shortcutElem.classList.remove('mui-hidden');
            shortcutElem.addEventListener('tap', function () {
                plus.navigator.createShortcut({
                    name: "hello-mui",
                    icon: "images/logo.png"
                });
            });
        }
    </script>
    
<!--<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.qrcode.min.js"></script>-->
   <?=Html::jsFile('@web/web/assets/js/common/jquery.js')?>
 
   <?=Html::jsFile('@web/web/assets/js/common/jquery.qrcode.min.js')?>


   
      <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>

  <input type="hidden" value="<?= $currentuserid?>" id="userid"/>
      	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxzhuyejs.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->

</body>

</html>