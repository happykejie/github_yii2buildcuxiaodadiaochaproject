<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>城市选择插件demo</title>
  

            <?=Html::cssFile('@web/web/assets/citypicker/css/cityPicker.css')?>
</head>
<body>

<div style="margin-left:20px !important">

 <input type="text" id="cityChoice"  style="width:80px;" value="成都市 " />
<input type="hidden" id="province" value="">
<input type="hidden" id="city" value="">

</div>
    <?=Html::jsFile('@web/web/assets/citypicker/js/jquery-2.1.3.min.js')?>
    <?=Html::jsFile('@web/web/assets/citypicker/js/cityData.js')?>
    <?=Html::jsFile('@web/web/assets/citypicker/js/cityPicker.js')?>

<script>
    var cityPicker = new IIInsomniaCityPicker({
        data: cityData,
        target: '#cityChoice',
        valType: 'k-v',
        hideCityInput: '#city',
        hideProvinceInput: '#province',
        callback: function (city_id) {
            alert(city_id);
        }
    });

    cityPicker.init();
</script>
</body>
</html>