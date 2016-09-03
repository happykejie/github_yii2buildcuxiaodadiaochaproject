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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>申请成为发布者</title>
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/BecomeTeacher.css')?>
         <?=Html::cssFile('@web/web/assets/wenda/css/teacher.css')?>
	<style>
		.checks_div_select{
			background-color:#7ad9d3;
			overflow:hidden;
			height: 14rem;
		}
		.checks_div_select div{
			color: #fff;
			width:30%;
			height:40px;
			float:left;
			padding-left: 10px;
			padding-top: 8px;
			overflow: hidden;
			line-height: 40px;
		}
		input[type=checkbox], input[type=radio] {
			box-sizing: border-box;
			padding: 0;
			width: 30px;
			height: 20px;
			position: relative;
			top: 1px;
		}
	
	</style>
</head>
<body>

    <div class="mui-content" style="position: relative;">


        <?php if(Yii::$app->session->hasFlash('success')):?>
        <div class="alert alert-success text" >
            <b><?=Yii::$app->session->getFlash('success')?></b>
            <script>
                //setTimeout('parent.location.reload()', 2000);

                window.location.href = "/wenda/mycuxiao/index";

            </script>
        </div>
        <?endif?>


        <div class="teacher-back">
           

                <?php if(Yii::$app->session->hasFlash('error')):?>
                <div class="alert alert-error text">
                    <b><?=Yii::$app->session->getFlash('error')?></b>
                    <script>
                       
                    </script>
                </div>
                <?endif?>
                <?php $form=ActiveForm::begin(['id'=>'becometeacher123','enableAjaxValidation'=>false]); ?>
				<div class="q"></div>

				<div class="mui-content-padded">
					<h4 class="s">真实姓名(必填)</h4>
					<div class="dianh  s">
						<?= $form->field($model,'realname')->textinput();?>
					</div>
				</div>

                <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">你的电话(必填)</h4>
					<div class="dianh s">
						<?= $form->field($model,'phone')->textinput();?>
					</div>
				</div>

            <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">微信号(必填)</h4>
					<div class="dianh  s">
						<?= $form->field($model,'wechatnumber')->textinput();?>
					</div>
				</div>


             <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">你的QQ(选填)</h4>
					<div class="dianh s">
						<?= $form->field($model,'qqnum')->textinput();?>
					</div>
				</div>

            	<div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">所属机构,单位，公司(选填)</h4>
					<div class="dianh  s">
						<?= $form->field($model,'belongfirm')->textinput();?>
					</div>
				</div>


              <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">所属机构,单位，公司的电话(选填)</h4>
					<div class="dianh s">
						<?= $form->field($model,'belongfirmphone')->textinput();?>
					</div>
				</div>

		    <div class="q"></div>
            <div class="mui-content-padded">
                <h4 class="s">介绍一下你自己吧 (100字以内)</h4>
                <?= $form->field($model,'description')->textarea(['rows'=>4,'maxlength'=>100,'placeholder'=>'简单介绍一下自己，如个人经历，擅长回答的方向和内容...']);?>
            </div>

          
              <div class="q"></div>
                <div class="back-footer">
				
				
                <?php if($model->userstate<>1):?>
					<p style="padding-left: 10px;">审核信息提交后，工作人员会在24小时内为您更新信息。</p>

                <?endif?>
             
                  <div style="padding:10px;">
				
                        <?=Html::submitButton('保存',['id'=>'sub','class'=>'mui-btn mui-btn-primary mui-btn-block s','onclick'=>'return reg()'])?>
               </div>
                    </div>
                      <?php ActiveForm::end()?>

          
        </div>


      
	</div>	
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript" charset="UTF-8">

        mui.init();
    

       
        /*表单验证　*/

        $('#user-name').blur(function () {
            var userTitle = $('#user-name').val();
            if (userTitle == "") {
                alert("真实不能为空");
                return false;
            }
        });



    

        $('#user-phone').blur(function () {
            var userPhone = $('#user-phone').val();
            if (userPhone == "" || !/^(1[0-9][0-9])\d{8}$/.test(userPhone)) {
                alert("手机号码不正确");
                return false;
            }
        })

   
        //微信号
        $('#user-wechatnumber').blur(function () {
            var userwechatnumber = $('#user-wechatnumber').val();
            if (userwechatnumber == "" || /^[\u4E00-\u9FA5]$/.test(userwechatnumber)) {
                alert('微信号输入格式不正确');
                return false;
            }
        })



        function reg() {
            var userTitle = document.getElementById("user-realname").value;
           
            var userPhone = document.getElementById("user-phone").value;
           
            var userwechatnumber = $('#user-wechatnumber').val();
          
            if (userTitle == "") {
                alert("真实不能为空");
                return false;
            }
         if (userwechatnumber == "" || /[\u4E00-\u9FA5]/i.test(userwechatnumber)) {
                alert('微信号输入格式不正确');
                return false;
            }
            if (userPhone == "" || !/^(1[0-9][0-9])\d{8}$/.test(userPhone)) {
                alert("手机号码不正确");
                return false;
            }
          
            else {
                return true;
            }
        }
    </script>
	
	
     <!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH . '/config/wxfxjs.php'); ///引入微信分享1
    ?> 
    <!--End 结束分享功能-->
</body>
</html>
