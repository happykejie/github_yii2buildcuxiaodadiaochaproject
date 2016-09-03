<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;


use kartik\file\FileInput;

function getinitialPreviewConfig($imgs){
    $data=[];
    foreach($imgs as $img){
        if(!empty($img)){
            array_push($data,
                    [
                        'caption'=> '', 
                        'width'=> '120px', 
                         'url'=> '../deleteupload', // server delete action 
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
    <title>新增促销信息</title>
    <?=Html::cssFile('@web/web/css/bootstrap.min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>
    <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>
    



    <script>
        $(function () {
            ckinfo();
            //检查信息框
            function ckinfo() {
                var len = $(".text").length;
                if (len) {
                    fadeInfo();
                }
            }


            //消息消失动画
            function fadeInfo() {
                setTimeout(function () {
                    //alert('消息框即将消失！');
                    $(".text").fadeOut(800);
                }, 1000)
            }
        })

    </script>

    <?php $this->head() ?>
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

                      <?php $form=ActiveForm::begin(['id'=>'add','enableAjaxValidation'=>false,
                        'options' => ['enctype' => 'multipart/form-data']
                    ]); ?>

                    <?= $form->field($model,'title')->textinput();?>
                  

                      <?= $form->field($model,'description')->textarea(['rows'=>4,'maxlength'=>100,'placeholder'=>'简单介绍促销信息的内容（在100字以内）']);?>


                      <?= $form->field($model,'detail')->textarea(['rows'=>6,'maxlength'=>1000,'placeholder'=>'详细描述本次发布促销信息的情况（在1000字以内）']);?>


                    <?= $form->field($model,'starttime')->textinput();?>
                    <?= $form->field($model,'endtime')->textinput();?>
                    <?= $form->field($model,'address')->textinput();?>
                    <?= $form->field($model,'order')->textinput();?>
                    <?= $form->field($model,'remark')->textinput();?>

                    <input type="hidden" id="publishinfo-headimg" class="form-control" name="PublishInfo[headimg]" value="<?=$model->headimg; ?>">
                    <?= $form->field($model, 'headimg_file')->widget(FileInput::classname(), [
                         'options' => 
                            [
                                'accept' => 'image/*',
                                'multiple' => false
                            ],
                         'pluginOptions' => [
                                'initialPreviewAsData'=>true,
                                'overwriteInitial'=>true,
                                'showRemove' => true,
                                'showUpload' => false,
                         ], 'pluginEvents' => [
                             'filecleared'=>"function(event) {
                                $('#banner-headimg_file').val('');
                             }",
                          ],
                    ]);?>



                          <input type="hidden" id="publishinfo-backimg" class="form-control" name="PublishInfo[backimg]" value="<?=$model->backimg; ?>">
                    <?= $form->field($model, 'backimg_file')->widget(FileInput::classname(), [
                         'options' => 
                            [
                                'accept' => 'image/*',
                                'multiple' => false
                            ],
                         'pluginOptions' => [
                                'initialPreviewAsData'=>true,
                                'overwriteInitial'=>true,
                                'showRemove' => true,
                                'showUpload' => false,
                         ], 'pluginEvents' => [
                             'filecleared'=>"function(event) {
                                $('#banner-backimg_file').val('');
                             }",
                          ],
                    ]);?>



                    <?=Html::submitButton('添加',['class'=>'btn btn-primary'])?>
                    <?php ActiveForm::end()?>

                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
   <!-- <script>

        $("form").submit(function (e) {
            var bighead_file = $('input[name="Banner[bighead_file]"][type=file]')[0].value;

            if (!bighead_file) {
                $(".field-banner-bighead_file").addClass("has-error");
                $(".field-banner-bighead_file .help-block-error").text("图片不能为空");
                return false;
            } else {
                $('.field-banner-bighead_file').removeClass('has-error');
                $('.field-banner-bighead_file .help-block-error').text('');
            }
            return true;
        });
    </script>-->
</body>
</html>
<?php $this->endPage() ?>