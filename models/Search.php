<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\YiiUser;

class Search extends Model{
    public  $nickname;
    public  $questiondescription;
    public  $attentionname;
    public  $answernname;
    
    public function rules(){

        return [
            [['questiondescription','nickname','attentionname','answernname'],'string'],
            ['nickname', 'string', 'max' => 20 ],
        ];
    }
    /**
     * @
     */
    public function attributeLabels()
    {
        return [
            'nickname' => '用户名称',
            'questiondescription' => '问题描述',
            'attentionname'=>'被关注用户名称',
            'answernname'=>'回答人',
        ];
    }

    
    
    public function getStatuDropListModel(){
        $rets=array();
        $rets['-1']='全部';
        $rets['0']='审核通过';
        $rets['1']='待审核';
        return $rets;
    }
    public function getTypeDropListModel(){
        $rets=array();
        $rets['-1']='全部';
        $rets['0']='线上报名';
        $rets['1']='线下报名';
        return $rets;
    }
    
    public function getKeyTypeDropListModel(){
        $rets=array();;
        $rets['0']='名称';
        $rets['1']='电话';
        return $rets;
    }
}
?>