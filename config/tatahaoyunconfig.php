<?php

/**
 * tatahaoyunconfig short summary.
 *
 * tatahaoyunconfig description.
 *
 * @version 1.0
 * @author Administrator
 */
define('Wx_Title','她他好孕');
define('WX_APPID','wxe0aa7e37f44b94c5'); //他她好运wxe0aa7e37f44b94c5  
define('WX_APPSECRET','0f30d30e4ace88ea0b0bf0ef89207a96');  ///他她好运0f30d30e4ace88ea0b0bf0ef89207a96   


define("CURR_DOMAIN",'tatahaoyun.com');//配置当前域名

define("DOMAITDESC",'她他好孕');

define("YHTWTZ",'ukXQLZYhDuYPsrRX-Dl-UTRcZjPRWMHLnR8hY4BE8U4'); // 她他好孕  用户提问通知

define("SDHFTZ",'TZlkrkfrMB2tbszgMTP5hgx3J5BblJgkhw8nKrTe7l0'); // 她他好孕  收到回复通知

define("TKTZ",'QC2-s6x1dc9O9OIqOfdwx2h8HxSbYGOkCMsc7hJxKeg'); // 她他好孕  退款通知

define("JJGQTX",'KfZXLeiyiSAYbVbcxWrRCtGopRNxfxr_LgLgTK124kw'); // 她他好孕  即将过期提醒



///微信分享配置

define("WXFX_TITLE",'她他好孕'); // 她他好孕  微信分享——标题

define("WXFX_DESC",'她他好孕：专业的平台,专业的问题,专业的回答'); // 她他好孕  微信分享——分享描述

define("WXFX_LINK",'http://www.tatahaoyun.com'); // 她他好孕  微信分享详细地址

define("WXFX_IMGPATH",'http://www.tatahaoyun.com/web/images/getheadimg.jpg'); // 她他好孕  微信分享图地址片

//默认官网
define("HTTPWWW",'http://www.tatahaoyun.com'); // 她他好孕  

//默认官网
define("WWW",'www.tatahaoyun.com'); // 她他好孕  






//////////////////////////微信支付开始////////////////////////////////////////////////////////////

//=======【基本信息设置】=====================================
//
/**
 * TODO: 修改这里配置为您自己申请的商户信息
 * 微信公众号信息配置
 * 
 * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
 * 
 * MCHID：商户号（必须配置，开户邮件中可查看）
 * 
 * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
 * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
 * 
 * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
 * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
 * @var string
 */


define('APPID','wxe0aa7e37f44b94c5');
define('MCHID','1345744301');
define('KEY','0f20d16e4ace88ea0b0bf0ef89207a96');
define('APPSECRET','0f30d30e4ace88ea0b0bf0ef89207a96');




//=======【证书路径设置】=====================================
/**
 * TODO：设置商户证书路径
 * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
 * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
 * @var path
 */


define('SSLCERT_PATH','../cert/apiclient_cert.pem');
define('SSLKEY_PATH','../cert/apiclient_key.pem');
define('SSLCERTP12_PATH','../cert/apiclient_cer.p12');
define('SSLROOTCA','../cert/rootca.pem');


////证书完整路径

define('SSLCERT_PATHALL','/vendor/wxpayapi/tthycert/apiclient_cert.pem');
define('SSLKEY_PATHALL','/vendor/wxpayapi/tthycert/apiclient_key.pem');
define('SSLCERTP12_PATHALL','/vendor/wxpayapi/tthycert/apiclient_cer.p12');
define('SSLROOTCAALL','/vendor/wxpayapi/tthycert/rootca.pem');




//=======【curl代理设置】===================================
/**
 * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
 * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
 * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
 * @var unknown_type
 */
const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
const CURL_PROXY_PORT = 0;//8080;

//=======【上报信息配置】===================================
/**
 * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
 * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
 * 开启错误上报。
 * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
 * @var int
 */
const REPORT_LEVENL = 1;
////////////////////////////////////////////微信支付配置结束////////////////////////////////////////////////////////////////////////////


////////////////////////////////支付订单////////////////////////////////////////

define('ORDERBODY','她他好孕问答');
define('ORDERTAG','她他好孕问答');
define('ORDERATTACH','她他好孕问答');