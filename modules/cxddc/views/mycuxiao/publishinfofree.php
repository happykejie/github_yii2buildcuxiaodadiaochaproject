<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use dosamigos\datetimepicker\DateTimePicker;
use dosamigos\datetimepicker\DateTimePickerAsset;
use \yii\redactor\widgets\Redactor;
use kartik\file\FileInput;

function getinitialPreviewConfig($imgs){
    $data=[];
    foreach($imgs as $img){
        if(!empty($img)){
            array_push($data,
                    [
                        'caption'=> '', 
                        'width'=> '120px', 
                        'url'=> '/admin/activity/deleteupload', // server delete action 
                        'key'=> $img,
                    ] );
        }
    }
    return $data;
}

function getinitialPreview($imgs){
    $data=[];
    foreach($imgs as $img){
        if(!empty($img)){
            array_push($data,$img);
        }
    }
    return $data;
}

?>


<?php
require_once "models/WxJsSdk.php";
$jssdk = new WxJsSdk(WX_APPID, WX_APPSECRET);  
$signPackage = $jssdk->GetSignPackage();
?>


<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title>新增活动</title>
    <?=Html::cssFile('@web/web/css/bootstrap.min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>

     <?=Html::cssFile('@web/web/assets/mui/css/css/Becomeuser.css')?>
  

    <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>

    <?php $this->head() ?>


    <style>



        .input-group-btn:last-child .btn{
                margin-left: 20px;
    margin-top: -50px;
        }

   
    </style>

        <script>

            function changepay() {
                alert('test');
            }


        </script>

</head>
<body>
    <?php $this->beginBody() ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="main">
                    <?php if(Yii::$app->session->hasFlash('success')):?>
                    <div class="alert alert-success text">
                        <b><?=Yii::$app->session->getFlash('success')?></b>
                        <script>
                            setTimeout('parent.location.reload()', 2000);
                        </script>
                    </div>
                    <?endif?>

                    <?php if(Yii::$app->session->hasFlash('error')):?>
                    <div class="alert alert-error text">
                        <b><?=Yii::$app->session->getFlash('error')?></b>
                    </div>
                    <?endif?>

                    <?php $form=ActiveForm::begin([
                        'id'=>'publishinfofree',
                        'enableAjaxValidation'=>false,
                        'options' => ['enctype' => 'multipart/form-data']]); ?>
						<h4 >活动名称(25字以内 )</h4>
					<?= $form->field($model,'name')->textarea(['rows'=>1,'maxlength'=>25,'placeholder'=>'阿欢阿杰科技促销平台']);?>
						<h4 >所属分类</h4>
					 <?=$form->field($model,'group_id')->dropDownList($to)?>
                    <h4 >发布城市</h4>
                     <?= $form->field($model,'belongarea')->textinput(['readonly'=>'readonly','value'=>Yii::$app->cache->get('citynamenew')]);?>
                  
                   
	            <h4 >&nbsp;&nbsp;&nbsp;开始时间</h4>
         <?= $form->field($model,'start_time')->textinput(['value'=>date('y-m-d',time()).' '.'00:00:00','readonly'=>'readonly'])?>


                 <h4 >&nbsp;&nbsp;&nbsp;结束时间</h4>
                

                     <?= $form->field($model,'end_time')->textinput(['value'=>date('y-m-d',time()).' '.'59:59:59','readonly'=>'readonly'])?>



                    <h4 >活动介绍</h4>
                      <?= $form->field($model,'intro')->textarea(['rows'=>4,'maxlength'=>1000,'placeholder'=>'本次活动介绍...']);?>

                    <h4 >活动地址</h4>
                    <?= $form->field($model,'address')->textarea(['rows'=>1,'placeholder'=>'成都市....或者http://www.baidu.com'])?>
                    <h4 >联系方式</h4>

                    <?= $form->field($model,'contacttype')->textarea(['rows'=>1,'placeholder'=>'电话，微信，QQ，邮件.....'])?>

                    <h4 >封面</h4>

                    <input type="hidden" id="activity-surface" class="form-control" name="Activity[surface]" value="<?=$model->surface; ?>">
                    <?=  $form->field($model, 'surface_file')->widget(FileInput::classname(), [
                         'options' => 
                            [
                                'accept' => 'image/*',
                                'multiple' => false
                            ],
                         'pluginOptions' => [
                                'initialPreviewAsData'=>true,
                                'overwriteInitial'=>true,
                                'showRemove' => true,
                                'allowedFileExtensions'=>[ 'jpg', 'jpeg', 'png'],                                
                                'showUpload' => false
                           ], 'pluginEvents' => [
                             'filecleared'=>"function(event) {
                                $('#activity-surface').val('');
                             }",
                          ]
                    ]);?>


                 
                    <h4 >活动图片(最多3张)</h4>
                    
                    <input id="newspictures_val" type="hidden" name="newspictures_val" value="<?=implode('-',$model->newspictures); ?>"/>
                    <?=  $form->field($model, 'newspictures[]')->widget(FileInput::classname(), [
                            'options' => 
                            [
                                'accept' => 'image/*',
                                'multiple' => true
                            ],
                            'pluginOptions' => [
                                'uploadUrl'=>'/admin/activity/upload', //上传的地址
                                'uploadAsync'=>true,
                                'deleteUrl'=>'/admin/activity/deleteupload',
                                'allowedPreviewTypes'=>[ 'image' ],
								'allowedFileExtensions'=>[ 'jpg', 'jpeg', 'png'],
								'previewFileType' => 'image',
								'initialPreview'=>getinitialPreview($model->newspictures),
								'initialPreviewConfig'=> getinitialPreviewConfig($model->newspictures),
								'initialPreviewAsData'=>true,
								'overwriteInitial'=>false,
								'dropZoneEnabled'=>false,
								'showRemove' => true,
								'showUpload' => false,
								'enctype'=> 'multipart/form-data',
								'validateInitialCount'=>false,
								'resizeImage'=>true,
								'resizePreference'=>'width',
								'resizeQuality'=>0.6,
								'resizeDefaultImageType'=>'image/jpeg',
								'maxFileSize'=>20144,
								'maxFilePreviewSize'=>306864,
								// 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
								'fileActionSettings' => [
									// 设置具体图片的查看属性为false,默认为true
									'showZoom' => false,
									// 设置具体图片的上传属性为true,默认为true
									'showUpload' => true,
									// 设置具体图片的移除属性为true,默认为true
									'showRemove' => false,
								],
                        ],
                        // 一些事件行为
                        'pluginEvents' => [
                                "fileuploaded" => "function (event, data, id, index) {
                                $('#newspictures_val').attr(id,data.response);
                                $('#newspictures_val').val(data.response +$('#newspictures_val').val() );
                                    $('.field-player-img').removeClass('has-error');
								$('.field-player-img .help-block-error').text('');
                            }",
                            'filesuccessremove'=> "function(event, id) {
                                var key = $('#newspictures_val').attr(id);
                                $('#newspictures_val').val($('#newspictures_val').val().replace(key,''));
                            }",
                            'filedeleted'=> "function(event, key) {
                                $('#newspictures_val').val($('#newspictures_val').val().replace(key,''));
                            }",
                            'filecleared'=>"function(event) {
                                $('#newspictures_val').val('');
                            }",
                            'filereset'=>"function(event) {
                                console.log('filereset');
                            }",
                            "filebatchselected"=>"function(event, files) {
                                $(this).fileinput('upload');
                            }",
                        ]
                ]);?>

                    
                         <?=Html::submitButton('发布消息',['id'=>'sub','class'=>'mui-btn mui-btn-primary mui-btn-block s'])?>
                     
                    <?php ActiveForm::end()?>


                         
                </div>
            </div>

            <div class="col-md-6" style="height:100px"></div>
        </div>
    </div>


    <?php $this->endBody() ?>

    <script>
        $("form").submit(function (e) {

            var surface_file = $('input[name="Activity[surface_file]"][type=file]')[0].value;
            var homepictures_val = $('#activity-surface').val();

            if (!surface_file && !homepictures_val) {
                $(".field-activity-surface_file").addClass("has-error");
                $(".field-activity-surface_file .help-block-error").text("图片不能为空");
                return false;
            } else {
                $('.field-activity-surface_file').removeClass('has-error');
                $('.field-activity-surface_file .help-block-error').text('');
            }



            var newspictures_val = $('#newspictures_val').val();
            if (!newspictures_val) {
                $(".field-activity-newspictures").addClass("has-error");
                $(".field-activity-newspictures .help-block-error").text("图片不能为空");
                return false;
            } else {
                $('.field-activity-newspictures').removeClass('has-error');
                $('.field-activity-newspictures .help-block-error').text('');
            }
            return true;
        });
    </script>

      <input type="hidden" value="<?= $currentuserid?>" id="userid"/>
      	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxzhuyejs.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->

</body>
</html>
<?php $this->endPage() ?>