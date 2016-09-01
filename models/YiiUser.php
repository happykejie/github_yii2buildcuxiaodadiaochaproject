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




}
