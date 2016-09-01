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
class Userattention extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%userattention}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [[ 'attentiontime'], 'required'],
            [['userid','attentionuserid','id'], 'integer'],
        
        ];
    }

    /**
     * ����Id��ȡ����ע��ʦ����ϸ��Ϣ
     */ 
    public function GetTeacher()
    {
        $id=$this->attentionuserid;
        $model=User::findOne(['id'=>$id,'userstate'=>1]);
        if (!isset($model))
        {
        	$model=new User();
            $model->nickname="";
        }
        
        return $model;
        
    }
    
  
    
    /**
     * ����ID��ȡ��ע��ʦ���û���ϸ��Ϣ
     */ 
    public function getuser()
    {
        
        $model=User::findOne(['id'=>$this->userid]);
		
		 if (!isset($model))
        {
        	$model=new User();
            $model->nickname="";
        }
		
        return $model;
        
    }
    
    
    
    
    /**
     * ��ȡ��ǰ�û��Ƿ��ע
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
             'userid' => '�û�',
              'attentionuserid' => '��ע���û�',
               'attentiontime' => '��עʱ��',
        ];
    }
}
