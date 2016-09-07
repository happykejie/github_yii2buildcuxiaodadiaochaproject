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
	<style type="text/css">
		
	</style>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=lmZLZ77R2a7dDznD114r5g813rXWhUSY"></script>
	<title>启动页</title>

      <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>

    <style>

        #contant{
            background-image:url('/web/images/appstartpage2.jpg');
            background-repeat:no-repeat;
            width:100%;
            height:100%;

        }


        #mainbody
        {
            width:100%;
            height:100%;
        }

        .mui-slider .mui-slider-group .mui-slider-item img
        {
            height:100%;
        }
    </style>

</head>
<body>


     <!--轮播-->
        <div id="slider" class="mui-slider">
            <div class="mui-slider-group mui-slider-loop">
                <!-- 额外增加的一个节点(循环轮播：第一个节点是最后一张轮播) -->

                <?php 
                $lastimgpath ='';
                
                ?>

                <div class="mui-slider-item mui-slider-item-duplicate">
                    <a id="lasts" href="#">
                        <img src="/../<?=  $lastimgpath?>">

                    </a>
                </div>

                <?php 
                $count =0;
                $firstimgpath ='';
                $hrefurl='#';
                ?>
                 <?php if(count($banner)>0):?>
                
                <?php foreach($banner as $b):?>

                <?php 
                if($count==0)
                {
                    $firstimgpath =$b->bannerimgpath;
                }
                
                if($count==2)
                {
                    $lastimgpath =$b->bannerimgpath;
                    $hrefurl ='cuxiaoindex';
                    
                }
                
                
                
                
                
                $count++;
                
                
                
                
                
                    
                
                ?>

                <div class="mui-slider-item">
                    <a href="<?=$hrefurl?>">
                        <img src="/../<?=$b->bannerimgpath;?>">
                    </a>
                </div>

                
                
                <?endforeach?>
                <?endif?>
               
               

                
              
                <!-- 额外增加的一个节点(循环轮播：最后一个节点是第一张轮播) -->
				<div class="mui-slider-item mui-slider-item-duplicate">
                    <a  id="fists" href="#">
                        <img src="/../<?=$firstimgpath?>">
                    </a>
                </div>
            </div>
            <div id="indicator"  class="mui-slider-indicator">
              <!--  <div class="mui-indicator mui-active"></div>-->
              <!--  <div class="mui-indicator"></div>
                <div class="mui-indicator"></div>
                <div class="mui-indicator"></div>
                <div class="mui-indicator"></div>-->
            </div>
        </div>


    
    
    
 
</body>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
	<?=Html::jsFile('@web/web/Js/jquery.js')?>
</html>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>


<script type="text/javascript">
    mui.init({
        swipeBack: true, //
        tap: true
    });
    /*轮播播放*/
    var gallery = mui('.mui-slider');
    gallery.slider({
        interval: 4000//自动轮播周期，若为0则不自动播放，默认为0；
    });
    /*轮播播放*/

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
        $('#segmentedControl .mui-control-item').removeClass('mui-active')
        $($('#segmentedControl .mui-control-item')[indextmp]).addClass('mui-active');
        document.getElementById('segmentedControl').style.display = 'block';
        document.getElementById('tab1').style.display = 'none';
    })

    mui('#segmentedControl').on('tap', '.mui-control-item', function () {
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
    }
    /*获取轮播长度 */
    item();
    function item() {
        var lunlength = $('.lun').length;
        for (var i = 0 ; i < lunlength ; i++) {
            $('.lun')[i];
        }
        //额外增加的一个节点(循环轮播：第一个节点是最后一张轮播) 		
        var a = $($('.lun')[0]).children().attr('href');
        var img = $($('.lun')[0]).children().children().attr('src');
        $('#fists').attr('href', a);
        $('#fists').children().attr('src', img)
        //额外增加的一个节点(循环轮播：最后一个节点是第一张轮播) 
        var lastsa = ($($('.lun')[lunlength - 1])).children().attr('href');
        var lastsimg = ($($('.lun')[lunlength - 1])).children().children().attr('src');
        $('#lasts').attr('href', lastsa);
        $('#lasts').children().attr('src', lastsimg);
    }

    war();
    function war() {
        var lunlength = $('.lun').length;
        str = " ";
        for (var k = 0 ; k < lunlength - 1; k++) {
            str += "<div class=\"mui-indicator\"></div>";
        }
        $('#indicator').append(str);
    }
    /**/
        </script>




<script>
    $(function () {
        //图片数组
        var sArr = ['/web/images/appstartpage1.jpg', '/web/images/appstartpage2.jpg', '/web/images/appstartpage3.jpg'];
        //定时更换背景
        setInterval(function () {
            $("#contant").css("backgroundImage", "url(" + sArr[fRandomBy(0, 2)] + ")");
        }, 5000); //单位毫秒
        //设定随机数的范围
        function fRandomBy(under, over) {
            switch (arguments.length) {
                case 1: return parseInt(Math.random() * under + 1);
                case 2: return parseInt(Math.random() * (over - under + 1) + under);
                default: return 0;
            }
        }
    })


    function onclickenter() {
     window.location.href = '/cxddc/cuxiao/index';
    }


</script>


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
                data: {'lng': r.point.lng, 'lat': r.point.lat },
                dataType: "text",
                success: function (data) {

                   // alert('你所在城市:'+data);

                    $("#enter").css('display', 'block');

                  //  window.location.href = '/cxddc/cuxiao/index';

                },
                error: function (xhr, errorType, error) {
                    alert('不能定位到你所在城市');
                   // window.location.href = '/cxddc/cuxiao/loadad';

                }
            });
        }
        else {
            alert('failed' + this.getStatus());
        }
    }, { enableHighAccuracy: true })
 
</script>







