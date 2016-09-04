<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>活动管理</title>
    <?=Html::cssFile('@web/web/css/bootstrap.min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>
    <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>
         <?=Html::cssFile('@web/web/assets/citypicker/css/cityPicker.css')?>
</head>
<body>
    <div class="contianer">
        <div class="row">
            <div class="col-md-12">
                <div class="main">
                    <div class="tool">
                        <a id="btn_add" class="btn btn-primary btn-sm" href="<?=Yii::$app->urlManager->createUrl('admin/activity/add')?>">添加活动</a>
                    </div>

                       <div class="row">
                        <?php $form=ActiveForm::begin(['id'=>'index','enableAjaxValidation'=>false]); ?>

                        <div class="col-md-2">
                           <?=$form->field($search,'name')->textinput();?>

                        </div>
                         <div class="col-md-2">
                         <?=$form->field($search,'group_id')->dropDownList($to)?>

                         </div>
                          <div class="col-md-2"> 
                          <?=$form->field($search,'belongarea')->textinput();?>
                          </div>

                    
                        <div class="col-md-1">
                            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
                        </div>
                        <?php ActiveForm::end()?>
                        </div>

                    <table class="table table-hover">
                        <tr> 
                            <th>名称</th>
                            <th>组别</th>
                            <th>起始日期</th>
                            <th>截至日期</th>
                            <th>城市</th>
                            <th>是否支付</th>
                            <th>金额</th>
                            <th>发布者</th>
                            <th>发布者电话</th>
                            <th>浏览次数</th>
                            <th>操作</th>
                        </tr>
                        <?php if(count($items)>0):?>
                        <?php foreach($items as $v):?>
                        <tr> 
                            <td><?=$v->name?></td>
                            <th><?=$v->getGroup()->categoryname?></th>
                            <th><?=$v->start_time?></th>
                            <th><?=$v->end_time?></th>
                            <th><?=$v->belongarea?></th>
                            <th><?=$v->ispay?></th>
                            <th><?=$v->paynum?></th>
                            <th><?=$v->getUserinfo()->realname?></th>
                            <th><?=$v->getUserinfo()->phone?></th>
                            <th><?=$v->viewcount?></th>




                           
                            <td>
                                <a class="btn btn-sm btn-danger btn-edit" id="" href="<?=Yii::$app->urlManager->createUrl(['admin/activity/edit','id'=>$v->id])?>">编辑</a>
                                <button class="btn btn-sm btn-danger btn-delete"  action="<?=Yii::$app->urlManager->createUrl(['admin/activity/delete','id'=>$v->id])?>">删除</button>
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
                    <h4 class="modal-title" >活动操作</h4>
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
                    <h4 class="modal-title" >删除活动</h4>
                </div>
                <div class="modal-body">
                    确定删除内容吗？
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
