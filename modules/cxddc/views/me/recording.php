<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>¼��ǰ</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />


      <link rel="stylesheet" href="http://demo.open.weixin.qq.com/jssdk/css/style.css?ts=1420774989">
    <script src="/web/Js/jquery.js"></script>
    <!--App�Զ����css-->
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
									<h4 class="oa-contact-name">Ҷ�Ľ�</h4>
									<span class="recording-span">��36.00</span>
								</div>
							
							</div>
						</div>
					<h4 class="recording-h4">��β�����Ч���У��ڱ����ڼ�Ӧ��ע����Щ���⣿</h4>
					<p>2��ǰ</p>
					</div>
				</li>
			</ul>
			<div class="recording-footer">
			  <div class="mui-checkbox" >
            <p class="tongy" >
                <input name="checkbox" value="Item 2" type="checkbox" checked="">
                ���ʹ����ش𣬴�ÿ���˰���1�Σ���ͽ��0.5Ԫ
            </p>
        </div>
        	    <div class="footer-cent">
        		<p class="recording-p">��ס��ʼ¼����¼��ʱ��������60��</p>
        		<div class="recording-button">
        		   		<button  type="button" class="mui-btn-primary recording-ti">
            <img src="/web/assets/mui/images/icon2.png" alt="" />        		</button>
        		   		<button type="button" class="mui-btn-primary recording-ti1">
            ��¼
        		</button>
        		</div>
        	</div>
        	<div class="footer-botton">
        		<button type="button" class="mui-btn-primary  recording-ti3">
            ȷ���ύ
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