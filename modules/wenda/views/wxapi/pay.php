<?php 
use yii\helpers\Url;
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

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>微信支付</title>

    <script type="text/javascript">

        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
			function(res){
			    if(res.err_msg=='get_brand_wcpay_request:ok'){
			        alert('恭喜您，支付成功!');
			    }else{
			        WeixinJSBridge.log(res.err_msg);
			        //支付成功后执行
			        alert('支付失败：'+res.err_code+res.err_desc+res.err_msg);
			    }
			}
		);
        }

        function callpay()
        {
            debugger;
            alert("ss");
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>

    <script type="text/javascript">
        //获取共享地址
        function editAddress()
        {
            //WeixinJSBridge.invoke(
            //    'editAddress',
            //    <?php echo $editAddress; ?>,
			//function(res){
			//    var value1 = res.proviceFirstStageName;
			//    var value2 = res.addressCitySecondStageName;
			//    var value3 = res.addressCountiesThirdStageName;
			//    var value4 = res.addressDetailInfo;
			//    var tel = res.telNumber;
			//	
			//    alert(value1 + value2 + value3 + value4 + ":" + tel);
			//}
		    //);
        }
	
        window.onload = function(){
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', editAddress, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', editAddress); 
                    document.attachEvent('onWeixinJSBridgeReady', editAddress);
                }
            }else{
                editAddress();
            }
        };
	
    </script>
</head>
<body>
    <div align="center">
        <button style="width: 210px; height: 50px; border-radius: 15px; background-color: #FE6714; border: 0px #FE6714 solid; cursor: pointer; color: white; font-size: 16px;" type="button" onclick="callpay()">立即支付</button>
    </div>
</body>
</html>
