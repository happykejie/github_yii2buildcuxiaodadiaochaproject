<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $user
 * @property string $pwd
 */
class YiiUser extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
         
        
            [['userorder','phone'], 'integer'],
            [['description','locationcity'], 'string'],
            [['title'], 'string', 'max' =>225]
        ];
    }
    

    /**
     * 获取当前用户回答了多少个问题
     */ 
    public function askproblemnumber()
    {
        $model=User::findOne(['id'=>$this->id,'userstate'=>1]);
        if (isset($model))
        {
            $model=  Askproblem::find()->where(['answerpersonid'=>$this->id]);
            return $model;
        }else
        {
            return  1111;
        }
    }
    
    /**
     * 获取当前用户获取被关注次数
     */ 
    public function attentionnumber()
    {
        $model=User::findOne(['id'=>$this->id,'userstate'=>1]);
        if (isset($model))
        {
            $model=  Userattention ::find()->where(['attentionuserid'=>$this->id]);
            return $model;
        }else
        {
            return  1111;
        }
    }
    
    
    /**
     * @
     */
    public static function findIdentity($id)
    {
        //自动登陆时会调用
        $temp = parent::find()->where(['id'=>$id])->one();
        return isset($temp)?new static($temp):null;
    }

    /**
     * @
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * @
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *@
     */

    public  function  getUser(){
        return $this->user;
    }

    /**
     * @
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * @
     */
    public function validatePassword($password)
    {
        return $this->pwd === $password;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户Id',
            'username' => '用户姓名',
            'usercreatetime' => '用户注册时间',
            'useraccount' => '用户帐号',
            'userrole' => '用户角色',
            'title' => '用户的头衔',
            'userstate'=>'用户状态',
            'description' => '老师的介绍',
            'phone' => '用户的电话',
            'questionprice' => '用户提问的价格',
            'createteachertime' => '成为用户的时间',
            'userorder' => '排序',
            'isenable'=>'是否启用',
            'openid'=>'Openid',
            'scope'=>'scope',
            'nickname'=>'昵称',
            'sex'=>'性别',
            'city'=>'城市',
            'country'=>'国家',
            'headimgurl'=>'头像',
            'remark'=>'备注',
            'realname'=>'真实姓名',
            'qqnum'=>'QQ号码',
            'belongfirm'=>'所属机构',
            'belongfirmphone'=>'所属机构电话',
            
            'locationcity'=>'定位城市',
            
            
            
        ];
    }




}
