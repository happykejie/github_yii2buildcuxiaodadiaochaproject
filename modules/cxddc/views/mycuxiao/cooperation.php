
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
		<title>平台合作</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		   <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
     <?=Html::cssFile('@web/web/assets/cxddc/css/my.css')?>

		<style>
			p {
				text-indent: 22px;
				padding: 5px 8px;
			}
			
			html,
			body,
			.mui-content {
				background-color: #fff;
			}
			
			h4 {
				margin-left: 5px;
			}
			
			.mui-plus header.mui-bar {
				display: none;
			}
			
			.mui-plus .mui-bar-nav~.mui-content {
				padding: 0;
			}
			
			.qrcode {
				/*position: absolute;*/
				top: 50px;
				/*left: 50%;*/
				width: 100%;
				/*-webkit-transform: translate(-50%, 0);
				transform: translate(-50%, 0);*/
				text-align: center;
			}
			
			.qrcode img {
				margin: 0 auto;
			}
			
		</style>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">平台合作</h1>
		</header>
		<div class="mui-content">
			<div class="mui-content-padded">

               <div style="text-align:left;color:#23ac38"> 平台简介：</div>
               <div style="padding-left:20px;text-align:left"><p> 促销大调查是阿欢阿杰科技自主开发和运营的全国首家促销信息平台。全国最大的促销信息分享平台。阿欢阿杰科技专注微信领域开发，提供微信运营解决方案。</p></div>
            </div>
               
				<!--<h4 style="margin-top:10px;">mui</h4>-->

                <div style="text-align:center;">
                <ul class="mui-table-view">
					<li class="mui-table-view-cell">
						公司名称:<span id="channel"><a href="http://www.ahuanajie.com">阿欢阿杰科技</a></span>
					</li>
					<li class="mui-table-view-cell">
						公司电话: <span id="bill_no">028-86090169</span>
					</li>

                    <li class="mui-table-view-cell">
						手机(微信): <span id="bill_no">徐总：18980946169</span>
					</li>
					
					

                    <li class="mui-table-view-cell">
						手机(微信): <span id="total_fee">张先生：15198029360</span>
					</li>
				
				</ul>
                    </div>

				
				<p style="text-align: center;color:gray;text-indent: 0;">当前版本：<span id="version">1.1.0</span></p>	
				<p style="text-align: center;color: gray;text-indent: 0;">版权所有：成都阿欢阿杰科技有限公司</span></p>	

			</div>
		</div>
		  <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
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


          <input type="hidden" value="<?= $currentuserid?>" id="userid"/>
      	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxzhuyejs.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->

	</body>

</html>