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
class Answerquestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%answerquestion}}';
    }
    
    //根据问题Id获取回答表详细信息(后台爱听列表使用)
    public function getAnswerquestion()
    {
        $model =Answerquestion::findOne(['askquestionid'=>$this->id]);
        if (!isset($model))
        {
            $model=new Answerquestion();
            $model->answertime='';
        }
        
        
        return $model;
    }
    
    
    
    /**
     * 根据问题ID获取问题详细信息
     */ 
    public function askproblemone()
    {
        $model=  Askproblem ::findOne(['id'=>$this->askquestionid]);
        return $model;
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[  'answercontent', 'answertime'], 'required'],
            [['id', 'askquestionid','answertimelength'], 'integer'],
            [['answercontent','remark'], 'string'],
            [['remark'], 'string', 'max' =>225]
        ];
    }
    
    
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '回答问题Id',
            'askquestionid' => '提问内容Id',
            'answercontent' => '回答内容',
            'answertimelength' => '回答时长',
            'answertime' => '回答时间',
            'answerispass' => '回答是否通过',
            'remark' => '备注',
        ];
    }
}
