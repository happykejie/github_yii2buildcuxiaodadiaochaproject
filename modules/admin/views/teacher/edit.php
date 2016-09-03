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

                    <?php $form=ActiveForm::begin(['id'=>'edit',
                        'enableAjaxValidation'=>false,
                        'options' => ['enctype' => 'multipart/form-data']
                        ]); ?>
                    <?= $form->field($model,'nickname')->textinput();?>
                    <?= $form->field($model,'wechatnumber')->textinput();?>
                    <?= $form->field($model,'title')->textinput();?>
                    <?= $form->field($model,'description')->textinput();?>
                    <?= $form->field($model,'phone')->textinput();?>
                    <?= $form->field($model,'questionprice')->textinput();?>
                    <?= $form->field($model,'label')->textarea(['rows'=>2,'maxlength'=>100,'readonly'=>'readonly','placeholder'=>'关于这些你可以尽情的问我......']);?>
                    <?= $form->field($model,'createteachertime')->textinput();?>
                    <?= $form->field($model,'userorder')->textinput();?>

                    <input type="hidden" id="user-headimgurl" class="form-control" name="User[headimgurl]" value="<?=$model->headimgurl;?>">
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
                                $('#user-bighead_file').val('');
                             }",
                          ],
                    ]);?>


                    <?=Html::submitButton('修改',['class'=>'btn btn-primary'])?>
                    <?php ActiveForm::end()?>
                </div>
            </div>

            <?php $string="";
                  if(count($category)>0):  ?>
            <?php foreach($category as $v):?>

            <? $string = $string.$v->categoryname.",";?>

            <?endforeach?>
            <?endif?>
            <input type="hidden" id="categoryname" name="categoryname" value="<?=$string?>">
        </div>

    </div>
   

    <script>
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
                                check_div = $("<div><input type='checkbox' value='" + n + "'>" + n + "</div>").appendTo(jq_checks_select);
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
    </script>
</body>
</html>
<?php $this->endPage() ?>