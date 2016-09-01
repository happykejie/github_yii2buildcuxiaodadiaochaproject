<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;

ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

//初始化日志
$logHandler= new CLogFileHandler();
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';

//printf_info($order);

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<?php
require_once "models/WxJsSdk.php";
$jssdk = new WxJsSdk(WX_APPID, WX_APPSECRET);  
$signPackage = $jssdk->GetSignPackage();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>主页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/mui/css/app.css')?>
    <?=Html::cssFile('@web/web/assets/mui/css/css/wenda.css')?>


          <style>
			.title {
				margin:  20px 15px 10px;
				color: #6d6d72;
				font-size: 15px;
			}

            .cuxiao-little-img {
                width:100px;
                height:67px;
                margin-right:10px;
            }
            .mui-segmented-control>a:last-child
            {
              width:5px;

            }


		</style>

</head>
<body>
    <div id="mui-wrap" class="mui-content" style="overflow-y: auto; position: relative; ">
        <div id="s">
            <div style="padding: 10px 10px 10px 10px;">
                <div class="mui-col-xs-12 ">
                   
                        <div id="segmentedControl" class="mui-segmented-control ">
                            <a class="mui-control-item mui-active" href="#item1">今日</a>
                            <a class="mui-control-item" href="#item2">明日</a>
                            <a class="mui-control-item" href="#item3">预告</a>
                            <a class="mui-control-item" href="#item4">热门</a>
                            <a class="mui-control-item" href="#item5">大型活动</a>
                        </div>
             
                  
                </div>
            </div>
            <div>
                <!-- 全部-->
                <div id="item1" class="mui-control-content mui-active">
                    <ul id="wenda-ul" class="wenda-ul mui-table-view mui-table-view-striped mui-table-view-condensed">
                      

                       <?php if(count($items)>0):?>
                <?php foreach($items as $v):?>


                <li class="mui-table-view-cell mui-media">
					<a href="/wenda/cuxiao/detail?id=<?=$v->id?>">
						<img class="cuxiao-little-img !important  mui-pull-left" src="<?=$v->surface?>">
						<div class="mui-media-body">
							<?=$v->name?>
							<p class='mui-ellipsis'><?=$v->rule?></p>
						</div>
					</a>
				</li>


              

                <?endforeach?>
                <?endif?>

                        <?php if(count($items)<=0&&!isset($askone)):?>
                        <li style="background-color: #efeff4;">
                            <div style="margin-top: 30px;">
                                <div class="face">
                                    <img src="/web/assets/mui/images/face.png" />
                                </div>
                                <p class="remind-text">您暂时还没有信息哦！</p>
                                <div class="mui-button-row">
                                    <a href="/wenda/lookforpeople/lookforpeople">
                                        <button type="button" class="mui-btn-primary remind-button">去发布</button>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?endif?>
                    </ul>
                </div>
                <!-- 备孕-->
                <div id="item2" class="mui-control-content">
                    <ul class="wenda-ul mui-table-view mui-table-view-striped mui-table-view-condensed">
                         <?php if(count($items)>0):?>
                <?php foreach($items as $v):?>


                <li class="mui-table-view-cell mui-media">
					<a href="/wenda/cuxiao/detail?id=<?=$v->id?>">
						<img class="cuxiao-little-img !important  mui-pull-left" src="<?=$v->surface?>">
						<div class="mui-media-body">
							<?=$v->name?>
							<p class='mui-ellipsis'><?=$v->rule?></p>
						</div>
					</a>
				</li>


              

                <?endforeach?>
                <?endif?>

                        <?php if(count($items)<=0&&!isset($askone)):?>
                        <li style="background-color: #efeff4;">
                            <div style="margin-top: 30px;">
                                <div class="face">
                                    <img src="/web/assets/mui/images/face.png" />
                                </div>
                                <p class="remind-text">您暂时还没有信息哦！</p>
                                <div class="mui-button-row">
                                    <a href="/wenda/lookforpeople/lookforpeople">
                                        <button type="button" class="mui-btn-primary remind-button">去发布</button>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?endif?>
                    </ul>
                </div>
                <!-- 孕期-->
                <div id="item3" class="mui-control-content">
                    <ul class="wenda-ul mui-table-view mui-table-view-striped mui-table-view-condensed">
                         <?php if(count($items)>0):?>
                <?php foreach($items as $v):?>


                <li class="mui-table-view-cell mui-media">
					<a href="/wenda/cuxiao/detail?id=<?=$v->id?>">
						<img class="cuxiao-little-img !important  mui-pull-left" src="<?=$v->surface?>">
						<div class="mui-media-body">
							<?=$v->name?>
							<p class='mui-ellipsis'><?=$v->rule?></p>
						</div>
					</a>
				</li>


              

                <?endforeach?>
                <?endif?>

                        <?php if(count($items)<=0&&!isset($askone)):?>
                        <li style="background-color: #efeff4;">
                            <div style="margin-top: 30px;">
                                <div class="face">
                                    <img src="/web/assets/mui/images/face.png" />
                                </div>
                                <p class="remind-text">您暂时还没有信息哦！</p>
                                <div class="mui-button-row">
                                    <a href="/wenda/lookforpeople/lookforpeople">
                                        <button type="button" class="mui-btn-primary remind-button">去发布</button>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?endif?>
                    </ul>
                </div>
                <!-- 产后-->
                <div id="item4" class="mui-control-content">
                    <ul class="wenda-ul mui-table-view mui-table-view-striped mui-table-view-condensed">
                        <?php if(count($items)>0):?>
                <?php foreach($items as $v):?>


                <li class="mui-table-view-cell mui-media">
					<a href="/wenda/cuxiao/detail?id=<?=$v->id?>">
						<img class="cuxiao-little-img !important  mui-pull-left" src="<?=$v->surface?>">
						<div class="mui-media-body">
							<?=$v->name?>
							<p class='mui-ellipsis'><?=$v->rule?></p>
						</div>
					</a>
				</li>


              

                <?endforeach?>
                <?endif?>

                        <?php if(count($items)<=0&&!isset($askone)):?>
                        <li style="background-color: #efeff4;">
                            <div style="margin-top: 30px;">
                                <div class="face">
                                    <img src="/web/assets/mui/images/face.png" />
                                </div>
                                <p class="remind-text">您暂时还没有信息哦！</p>
                                <div class="mui-button-row">
                                    <a href="/wenda/lookforpeople/lookforpeople">
                                        <button type="button" class="mui-btn-primary remind-button">去发布</button>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?endif?>
                    </ul>
                </div>
                <!-- 婴幼-->
                <div id="item5" class="mui-control-content">
                    <ul class="wenda-ul mui-table-view mui-table-view-striped mui-table-view-condensed">
                         <?php if(count($items)>0):?>
                <?php foreach($items as $v):?>


                <li class="mui-table-view-cell mui-media">
					<a href="/wenda/cuxiao/detail?id=<?=$v->id?>">
						<img class="cuxiao-little-img !important  mui-pull-left" src="<?=$v->surface?>">
						<div class="mui-media-body">
							<?=$v->name?>
							<p class='mui-ellipsis'><?=$v->rule?></p>
						</div>
					</a>
				</li>


              

                <?endforeach?>
                <?endif?>

                        <?php if(count($items)<=0&&!isset($askone)):?>
                        <li style="background-color: #efeff4;">
                            <div style="margin-top: 30px;">
                                <div class="face">
                                    <img src="/web/assets/mui/images/face.png" />
                                </div>
                                <p class="remind-text">您暂时还没有信息哦！</p>
                                <div class="mui-button-row">
                                    <a href="/wenda/lookforpeople/lookforpeople">
                                        <button type="button" class="mui-btn-primary remind-button">去发布</button>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?endif?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
    <script type="text/javascript">
        mui.init({
            swipeBack: true, //
            tap: true
        });

        /*mui.ready(function(){
        var h = window.innerHeight
        var x = h-40
        x += "px !important"
        console.log(x)
        document.getElementById("mui-wrap").style.height = x 
        console.log($("#mui-wrap").css("height"))
        $("#mui-wrap").css("height",x)
        console.log($("#mui-wrap").css("height"))
        })*/
    </script>


      	<!--Start 引入分享功能-->
	<?php 
    require(BASE_PATH.'/config/wxfxjs1.php'); ///引入微信分享
    ?> 
    <!--End 结束分享功能-->

</body>
</html>
