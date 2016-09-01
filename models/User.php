<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use yii\web\UploadedFile;
require_once Yii::$app->basePath.'/models/Img.php';

class User extends ActiveRecord implements IdentityInterface
{
    public  $attentionname;
    //public $id;
    //public $username;
    //public $password;
    //public $authKey;
    //public $accessToken;

    /**
     * @inheritdoc 建立模型表
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
            [['description','nickname','attentionname','headimgurl','wechatnumber','explain'], 'string'],
            [['questionprice'],'double'],
            [['title'], 'string', 'max' =>225]
        ];
    }
    
	 /**
     * 获取当前用户回答了多少个问题
     */ 
    public function incomecost()
    {
        $incomecost=  Incomecost ::find()->where(['userid'=>$this->id,'incomecosttype'=>2]);
        if (!isset($incomecost))
        {
            $incomecost=  Incomecost ::find()->where(["id"=>0]); 
        }
        return $incomecost;
    }
	
    /**
     * 获取当前用户回答了多少个问题
     */ 
    public function askproblemnumber()
    {
        $model=User::findOne(['id'=>$this->id,'userstate'=>1]);
        if (isset($model))
        {
            $askproblem=  Askproblem ::find()->where(['answerpersonid'=>$this->id,'questionstate'=>1]);
            return $askproblem;
        }else
        {
            $askproblem=  Askproblem ::find()->where(["id"=>0]); 
            
            return $askproblem;
            
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
            $model=  Userattention ::find()->where(['id'=>0]);
            
            return  $model;
        }
    }
    
    
 
    
    
    /**
     * 获取当前用户 爱听总支出多少钱(表示爱听用户支出)
     */ 
    public function incomecost1mnumber()
    {
        $user=User::findOne(['id'=>$this->id]);
        if (isset($user))
        {
            $incomecost=  Incomecost ::find()->where(['userid'=>$this->id,'Incomecosttype'=>1]);
            return $incomecost;
        }else
        {
            $incomecost=  Incomecost ::find()->where(['id'=>0]);
            return  $incomecost;
        }
    }
    
    
    /**
     * 获取当前用户 爱听 提问者总收入多少钱（表示提问人收入爱听0.5 元）
     */ 
    public function incomecost2mnumber()
    {
        $user=User::find(['id'=>$this->id])->where(['userstate'=>1]);
        if (isset($user))
        {
            $incomecost=  Incomecost ::find()->where(['userid'=>$this->id,'Incomecosttype'=>2]);
            return $incomecost;
        }else
        {
            $incomecost=  Incomecost ::find()->where(['id'=>0]);
            return  $incomecost;
        }
    }
    
    
    /**
     * 获取当前用户 爱听 回答者总收入多少钱（回答人分得爱听收入0.5元）
     */ 
    public function incomecost3mnumber()
    {
        $user=User::find(['id'=>$this->id])->where(['userstate'=>1]);
        if (isset($user))
        {
            $incomecost=  Incomecost ::find()->where(['userid'=>$this->id,'Incomecosttype'=>3]);
            return $incomecost;
        }else
        {
            $incomecost=  Incomecost ::find()->where(['id'=>0]);
            return  $incomecost;
        }
    }
    
  
    
    /**
     * 获取当前用户 提问总支出多少钱
     */ 
    public function incomecost4mnumber()
    {
        $user=User::findOne(['id'=>$this->id]);
        if (isset($user))
        {
            $incomecost=  Incomecost ::find()->where(['userid'=>$this->id,'Incomecosttype'=>4]);
            return $incomecost;
        }else
        {
            $incomecost=  Incomecost ::find()->where(['id'=>0]);
            return  $incomecost;
        }
    }
    
