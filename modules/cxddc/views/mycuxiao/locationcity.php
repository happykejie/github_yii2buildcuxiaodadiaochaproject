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


<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=lmZLZ77R2a7dDznD114r5g813rXWhUSY"></script>
	<title>定位页面</title>

      <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>


     <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>
    <?=Html::cssFile('@web/web/assets/citypicker/css/cityPicker.css')?>

   
    <style>

        .control-label
        {
            display:none;
        }

        .phone-input{ 
padding-right:20px; 
background:url("/web/images/icon6.png") no-repeat scroll right center transparent; 
background-color:red;
} 

    </style>
     

</head>
<body>


  <header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">定位页面</h1>
		</header>
		<div class="mui-content">
			<div class="mui-content-padded" style="margin: 5px;">



                 

                <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">当前设置城市</h4>
					<div class="dianh s">
						<?=  $cityname?>
					</div>
				</div>


                <input class='phone-input' value="test" type="text" id="phone"/> 

              



                 <?php $form=ActiveForm::begin(['id'=>'locationcity','enableAjaxValidation'=>false]); ?>

                <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">定位城市</h4>
					<div class="dianh s">
						<?= $form->field($baiducity,'locationcity')->textinput();?>
					</div>
				</div>
                   <div style="padding:10px;">
                        <?=Html::submitButton('切换到定位城市',['id'=>'sub','class'=>'mui-btn mui-btn-primary mui-btn-block s'])?>
                </div>

                 
                      <?php ActiveForm::end()?>



                 <?php $form=ActiveForm::begin(['id'=>'locationcity','enableAjaxValidation'=>false]); ?>

                <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">选择城市</h4>
					<div class="dianh s">
						<?= $form->field($selectcity,'city')->textinput();?>
					</div>
				</div>
                   <div style="padding:10px;">
                        <?=Html::submitButton('切换到选择城市',['id'=>'sub','class'=>'mui-btn mui-btn-primary mui-btn-block s'])?>
                </div>

                   <?php ActiveForm::end()?>




          
			

         
     
             
               
            
				
			
			</div>
		</div>

    
    
 
</body>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
	<?=Html::jsFile('@web/web/Js/jquery.js')?>
</html>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>


<script type="text/javascript">
    // 百度地图API功能-通过浏览器获取定位信息
    var geolocation = new BMap.Geolocation();
    geolocation.getCurrentPosition(function (r) {
        if (this.getStatus() == BMAP_STATUS_SUCCESS) {
            var mk = new BMap.Marker(r.point);

            // alert('您的位置：' + r.point.lng + ',' + r.point.lat);

            $.ajax({
                url: '/cxddc/cuxiao/getcityname',
                type: 'get',
                data: { 'lng': r.point.lng, 'lat': r.point.lat },
                dataType: "text",
                success: function (data) {

                    $('#user-locationcity').val(data);
                  

                },
                error: function (xhr, errorType, error) {
                    alert('不能定位到你所在城市');
                 

                }
            });
        }
        else {
            alert('failed' + this.getStatus());
        }
    }, { enableHighAccuracy: true })

</script>


    <?=Html::jsFile('@web/web/assets/citypicker/js/cityData.js')?>
    <?=Html::jsFile('@web/web/assets/citypicker/js/cityPicker.js')?>



<script>
    var cityPicker = new IIInsomniaCityPicker({
        data: cityData,
        target: '#user-city',
        valType: 'k-v',
        hideCityInput: '#city',
        hideProvinceInput: '#province',
        callback: function (city_id) {
           // alert(city_id);
        }
    });

    cityPicker.init();
</script>







