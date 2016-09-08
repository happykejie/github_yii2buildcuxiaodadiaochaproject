<?php

/**
 * boshizhidaoconfig short summary.
 *
 * boshizhidaoconfig description.
 *
 * @version 1.0
 * @author Administrator
 */




define('Wx_Title','促销大调查');
define('WX_APPID','wx40af8cb4c6dd1f67'); //促销大调查 wx40af8cb4c6dd1f67   
define('WX_APPSECRET','86b8c4edc4fbcc39896ab14dec449d41');  ///促销大调查86b8c4edc4fbcc39896ab14dec449d41   


define("CURR_DOMAIN",'happykejie.vicp.cc');//配置当前域名

define("DOMAITDESC",'促销大调查');

define("YHTWTZ",'KwpE1b0IyukwmfBLd18M5UxzM4pEnhq1dXqE2cOi91o'); // 促销大调查  用户提问通知

define("SDHFTZ",'vfVV009pVO4ZkXwM4C1LiJvJ1ot7HFe4x32ISvfwW7k'); // 促销大调查  收到回复通知

define("TKTZ",'xTBEu1vh5rZxRlSzL_tO90gsSai-z4HHl2ASPLeyU3k'); // 促销大调查  退款通知

define("JJGQTX",'Ik8CJmg69xjVrdduWIvemsFsLG1Lm77aljMAIyEQMCE'); // 促销大调查  即将过期提醒


///微信分享配置

define("WXFX_TITLE",'促销大调查'); // 促销大调查  微信分享——标题

define("WXFX_DESC",'促销大调查：全国最大的促销分享平台，最专业，最快捷的发布促销信息'); // 促销大调查  微信分享——分享描述

define("WXFX_LINK",'happykejie.vicp.cc'); // 促销大调查  微信分享详细地址

define("WXFX_IMGPATH",'happykejie.vicp.cc/web/images/wxlogonew.jpg'); // 促销大调查  微信分享图地址片

//默认官网
define("HTTPWWW",'happykejie.vicp.cc'); // 促销大调查  



//默认官网
define("WWW",'happykejie.vicp.cc'); // 促销大调查  




//////////////////////////微信支付////////////////////////////////////////////////////////////



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


define('APPID','wx40af8cb4c6dd1f67');
define('MCHID','1360507502');
define('KEY','86b8c4edc4fbhh39896ab14dec4kejie');
define('APPSECRET','86b8c4edc4fbcc39896ab14dec449d41');


//=======【证书路径设置】=====================================
/**
 * TODO：设置商户证书路径
 * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
 * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
 * @var path
 */

define('SSLCERT_PATH','../cxddccert/apiclient_cert.pem');
define('SSLKEY_PATH','../cxddccert/apiclient_key.pem');
define('SSLCERTP12_PATH','../cxddccert/apiclient_cer.p12');
define('SSLROOTCA','../cxddccert/rootca.pem');

////证书完整路径

define('SSLCERT_PATHALL','/vendor/wxpayapi/cxddccert/apiclient_cert.pem');
define('SSLKEY_PATHALL','/vendor/wxpayapi/cxddccert/apiclient_key.pem');
define('SSLCERTP12_PATHALL','/vendor/wxpayapi/cxddccert/apiclient_cer.p12');
define('SSLROOTCAALL','/vendor/wxpayapi/cxddccert/rootca.pem');

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
const REPORT_LEVENL = 1;//=======【基本信息设置】=====================================




////////////////////////////////支付订单////////////////////////////////////////

define('ORDERBODY','促销大调查');
define('ORDERTAG','促销大调查');
define('ORDERATTACH','促销大调查');