    /**
     * 获取当前用户 回答问题总收入多少钱
     */ 
    public function incomecost5mnumber()
    {
        $user=User::find(['id'=>$this->id])->where(['or','userstate'=>1,'userstate'=>3]);
        if (isset($user))
        {
            $incomecost=  Incomecost ::find()->where(['userid'=>$this->id,'Incomecosttype'=>5]);
            return $incomecost;
        }else
        {
            $incomecost=  Incomecost ::find()->where(['id'=>0]);
            return  $incomecost;
        }
    }
    
    
    
    /**
     * 获取当前用户 提现记录
     */ 
    public function incomecost6mnumber()
    {
        $user=User::findOne(['id'=>$this->id]);
        if (isset($user))
        {
            $incomecost=  Incomecost ::find()->where(['userid'=>$this->id,'Incomecosttype'=>6]);
            return $incomecost;
        }else
        {
            $incomecost=  Incomecost ::find()->where(['id'=>0]);
            return  $incomecost;
        }
    }
    
    
    /**
     * @我未回答的问题数量
     */
    public function Myanswer(){
        
        //获取我还没有回答的问题
        $answerno =Askproblem::find()->where(['answerpersonid'=>$this->id,'questionstate'=>0])->orderBy('asktime DESC')->all();
        return $answerno;
    }
    
    /**
     * @我的提问还未回答数量
     */

    public function Myquestion(){
        
        //获取我还没有回答的问题
        $askproblemno =Askproblem::find()->where(['askpersonid'=>$this->id,'questionstate'=>0])->orderBy('asktime DESC')->all();
        return $askproblemno;
    }
    
    
    public function fileInput($file)
    {
        $files=[];
        if(is_array($file)){
            foreach ($file as $f) {
                $image =  $f;
                $ext = $image->getExtension();
                $randName = time() . rand(1000, 9999) . "." . $ext; 
                $rootPath = 'avatar/';
                if (!file_exists($rootPath)) {
                    mkdir($rootPath,true);
                }
                $image->saveAs($rootPath . $randName); 
                $path=  '/'.$rootPath.$randName;
                
                array_push($files,$path);
            }
            return $files;
        }else{
            $image =  $file;
            $ext = $image->getExtension();
            $randName = time() . rand(1000, 9999) . "." . $ext;
            $path = abs(crc32($randName) % 500);
            $rootPath = 'avatar/';
            if (!file_exists($rootPath)) {
                mkdir($rootPath,true);
            }
            $imgPath = $rootPath . $randName;
            $image->saveAs($imgPath);
            
            //$img =new \Img();
            //$img->resize_image($imgPath,'', ['width'=>'','height'=>''] );
            
            $path=  '/'.$rootPath.$randName;
            return $path;
        }
        return $files;
    }
    
    
    
    /**
     * 获取当前用户是否关注
     */ 
    public function attenuserattention()
    {
        $userid=Yii::$app->user->getId();
        
        $id=$this->id;
        
        $model=Userattention::findOne(['userid'=>$userid,'attentionuserid'=>$this->id]);
        if (isset($model))
        {
            //$model=  Userattention ::find()->where(['attentionuserid'=>$this->id]);
            return $model;
        }else
        {
            $model=new Userattention();
            $model->id=0;
            return  $model;
        }
    }
    
    
    
    
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        //自动登陆时会调用
        $temp = parent::find()->where(['id'=>$id])->one();
        return isset($temp)?new static($temp):null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['nickname'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @username
     */
    public function getUser()
    {
        return $this->nickname;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
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
            'nickname' => '用户姓名',
            'usercreatetime' => '用户注册时间',
            'useraccount' => '用户帐号',
            'userrole' => '用户角色',
            'title' => '老师的头衔',
            'userstate'=>'用户状态',
            'description' => '老师的介绍',
            'phone' => '老师的电话',
            'questionprice' => '老师提问的价格',
            'createteachertime' => '成为老师的时间',
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
             'email'=>'邮箱',
            'attentionname'=>'被关注的老师名称',
            'wechatnumber'=>'微信号',
            'explain'=>'审核失败原因',
            
            
        ];
    }
}
