<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>新增用户</title>

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
</head>
<body>
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

                    <?php $form=ActiveForm::begin(['id'=>'add','enableAjaxValidation'=>false]); ?>

                    <?= $form->field($model,'realname')->textinput();?>
                    <?= $form->field($model,'user')->textinput();?>
                    <?= $form->field($model,'pwd')->passwordInput();?>
                    <?= $form->field($model,'managecity')->textinput();?>
                    <?= $form->field($model,'phone')->textinput();?>
                    <?= $form->field($model,'wechatnumber')->textinput();?>
                    <?= $form->field($model,'qqnum')->textinput();?>
                    <?= $form->field($model,'managecity')->textinput();?>
                    <?= $form->field($model,'belongfirm')->textinput();?>
                    <?= $form->field($model,'belongfirmphone')->textinput();?>
                    <?= $form->field($model,'remark')->textinput();?>





                  
                    <?= $form->field($model,'userorder')->textinput();?>

                    <?=Html::submitButton('添加',['class'=>'btn btn-primary'])?>
                    <?php ActiveForm::end()?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
