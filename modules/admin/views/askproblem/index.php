<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\linkPager;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>问题列表</title>
    <?=Html::cssFile('@web/web/css/bootstrap.min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>
    <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>
</head>
<body>
    <div class="contianer">
        <div class="row">

            <div class="col-md-12">
                <div class="main">
                    <div class="row">
                        <?php $form=ActiveForm::begin(['id'=>'index','enableAjaxValidation'=>false]); ?>

                        <div class="col-md-2">
                            <?=$form->field($search,'questiondescription')->textinput();?>
                        </div>
                        <div class="col-md-2">
                            <?=$form->field($search,'nickname')->textinput();?>
                        </div>
                        <div class="col-md-2">
                            <?=$form->field($search,'answernname')->textinput();?>
                        </div>
                        <div class="col-md-1">
                            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
                        </div>
                        <?php ActiveForm::end()?>
                    </div>

                    <table class="table table-hover">
                        <tr>
                            <th>问题描述</th>
                            <th>提问人</th>
                            <th>回答人</th>
                            <th>提交时间</th>
                            <th>问答价格</th>
                            <th>回答时间</th>
                            <th>问题分类</th>
                            <th>排序</th>
                            <th>状态</th>
                            <th>是否公开</th>
                            <th>语音列表</th>
                            <th>操作</th>
                        </tr>
                        <?php if(count($items)>0):?>
                        <?php foreach($items as $v):
                                  $hour=ceil((time()-strtotime($v->asktime))/3600);
                                  $questionstate=$v->questionstate;
                        ?>
                        <tr>
                            <td><?=$v->questiondescription?></td>
                            <td><?=$v->getUser()->nickname?></td>
                            <td><?=$v->getUseranswer()->nickname?></td>
                            <td><?=$v->asktime?></td>
                            <td><?=$v->askfee?></td>
                            <td><?=$v->getAnswerquestion()->answertime?></td>
                            <td><?=$v->getCategory()->categoryname?></td>

                            <td><?=$v->questionorder?></td>

                            <?php if($hour<=72):?>
                            <?php  
                                      if($questionstate==0):?>
                            <td>待回答</td>
                            <?endif?>
                            <?php  if($questionstate==1):?>
                            <td>已回答</td>
                            <?endif?>
                            <?php  if($questionstate==2):?>
                            <td>待审核</td>
                            <?endif?>
                            <?endif?>
                            <?php if($hour>72):?>
                           <?php  
                                      if($questionstate==0):?>
                            <td>已过期</td>
                            <?endif?>
                            <?php  if($questionstate==1):?>
                            <td>已回答</td>
                            <?endif?>
                            <?php  if($questionstate==2):?>
                            <td>待审核</td>
                            <?endif?>
                            <?endif?>
                             <td><?php if($v->isopenask==1):?>
                                <? echo "已公开"?>
                                <?endif?>
                                <?php if($v->isopenask==0):?>
                                <? echo "未公开"?>
                                <?endif?>
                            </td>
                             <td>
                             <button class="btn btn-sm btn-danger btn-voice"  action="<?=Yii::$app->urlManager->createUrl(['admin/answerquestion/voice','id'=>$v->id])?>">语音列表</button>

                             </td>
                           

                            <td>
                                <?php  if($questionstate==0):?>
                                <button class="btn btn-sm btn-danger btn-edit" id="" action="<?=Yii::$app->urlManager->createUrl(['admin/askproblem/edit','id'=>$v->id])?>">编辑</button>
                                <?endif?>

                                <?php  if($questionstate==1):?>
                                <button class="btn btn-sm btn-danger btn-top" id="" action="<?=Yii::$app->urlManager->createUrl(['admin/askproblem/top','id'=>$v->id])?>">分类编辑</button>
                                <?endif?>

                                <?php  if($questionstate==2):?>

                                <a class="btn btn-sm btn-danger btn-examine" id="" action="<?=Yii::$app->urlManager->createUrl(['admin/askproblem/examine','id'=>$v->id])?>">通过</a>

                                <a class="btn btn-sm btn-danger btn-examine" id="" action="<?=Yii::$app->urlManager->createUrl(['admin/askproblem/delete','id'=>$v->id])?>">不通过</a>
                                <?endif?>

                                <button class="btn btn-sm btn-danger btn-delete"  action="<?=Yii::$app->urlManager->createUrl(['admin/askproblem/delete','id'=>$v->id])?>">删除</button>
                            </td>
                        </tr>
                        <?endforeach?>
                        <?else:?>
                        <tr>
                            <td colspan="5">暂无内容！</td>
                        </tr>
                        <?endif?>
                    </table>
                    <div class="page">
                        <?= LinkPager::widget(['pagination' => $page]) ?>

                        <?php if(count($items)>0):?>
                        <? echo "共".count($items)."条数据" ?>
                        <?endif?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="frametop" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title">置顶操作</h4>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" height="400px" width="550px"></iframe>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade" id="frameVoice" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" height="400px" width="550px"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="frameModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myModalLabel">组别操作</h4>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" height="400px" width="550px"></iframe>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="deleteModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myModalLabel">删除组别</h4>
                </div>
                <div class="modal-body">
                    确定删除吗？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary sure">确定</button>
                    <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <script>
        $(function () {
            //语音列表
            $(".btn-voice").click(function (e) {
                $("#frameVoice iframe").attr("src", $(this).attr("action"));
                $('#frameVoice').modal('show');
            });

            //编辑信息
            $(".btn-edit").click(function (e) {
                $("#frameModal iframe").attr("src", $(this).attr("action"));
                $('#frameModal').modal('show');
            });


            //置顶
            $(".btn-top").click(function (e) {
                $("#frametop iframe").attr("src", $(this).attr("action"));
                $('#frametop').modal('show');
            });

            ////审核问题
            //$(".btn-examine").click(function (e) {
            //    $("#frameexamine iframe").attr("src", $(this).attr("action"));
            //    $('#frameexamine').modal('show');
            //});

            //编辑信息
            $("#btn_add").click(function (e) {
                $("#frameModal iframe").attr("src", $(this).attr("action"));
                $('#frameModal').modal('show');
            });

            $('#frameModal').on('hidden.bs.modal', function () {
                window.location.reload();
            });

            //弹出删除确认框按钮
            $(".btn-delete").click(function () {
                $("#deleteModal .sure").attr("action", $(this).attr("action"));
                $('#deleteModal').modal('show');
            });

            $("#deleteModal .sure").click(function () {
                //执行删除

                $.ajax({
                    type: "GET",
                    url: $(this).attr("action"),
                    success: function (data) {
                        if (data == 0) {
                            window.location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            });

        })
    </script>
</body>
</html>
