<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%msg}}".
 *
 * @property integer $id
 * @property integer $fid
 * @property integer $tid
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $send_time
 * @property integer $replay
 */
class Askproblem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%askproblem}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'questiondescription'], 'required'],
            [['answerpersonid','questionorder','categoryid','isopenask','isfree'], 'integer'],
            [['questiondescription','remark'], 'string'],
            [['remark'], 'string', 'max' => 225]
            
        ];
    }

    /**
     * @得到提问人信息
     */
    public function getUser()
    {
        $model =User::findOne(['id'=>$this->askpersonid]);
        return $model;
    }
    
    /**
     * @得到问题回答人信息
     */
    public function getUseranswer()
    {
        $User =User::findOne(['id'=>$this->answerpersonid]);
        
        if (!isset($User))
        {
        	$User=new User();
            $User->nickname="";
            $User->headimgurl='';
            $User->id=0;
            
        }
        return $User;
    }
    
	 /**
     * @判断问题是否听过
     */
    public function getLove()
    {
        $userid =Yii::$app->user->getId();
        $lovelistenquestion=new  lovelistenquestion();
        $lovelistenquestion =$lovelistenquestion::findOne(['userid'=>$userid,'questionid'=>$this->id]);
        
        
        return $lovelistenquestion;
    }
	
	
    //根据问题Id获取回答表详细信息(后台爱听列表使用)
    public function getAnswerquestion()
    {
        $model =Answerquestion::findOne(['askquestionid'=>$this->id]);
        if (!isset($model))
        {
            $model=new Answerquestion();
            $model->answertime='';
            $model->answertimelength=0;
           $model->answercontent="";
        }
        return $model;
    }
    
    //根据问题Id获取回答的总长度
    public function getAnswerquestionsum()
    {
        $model =Answerquestion::find()->where(['askquestionid'=>$this->id])->sum('answertimelength');
        
        return $model;
    }
    
    
    //根据问题Id获取回答表详细信息(后台爱听列表使用)
    public function getAnswerquestions()
    {
        $model =Answerquestion::findAll(['askquestionid'=>$this->id]);
        if (!isset($model))
        {
            $model=new Answerquestion();
            $model->answertime='';
        }
        return $model;
    }
    
    
    //根据问题Id获取爱听的详细信息
    public function getLovelistenquestion()
    {
        $model =LoveListenQuestion::find()->where(['questionid'=>$this->id])->all();
        return $model;
    }
    
    //根据问题的分类ID获取 分类信息
    public function getCategory()
    {
        $model =Category::findOne(['id'=>$this->categoryid]);
        if (isset($model))
        {
            return $model;
        }else
        {
            $model=new Category();
            $model->categoryname="";
            return $model;
        }
    }
    
    
    //根据问题Id获取爱听的详细信息
    public function Top()
    {
        $model =LoveListenQuestion::find()->where(['questionid'=>$this->id])->all();
        return $model;
    }
    
    public function getKeyTypeDropListIsfree(){
        $rets=array();;
        $rets['1']='是';
        $rets['0']='否';
        return $rets;
    }
    
    //判断当前问题，当前用户是否购买
    public function getIsPaywenda()
    {
        
        //判断当前用户是否购买
        $id =  Yii::$app->user->getId();
        $model =LoveListenQuestion::find()->where(['questionid'=>$this->id,'userid'=>$id])->all();
        if (count($model)>0)
        {
            return true;
        }
        
        //判断该问题是是否是当前用户提问
        if($id ==$this->answerpersonid)
        {
            return true;
        }
        
        //判断该问题是是否是当前用户回答
        if($id ==$this->askpersonid)
        {
            return true;
        }
        
        //判断改问题是否是0元
        if($this->askfee==0)
        {
            return true;
        }
        return false;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'questiondescription' => '问题描述',
            'answerpersonid' => '回答人Id',
            'asktime' => '提问时间：',
            'ispaysuccess' => '支付是否成功：',
            'askfee' => '提问费用',
            'isanswer' => '问题是否回答',
            'questionorder' => '问题排行',
            'remark' => '备注',
            'categoryid' => '问题分类',
            'isfree'=>'是否免费',
            'modifytime'=>'修改时间',
        ];
    }
}
