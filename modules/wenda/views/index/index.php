<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>他她好孕</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--标准mui.css-->
    <link rel="stylesheet" href="css/mui.min.css">
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.indexedlist.css')?>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/wenda/css/wenda.css')?>
    <?=Html::cssFile('@web/web/assets/wenda/css/zhaoren.css')?>
    <?=Html::cssFile('@web/web/assets/wenda/css/my.css')?>
</head>

<body>
    <nav class="mui-bar footer mui-bar-tab">
        <a id="defaultTab" class="mui-tab-item mui-active" href="<?=Yii::$app->urlManager->createUrl('wenda/wenda/index')?>"">问答
		    </a>
        <a class="mui-tab-item" href="#tabbar-with-chat">找人
		    </a>
        <a class="mui-tab-item" href="#tabbar-with-contact">我		    
		    </a>

    </nav>

    <div class="mui-content">
    </div>
</body>


<?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
<script>
    mui.init({
        swipeBack: true //启用右滑关闭功能
    });

	</script>
</html>
