
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
<html lang="en" class="feedback">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>问题反馈</title>
		  <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/Becomeuser.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/feedback.css')?>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<button id="submit" class="mui-btn mui-btn-blue mui-btn-link mui-pull-right">发送</button>
			<h1 class="mui-title">问题反馈</h1>
		</header>
		<div class="mui-content">
			<div class="mui-content-padded">
				<div class="mui-inline">问题和意见</div>
				<a class="mui-pull-right mui-inline" href="#popover">
					快捷输入
					<span class="mui-icon mui-icon-arrowdown"></span>
				</a>
				<!--快捷输入具体内容，开发者可自己替换常用语-->
				<div id="popover" class="mui-popover">
					<div class="mui-popover-arrow"></div>
					<div class="mui-scroll-wrapper">
						<div class="mui-scroll">
							<ul class="mui-table-view">
								<!--仅流应用环境下显示-->
								<li class="mui-table-view-cell stream">
									<a href="#">桌面快捷方式创建失败</a>
								</li>
								<li class="mui-table-view-cell"><a href="#">界面显示错乱</a></li>
								<li class="mui-table-view-cell"><a href="#">启动缓慢，卡出翔了</a></li>
								<li class="mui-table-view-cell"><a href="#">偶发性崩溃</a></li>
								<li class="mui-table-view-cell"><a href="#">UI无法直视，丑哭了</a></li>
							</ul>
						</div>
					</div>

				</div>
			</div>
			<div class="row mui-input-row">
				<textarea id='question' class="mui-input-clear question" placeholder="请详细描述你的问题和意见..."></textarea>
			</div>
			<p>图片(选填,提供问题截图,总大小10M以下)</p>
			<div id='image-list' class="row image-list"></div>
			<p>QQ/邮箱</p>
			<div class="mui-input-row">
				<input id='contact' type="text" class="mui-input-clear contact" placeholder="(选填,方便我们联系你 )" />
			</div>
			<div class="mui-content-padded">
				<div class="mui-inline">应用评分</div>
				<div class="icons mui-inline" style="margin-left: 6px;">
					<i data-index="1" class="mui-icon mui-icon-star"></i>
					<i data-index="2" class="mui-icon mui-icon-star"></i>
					<i data-index="3" class="mui-icon mui-icon-star"></i>
					<i data-index="4" class="mui-icon mui-icon-star"></i>
					<i data-index="5" class="mui-icon mui-icon-star"></i>
				</div>
			</div><br />
		</div>
		 <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <?=Html::jsFile('@web/web/assets/mui/js/feedback.js')?>
		<script type="text/javascript">
		    mui.init();
		    mui('.mui-scroll-wrapper212').scroll();
		</script>

    <input type="hidden" value="<?= $currentuserid?>" id="userid"/>
      	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxzhuyejs.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->

	</body>

</html>