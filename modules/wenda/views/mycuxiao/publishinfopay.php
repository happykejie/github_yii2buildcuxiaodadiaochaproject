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

     <?=Html::cssFile('@web/web/assets/mui/css/css/BecomeTeacher.css')?>
  

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

            function changepay()
            {
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
                        'id'=>'publishinfonew',
                        'enableAjaxValidation'=>false,
                        'options' => ['enctype' => 'multipart/form-data']]); ?>
						<h4 >活动名称(12字以内 )</h4>
					<?= $form->field($model,'name')->textarea(['rows'=>1,'maxlength'=>25,'placeholder'=>'阿欢阿杰科技促销平台']);?>
						<h4 >所属分类</h4>
					 <?=$form->field($model,'group_id')->dropDownList($to)?>
                    <h4 >发布城市</h4>
                     <?= $form->field($model,'belongarea')->textinput(['readonly'=>'readonly','value'=>Yii::$app->cache->get('citynamenew')]);?>
                
                   
                   
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


                     <h4 >应支付金额</h4>
                    <?= $form->field($model,'paynum')->textinput(['readonly'=>'readonly']);?>



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


        $(function () {


            //开始时间改变
            $("#activity-start_time").change(function () {

                $("#activity-paynum").val("");


                var start_time = $("#activity-start_time").val();
               // alert(start_time);
                if (start_time == "")
                {
                    return false;
                }
                else //如果开始时间不为空
                {
                    //查看有无结束时间

                    var end_time = $("#activity-end_time").val();
                   // alert(end_time);
                    if (end_time == "") {
                        //alert('empty');
                        return false;

                    }
                    else {  ///如果有结束时间则先计算开始时间是否小于结束时间， 如果是则计算天数

                        var beginDate = new Date(start_time).Format("yyyy-MM-dd");
                       // alert(beginDate);

                        var endDate = new Date(end_time).Format("yyyy-MM-dd");
                       // alert(endDate);


                        if (beginDate >= endDate) {
                            alert("结束时间必须大于开始时间！");
                            return false;
                        } else {
                            
                            var daysnum = DateDiff(beginDate, endDate);

                            var paynum = parseInt(daysnum) * 10;

                            $("#activity-paynum").val(paynum);
                        }

                    }
                }
            });

            //结束时间改变
            $("#activity-end_time").change(function () {

                $("#activity-paynum").val("");


                var end_time = $("#activity-end_time").val();
               // alert(end_time);
                if (end_time == "") {
                    //alert('empty');
                    return false;
                }
                else { //有结束时间

                    //查看有无开始时间

                    var start_time = $("#activity-start_time").val();
                    // alert(end_time);
                    if (start_time == "") {
                        //alert('empty');
                        return false;

                    }
                    else {  ///如果有结束时间则先计算开始时间是否小于结束时间， 如果是则计算天数

                        var beginDate = new Date(start_time).Format("yyyy-MM-dd");
                        // alert(beginDate);

                        var endDate = new Date(end_time).Format("yyyy-MM-dd");
                        // alert(endDate);


                        if (beginDate >= endDate) {
                            alert("结束时间必须大于开始时间！");
                            return false;
                        } else {

                            var daysnum = DateDiff(beginDate, endDate);

                            var paynum = parseInt(daysnum) * 10;

                            $("#activity-paynum").val(paynum);
                        }

                    }

                }
            });


            Date.prototype.Format = function (fmt) { //author: meizz 
                var o = {
                    "M+": this.getMonth() + 1, //月份 
                    "d+": this.getDate(), //日 
                    "h+": this.getHours(), //小时 
                    "m+": this.getMinutes(), //分 
                    "s+": this.getSeconds(), //秒 
                    "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
                    "S": this.getMilliseconds() //毫秒 
                };
                if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                for (var k in o)
                    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                return fmt;
            }



            //计算天数差的函数，通用  
            function DateDiff(sDate1, sDate2) {    //sDate1和sDate2是2006-12-18格式  
                var aDate, oDate1, oDate2, iDays
                aDate = sDate1.split("-")
                oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0])    //转换为12-18-2006格式  
                aDate = sDate2.split("-")
                oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0])
                iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24)    //把相差的毫秒数转换为天数  
                return iDays
            }

           

        })


       
        function testchange()
        {
            alert("45454454");
        }


      




        $('#activity-start_time').bind('input propertychange', function () {

            // 密码检验

            alert('test');

            var test = $("#activity-start_time").val();

            alert(test);

        });




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
</body>
</html>
<?php $this->endPage() ?>