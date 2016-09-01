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
    <title>组别管理</title>
    <?=Html::cssFile('@web/web/css/bootstrap.min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>
    <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>
</head>
<body>
    <div class="contianer">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="main">
                    <h2>用户关注管理</h2>
                    <!-- <div class="tool">
                        <button id="btn_add" class="btn btn-primary btn-sm" action="<?=Yii::$app->urlManager->createUrl('admin/userattention/add')?>">新增组别</button>
                    </div>-->
                    <div class="row">
                        <?php $form=ActiveForm::begin(['id'=>'index','enableAjaxValidation'=>false]); ?>

                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <?=$form->field($search,'nickname')->textinput();?>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <?=$form->field($search,'attentionname')->textinput();?>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
                        </div>
                        <?php ActiveForm::end()?>
                    </div>

                    <table class="table table-hover">
                        <tr>
                            <th>序号</th>
                            <th>用户名称</th>
                            <th>被关注的老师</th>
                            <th>关注时间</th>
                            <th>操作</th>
                        </tr>
                        <?php if(count($items)>0):
                                  $number=1?>
                        <?php foreach($items as $v): ?>
                        <tr>
                              <td><? echo $number?></td>
                            <td><?=$v->getuser()->nickname?><button class="btn btn-sm btn-danger btn-follow"  action="<?=Yii::$app->urlManager->createUrl(['admin/userattention/followdetailed','id'=>$v->attentionuserid])?>">More</button></td>
                            <td><?=$v->GetTeacher()->nickname?></td>

                            <td><?=$v->attentiontime?></td>

                            <td>
                                <!--<button class="btn btn-sm btn-danger btn-edit" id="" action="<?=Yii::$app->urlManager->createUrl(['admin/userattention/edit','id'=>$v->id])?>">编辑</button>-->
                                <button class="btn btn-sm btn-danger btn-delete"  action="<?=Yii::$app->urlManager->createUrl(['admin/userattention/delete','id'=>$v->id])?>">删除</button>
                            </td>
                        </tr>
                        <? $number++?>
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

       <div class="modal fade" id="framefollow" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

            

            //编辑信息
            $(".btn-follow").click(function (e) {
                $("#framefollow iframe").attr("src", $(this).attr("action"));
                $('#framefollow').modal('show');
            });

            //编辑信息
            $(".btn-edit").click(function (e) {
                $("#frameModal iframe").attr("src", $(this).attr("action"));
                $('#frameModal').modal('show');
            });

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
