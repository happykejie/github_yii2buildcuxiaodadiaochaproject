<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>录音前</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />


      <link rel="stylesheet" href="http://demo.open.weixin.qq.com/jssdk/css/style.css?ts=1420774989">
    <script src="/web/Js/jquery.js"></script>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/wenda/css/wenda.css')?>

	</head>
	<body>
		<div class="mui-content">
			<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
				<li class="mui-table-view-cell">
			
					<div class="mui-slider-cell">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img src="/web/assets/mui/images/header.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix">
									<h4 class="oa-contact-name">叶文洁</h4>
									<span class="recording-span">￥36.00</span>
								</div>
							
							</div>
						</div>
					<h4 class="recording-h4">如何才能有效备孕？在备孕期间应该注意哪些问题？</h4>
					<p>2天前</p>
					</div>
				</li>
			</ul>
			<div class="recording-footer">
			  <div class="mui-checkbox" >
            <p class="tongy" >
                <input name="checkbox" value="Item 2" type="checkbox" checked="">
                提问公开回答，答案每被人爱听1次，你就将活动0.5元
            </p>
        </div>
        	    <div class="footer-cent">
        		<p class="recording-p">按住开始录音，录音时长不超过60秒</p>
        		<div class="recording-button">
        		   		<button  type="button" class="mui-btn-primary recording-ti">
            <img src="/web/assets/mui/images/icon2.png" alt="" />        		</button>
        		   		<button type="button" class="mui-btn-primary recording-ti1">
            重录
        		</button>
        		</div>
        	</div>
        	<div class="footer-botton">
        		<button type="button" class="mui-btn-primary  recording-ti3">
            确认提交
        		</button>
        	</div>
		</div>
        </div>
    
     <?=Html::cssFile('@web/web/assets/mui/js/mui.min.js')?>
		<script type="text/javascript">
		    mui.init()
		</script>
	</body>


    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"> </script>
</html>