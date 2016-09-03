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

                window.location.href = "/wenda/me/currentme";

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
				<div class="mui-content-padded">
						<h4 >你的头衔(25字以内 )</h4>
					<?= $form->field($model,'title')->textarea(['rows'=>2,'maxlength'=>25,'placeholder'=>'某大学某专业博士 某大学某院/系 教师/教授/硕导/博导/院长 某单位职务']);?>
				</div>
				<div class="q"></div>
			
			  	
            <div class="mui-content-padded">
                <h4 class="s">介绍一下你自己吧 (100字以内)</h4>
                <?= $form->field($model,'description')->textarea(['rows'=>4,'maxlength'=>100,'placeholder'=>'简单介绍一下自己，如个人经历，擅长回答的方向和内容...']);?>
            </div>
            <div class="q"></div>
            <div class="mui-content-padded">
                <h4>希望在哪些分类中出现（可多选）</h4>
                <div style="position: relative;">
                    <!--<textarea id="test_div" readonly="readonly"></textarea>-->
                  <!--  <textarea id="user-label" class="form-control" name="User[label]" maxlength="100" rows="2"></textarea>-->
                    <?= $form->field($model,'label')->textarea(['rows'=>2,'maxlength'=>100,'readonly'=>'readonly','placeholder'=>'关于这些你可以尽情的问我......']);?>
                    <div>

                    </div>
                    
                </div>
            </div>
				<div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">微信号</h4>
					<div class="dianh  s">
						<?= $form->field($model,'wechatnumber')->textinput();?>
					</div>
				</div>

             <div class="q"></div>
				<div class="mui-content-padded">
					<h4 class="s">你的电话</h4>
					<div class="dianh s">
						<?= $form->field($model,'phone')->textinput();?>
					</div>
				</div>
             <div class="q"></div>
			 	<div class="mui-content-padded">
					<div  class="payment s">
					<h4 class=" mui-pull-left">向我提问需支付 </h4> <?= $form->field($model,'questionprice')->textinput();?>
					 <h4  class="mui-pull-left">元</h4> 
					</div>
				</div>
              <div class="q"></div>
                <div class="back-footer">
				
				
                <?php if($model->userstate<>1):?>
					<p style="padding-left: 10px;">审核信息提交后，工作人员会在24小时内为您更新信息。</p>

                <?endif?>
             
                  <div style="padding:10px;">
					<?php if($model->userstate<>1):?>
                        <?=Html::submitButton('提交审核',['id'=>'sub','class'=>'mui-btn mui-btn-primary mui-btn-block s'])?>
                     <?endif?>

                     <?php if($model->userstate==1):?>
                        <?=Html::submitButton('保存',['id'=>'sub','class'=>'mui-btn mui-btn-primary mui-btn-block s','onclick'=>'return reg()'])?>
                     <?endif?>
				   <div class="mui-checkbox">
                    <p class="tongy">
			
						<input id="check" checked=""   name="checkbox" value="" type="checkbox" >
						
                      
						我已阅读并同意<a href="">《 <?php echo DOMAITDESC ?>用户协议》</a>
                    </p>
	
                </div>
               </div>
                    </div>
                      <?php ActiveForm::end()?>

          
        </div>


        <!--	<div class="footer" >
			<div class="teacher-button">
				<button type="submit" class="mui-btn mui-btn-primary mui-btn-block"></button>
				</div>	
				
		</div>-->
  <?php $string="";
              if(count($category)>0):  ?>
        <?php foreach($category as $v):?>

        <? $string = $string.$v->categoryname.",";?>
        
        <?endforeach?>
        <?endif?>
        <input type="hidden" id="categoryname" name="categoryname" value="<?=$string?>">
	</div>	
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript" charset="UTF-8">

        mui.init();
		(function () {

                $.fn.extend({
                    checks_select: function (options) {
                        jq_checks_select = null;
                      // $(this).val("---请选择---");
                        $(this).next().empty(); //先清空 
                        $(this).unbind("click");
                        $(this).click(function (e) {
                            jq_check = $(this);
                            //jq_check.attr("class", ""); 
                            if (jq_checks_select == null) {
                                jq_checks_select = jq_check.next();
                                jq_checks_select.addClass("checks_div_select");
                                //jq_checks_select = $("<div class='checks_div_select'></div>").insertAfter(jq_check); 
                                $.each(options, function (i, n) {
                                    check_div = $("<div class='mui-checkbox-con'><label><input class='mui-checkbox' type='checkbox' value='" + n + "'>" + n + "</label></div>").appendTo(jq_checks_select);
                                    check_box = check_div.children();
                                    check_box.click(function (e) {
                                        //jq_check.attr("value",$(this).attr("value") ); 

                                        temp = "";
                                        $(this).parents().find("input:checked").each(function (i) {
                                            if (i == 0) {
                                                temp += $(this).val();
                                                $('#user-label').html(temp);
                                            } else {
                                                temp += "," + $(this).val();
                                                $('#user-label').html(temp);
                                            }
                                        });
                                        //alert(temp); 
                                        jq_check.val(temp);
                                        e.stopPropagation();
                                    });
                                });
                                jq_checks_select.show();
                            } else {
                                jq_checks_select.toggle();

                            }
                            e.stopPropagation();
                        });
                        $(document).click(function () {
                            flag = $("#test_div");
                            if (flag.val() == "") {
                                flag.val("---请选择---");
                            }
                            jq_checks_select.hide();
                        });
                        //$(this).blur(function(){ 
                        //jq_checks_select.css("visibility","hidden"); 
                        // alert(); 
                        //}); 
                    }
                })

            })(jQuery);

           // var categoryname = $($('#categoryname').val().split(","));
            //console.log(categoryname);
            
            //艺考, 高考, 考研, 考博, 考公务员, 考证, 留学, 创业, 求职, 其他,
                      
            $(document).ready(function () {
                var categoryname = $($('#categoryname').val().split(","));
                
           
                for (var i = 0 ; i < categoryname.length; i++) {
                    categoryname[i]

                }
              
                var options = {
                    1: categoryname[0],
                    2: categoryname[1],
                    3: categoryname[2],
                    4: categoryname[3],
                    5: categoryname[4],
                    6: categoryname[5],
                    7: categoryname[6],
                    8: categoryname[7],
                    9: categoryname[8],
                    10: categoryname[9]

                };
                $("#user-label").checks_select(options);
            });
		
		/*表单验证　*/
	
		$('#user-title').blur(function () {
            var userTitle = $('#user-title').val();
            if(userTitle == ""){
				alert("头衔不能为空");
				return false;
			}
        });
		
		$('#check').click(function(){
			var ischbox =   document.getElementById('check');					
			var  isopenask =0;					
			if(ischbox.checked){
			    isopenask =1;
				document.getElementById("sub").disabled = false;							
			}else{	
				isopenask =0;
				document.getElementById("sub").disabled = true;
			}
		});		
			
		$('#user-description').blur(function () {
            var userdescription = $('#user-description').val();
            if (userdescription == '') {
				alert("介绍自己不能为空");
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
           
        $('#user-questionprice').blur(function () {
            var userQuestionprice = $('#user-questionprice').val();
            if (userQuestionprice == "" || !/^[0-9]+([.]{1}[0-9]+){0,1}$/.test(userQuestionprice)) {
                alert("请输入支付金额");
                return false;
            }if (userQuestionprice * 100 <= 0) {
                alert("请输入支付金额");
                return false;
            }
        })
		//微信号
		$('#user-wechatnumber').blur(function(){
			var userwechatnumber = $('#user-wechatnumber').val();
			if (userwechatnumber == "" ||/^[\u4E00-\u9FA5]$/.test(userwechatnumber)) {
				alert('微信号输入格式不正确');
				return false;
			}
		})
		
		//$('#user-label').blur(function(){
			//var userlabel = $('#user-label').val();
			//if(userlabel == ""){
			//	alert('请选择类别');
			//	return false;
			//}
	//	})
		 
		
		function  reg(){
			var userTitle = document.getElementById("user-title").value;
			var userDescription = document.getElementById("user-description").value;
			var userPhone = document.getElementById("user-phone").value;
			var userQuestionprice = document.getElementById("user-questionprice").value;
			var userwechatnumber = $('#user-wechatnumber').val();
			var userlabel  =  $('#user-label').val();
			if(userTitle == ""){
				alert("头衔不能为空");
				return false;
			}if(userDescription == ""){
				alert("介绍自己不能为空");
				return false;
			}if (userwechatnumber == "" ||/[\u4E00-\u9FA5]/i.test(userwechatnumber)) {
				alert('微信号输入格式不正确');
				return false;
			}
			if(userPhone == "" || !/^(1[0-9][0-9])\d{8}$/.test(userPhone)){
				alert("手机号码不正确");
				return false;
			}
			if(userQuestionprice == "" || !/^[0-9]+([.]{1}[0-9]+){0,1}$/.test(userQuestionprice)){
				alert("请输入支付金额");
				return false;
			}
			if(userQuestionprice*100<=0){
                alert("请输入支付金额");
                return false;
            }
			else{
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
