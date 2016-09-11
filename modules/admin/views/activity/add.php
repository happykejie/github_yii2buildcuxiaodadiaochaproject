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
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title>新增活动</title>
    <?=Html::cssFile('@web/web/css/bootstrap.min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>

    <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>
    <?=Html::cssFile('@web/web/assets/citypicker/css/cityPicker.css')?>
    <?php $this->head() ?>

        <style>
                    .input-group-btn:last-child .btn{
                margin-left: 20px;
    margin-top: -50px;
        }
        </style>

</head>
<body>
    <?php $this->beginBody() ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
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
                        'id'=>'add',
                        'enableAjaxValidation'=>false,
                        'options' => ['enctype' => 'multipart/form-data']]); ?>
                    <?= $form->field($model,'name')->textinput();?>
                    <?=$form->field($model,'group_id')->dropDownList($to)?>
                    <?= $form->field($model,'belongarea')->textinput();?>
                    <?= $form->field($model,'ispay')->dropDownList(['是'=>'是','否'=>'否']);?>
                    <?= $form->field($model,'paynum')->textinput();?>
                    <?= $form->field($model,'lableremark')->textinput();?>

                    <input type="hidden" id="province" value="">
                    <input type="hidden" id="city" value="">




	<h4 >&nbsp;&nbsp;&nbsp;开始时间</h4>
                <?= DateTimePicker::widget([
                    'model' => $model,
                    'attribute' => 'start_time',
                    'language' => 'zh-CN',
                    'size' => 'ms',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'todayBtn' => true
                    ]
                ]);?>


                        <h4 >&nbsp;&nbsp;&nbsp;结束时间</h4>
                              <?= DateTimePicker::widget([
                    'model' => $model,
                    'attribute' => 'end_time',
                    'language' => 'zh-CN',
                    'size' => 'ms',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'todayBtn' => true
                    ]
                ]);?>



                         











                


                    <?= $form->field($model, 'intro')->widget(Redactor::className(), [
                        'clientOptions' => [
                            'imageManagerJson' => ['/redactor/upload/image-json'],
                            'imageUpload' => ['/redactor/upload/image'],
                            'fileUpload' => ['/redactor/upload/file'],
                            'lang' => 'zh_cn',
                            'plugins' => ['clips', 'fontcolor','imagemanager']
                        ]
                    ])?>
                    <?= $form->field($model,'address')->textarea(['rows'=>1])?>
                    <?= $form->field($model,'contacttype')->textarea(['rows'=>1])?>

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
                          
                                'showUpload' => false
                           ], 'pluginEvents' => [
                             'filecleared'=>"function(event) {
                                $('#activity-surface').val('');
                             }",
                          ]
                    ]);?>

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
								'allowedFileExtensions'=>[ 'jpg', 'jpeg', 'png','gif'],
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
								'maxFileSize'=>0,
								'maxFilePreviewSize'=>36864,
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

                    <?=Html::submitButton('添加',['class'=>'btn btn-primary'])?>
                    <?php ActiveForm::end()?>
                </div>
            </div>
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


    <?=Html::jsFile('@web/web/assets/citypicker/js/cityData.js')?>
    <?=Html::jsFile('@web/web/assets/citypicker/js/cityPicker.js')?>



<script>
    var cityPicker = new IIInsomniaCityPicker({
        data: cityData,
        target: '#activity-belongarea',
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
<?php $this->endPage() ?>