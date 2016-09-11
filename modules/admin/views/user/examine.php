<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>编辑用户</title>
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

                    <?php $form=ActiveForm::begin(['id'=>'edit','enableAjaxValidation'=>false]); ?>
                    <lable>昵称：<?echo $model->nickname?> </lable>

                   

                    <lable>真实姓名：<?echo $model->realname?></lable>

                    <lable>电话号码：<?echo $model->phone?> </lable>

                    <lable>微信号：<?echo $model->wechatnumber?> </lable>

                    <lable>个人介绍：<?echo $model->description?> </lable>

                    <?= $form->field($model,'userorder')->textinput();?>

                    <?=Html::submitButton('通过',['class'=>'btn btn-primary'])?>
                   <!-- <?=Html::resetButton('取消', ['class'=>'btn btn-primary','name' =>'submit-button']) ?>-->

                    <?php ActiveForm::end()?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
