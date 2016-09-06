
<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

?>

<!doctype html>
<html>

	<head>
		<meta charset="utf-8">
		<title>促销信息详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<!--标准mui.css-->
		  <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
		<!--App自定义的css-->
		<style type="text/css">
			.mui-preview-image.mui-fullscreen {
				position: fixed;
				z-index: 20;
				background-color: #000;
			}
			.mui-preview-header,
			.mui-preview-footer {
				position: absolute;
				width: 100%;
				left: 0;
				z-index: 10;
			}
			.mui-preview-header {
				height: 44px;
				top: 0;
			}
			.mui-preview-footer {
				height: 50px;
				bottom: 0px;
			}
			.mui-preview-header .mui-preview-indicator {
				display: block;
				line-height: 25px;
				color: #fff;
				text-align: center;
				margin: 15px auto 4;
				width: 70px;
				background-color: rgba(0, 0, 0, 0.4);
				border-radius: 12px;
				font-size: 16px;
			}
			.mui-preview-image {
				display: none;
				-webkit-animation-duration: 0.5s;
				animation-duration: 0.5s;
				-webkit-animation-fill-mode: both;
				animation-fill-mode: both;
			}
			.mui-preview-image.mui-preview-in {
				-webkit-animation-name: fadeIn;
				animation-name: fadeIn;
			}
			.mui-preview-image.mui-preview-out {
				background: none;
				-webkit-animation-name: fadeOut;
				animation-name: fadeOut;
			}
			.mui-preview-image.mui-preview-out .mui-preview-header,
			.mui-preview-image.mui-preview-out .mui-preview-footer {
				display: none;
			}
			.mui-zoom-scroller {
				position: absolute;
				display: -webkit-box;
				display: -webkit-flex;
				display: flex;
				-webkit-box-align: center;
				-webkit-align-items: center;
				align-items: center;
				-webkit-box-pack: center;
				-webkit-justify-content: center;
				justify-content: center;
				left: 0;
				right: 0;
				bottom: 0;
				top: 0;
				width: 100%;
				height: 100%;
				margin: 0;
				-webkit-backface-visibility: hidden;
			}
			.mui-zoom {
				-webkit-transform-style: preserve-3d;
				transform-style: preserve-3d;
			}
			.mui-slider .mui-slider-group .mui-slider-item img {
				width: auto;
				height: auto;
				max-width: 100%;
				max-height: 100%;
			}
			.mui-android-4-1 .mui-slider .mui-slider-group .mui-slider-item img {
				width: 100%;
			}
			.mui-android-4-1 .mui-slider.mui-preview-image .mui-slider-group .mui-slider-item {
				display: inline-table;
			}
			.mui-android-4-1 .mui-slider.mui-preview-image .mui-zoom-scroller img {
				display: table-cell;
				vertical-align: middle;
			}
			.mui-preview-loading {
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				display: none;
			}
			.mui-preview-loading.mui-active {
				display: block;
			}
			.mui-preview-loading .mui-spinner-white {
				position: absolute;
				top: 50%;
				left: 50%;
				margin-left: -25px;
				margin-top: -25px;
				height: 50px;
				width: 50px;
			}
			.mui-preview-image img.mui-transitioning {
				-webkit-transition: -webkit-transform 0.5s ease, opacity 0.5s ease;
				transition: transform 0.5s ease, opacity 0.5s ease;
			}
			@-webkit-keyframes fadeIn {
				0% {
					opacity: 0;
				}
				100% {
					opacity: 1;
				}
			}
			@keyframes fadeIn {
				0% {
					opacity: 0;
				}
				100% {
					opacity: 1;
				}
			}
			@-webkit-keyframes fadeOut {
				0% {
					opacity: 1;
				}
				100% {
					opacity: 0;
				}
			}
			@keyframes fadeOut {
				0% {
					opacity: 1;
				}
				100% {
					opacity: 0;
				}
			}
			p img {
				max-width: 100%;
				height: auto;
			}

		    #headtitle {
                font-size:1.5em;
                color:red;
                margin-top:25px;
                height:50px;
                line-height:30px;
               
                text-align:center;
           
		    }

            p.showlable{
                font-size:16px;
                color:green;
                margin-left:20px;

            }

            p.showcontent{
                 font-size:16px;
                color:gray;
                margin-left:20px;
            }

            #acdescription{
                 font-size:16px;
                color:gray;
                margin-left:20px;
            }
            
            #cxxc
            {
                  font-size:24px;
                color:green;
                margin-left:20px;

                margin-top:10px;
                margin-bottom:20px;
            }


               .detailimg 
            {
                max-height:400px;
                width:100%;
                margin-top:10px;
                border:10px solid inset;
                text-align:center;
             
            }

            .detailimg img
            {
                max-height:400px;
                width:90%;
                margin-top:10px;
                border:10px solid inset;
                text-align:center;
            

            }

            .emptydiv
            {
                height:150px;
                width:100%;
                 text-align:center;
                font-size:30px;
                color:red;
                font-family:Vijaya;
            }

            #divdetailimg
            {
                margin-bottom:20px;

               
            }


            #headimg img{
                 max-height:200px;
                width:100%;
                margin-top:10px;
                border:10px solid inset;
                text-align:center;

            }

              #headimg {
                 max-height:200px;
                width:100%;
                margin-top:10px;
                border:10px solid inset;
                text-align:center;
                margin-bottom:20px;

            }

		  
		</style>

	</head>

	<body>
		
		<div class="mui-content">
			<div class="mui-content-padded">

            <!--    <div id="bgheadimg" style="height:200px;width:100%;">

                </div>-->

                <div id="headtitle">
                    <?=$item->name?>
                </div>

			     <div id="headimg">
					<img src="<?=$item->surface?>" data-preview-src="" data-preview-group="1" />
				</div>
               <p class="showlable">开始时间:</p>
            <p class="showcontent">  <?=$item->start_time?></p>  
                <p class="showlable">结束时间:</p>
              
                <p class="showcontent">  <?=$item->end_time?> </p> 
                
              <p class="showlable">地址:</p>

              
                <p class="showcontent">  <?=$item->address?></p> 

               <p class="showlable">咨询方式:</p>
               
              
                <p class="showcontent">  <?=$item->contacttype?> </p> 
               
                <p class="showlable">活动介绍:</p>

                <div id="acdescription">   <p class="showcontent"> <?=$item->intro?></p> </div>
             
				

                <div id="cxxc">
                   促销现场
                </div>

               <div id="divdetailimg">


               

                   <?php if(count($arryimg)>0):?>
                <?php foreach($arryimg as $m):?>
                <?php if(strlen($m)>5):?>

                <div class="detailimg">
                      <img src="<?=$m?>" data-preview-src="" data-preview-group="1" />
                </div>

                <?endif?>
                <?endforeach?>
                <?endif?>


                   </div>

                
               
			</div>
              
            
		</div>
          
        <div class ="emptydiv">

            点击右上角分享
            </br></br>
             <p>版权所有：成都阿欢阿杰科技有限公司</p>
        </div>
       
                 
	</body>
       <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
       <?=Html::jsFile('@web/web/assets/mui/js/mui.zoom.js')?>
       <?=Html::jsFile('@web/web/assets/mui/js/mui.previewimage.js')?>

     <?=Html::jsFile('@web/web/js/jquery.js')?>

	<script>
	    mui.previewImage();

	  

	</script>

    <script>

    
 
       

    </script>

</html>