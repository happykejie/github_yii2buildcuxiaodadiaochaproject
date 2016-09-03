<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title><?php  echo Wx_Title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--标准mui.css-->
   
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.indexedlist.css')?>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>

    <?=Html::jsFile('@web/web/js/jquery.js')?>

</head>
    <style>
        #nav.mui-bar-tab  .mui-tab-item.mui-active{color:#7ad9d3;}
		#nav.mui-bar-tab {background-color: #ffffff;}
    </style>
<body>

    <?php echo $content; ?>

    <nav id="nav" class="mui-bar footer mui-bar-tab" style="bottom:0px;z-index:9999;border:none;">
        <a id="defaultTab" class="mui-tab-item mui-active">首页</a>
        <a id="defaultTab2" class="mui-tab-item">发现</a>
        <a id="defaultTab3" class="mui-tab-item" href="#tabbar-with-contact">我</a>
    </nav>

</body>



<script>
    mui.init({
        swipeBack: true //启用右滑关闭功能
    });
	window.onload = function(){
		var tabNum = sessionStorage.getItem("tabNum");
		var tab1 = $("#defaultTab");
		var tab2 = $("#defaultTab2");
		var tab3 = $("#defaultTab3");
		var tabNav = $("#nav");
		switch (tabNum){
			case "null":
			case "1":
			tab2.removeClass("mui-active");
			tab3.removeClass("mui-active");
			tab1.addClass("mui-active");
			tabNav.css("border","none")
			break;
			case "2":
			tab1.removeClass("mui-active");
			tab3.removeClass("mui-active");
			tab2.addClass("mui-active");
			tabNav.css("border","1px solid #ccc")
			break;
			case "3":
			tab1.removeClass("mui-active");
			tab2.removeClass("mui-active");
			tab3.addClass("mui-active");
			tabNav.css("border","none")
			break;
		}
	}
    mui("#nav").on("tap", "#defaultTab", function () {
        window.location = "/wenda/cuxiao/cuxiaoindex";
		sessionStorage.setItem("tabNum","1")
    })
    mui("#nav").on("tap", "#defaultTab2", function () {
        window.location = "/wenda/lookforcuxiao/index";
		sessionStorage.setItem("tabNum","2")
    })
    mui("#nav").on("tap", "#defaultTab3", function () {
        window.location = "/wenda/mycuxiao/index";
		sessionStorage.setItem("tabNum","3")
    })
</script>

 

</html>
