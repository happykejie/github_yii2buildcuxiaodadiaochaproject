<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>促销大调查</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--标准mui.css-->
    <link rel="stylesheet" href="css/mui.min.css">
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.indexedlist.css')?>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/cxddc/css/cxddc.css')?>
    <?=Html::cssFile('@web/web/assets/cxddc/css/zhaoren.css')?>
    <?=Html::cssFile('@web/web/assets/cxddc/css/my.css')?>
</head>

<body>
    <nav class="mui-bar footer mui-bar-tab">
       
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
