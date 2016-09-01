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
    <title>编辑老师</title>
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

                    <?php $form=ActiveForm::begin(['id'=>'edit','enableAjaxValidation'=>false,
                        'options' => ['enctype' => 'multipart/form-data']
                    ]); ?>


                    <?= $form->field($model,'bannertitle')->textinput();?>
                    <?= $form->field($model,'linkurl')->textinput();?>
                    <?= $form->field($model,'order')->textinput();?>
                    <?= $form->field($model,'remark')->textinput();?>

                    <input type="hidden" id="banner-bannerimgpath" class="form-control" name="Banner[bannerimgpath]" value="<?=$model->bannerimgpath; ?>">
                    <?= $form->field($model, 'bighead_file')->widget(FileInput::classname(), [
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
                                $('#banner-bighead_file').val('');
                             }",
                          ],
                    ]);?>


                    <?=Html::submitButton('修改',['class'=>'btn btn-primary'])?>
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