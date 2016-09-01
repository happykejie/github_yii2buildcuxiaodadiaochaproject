<?php

namespace app\models;
use yii\web\UploadedFile;
use Yii;

/**
 * This is the model class for table "{{%activity}}".
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
class Activity extends \yii\db\ActiveRecord
{
    public $homepictures=[];
    public $newspictures=[];
    public $surface_file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           [['name','group_id','start_time','end_time'], 'required'],
            //[['group_id'], 'integer'],
            [['group_id'], 'integer'],
            [['name','start_time','end_time','intro','rule','rule2','sponsor','belongarea'], 'string'],
            [['name'], 'string', 'max' => 225],
            [['surface','belongarea'], 'required'],
            [['surface_file'], 'file','maxFiles' => 1, 'skipOnEmpty' => true, 'extensions' => 'png, jpg,jpeg'],
            [['homepictures'], 'file','maxFiles' => 5, 'skipOnEmpty' => true, 'extensions' => 'png, jpg,jpeg'],
            [['newspictures'], 'file','maxFiles' => 3, 'skipOnEmpty' => true, 'extensions' => 'png, jpg,jpeg'],
        ];
    }

    public function getGroup(){
        if($this->group_id){
            $group=  Category::findOne(['id'=>$this->group_id]);
            if(!$group){
                $group= new Category();
            }
            return $group;
        }else{
            return new Category();
        }
    }
    
    public function fileInput($file)
    {
        $files=[];
        if(is_array($file)){
            foreach ($file as $f) {
                $image =  $f;
                $ext = $image->getExtension();
                
                $randnum =time() . rand(1000, 9999);
                
                $randName = $randnum . "." . $ext;
                $rootPath = 'upload/';
                if (!file_exists($rootPath)) {
                    mkdir($rootPath,true);
                }
                 $rebool =   $image->saveAs($rootPath . $randName); 
                $path=  '/'.$rootPath.$randName;
                
                //图片压缩：
                
                $test =APP_ROOT;
                
                $jdpath =APP_ROOT.'\\upload\\'.$randName;
                
                if($ext=='jpg')
                {
                $im=imagecreatefromjpeg($jdpath);//参数是图片的存方路径
                 $maxwidth="600";//设置图片的最大宽度
                $maxheight="400";//设置图片的最大高度
                $name=$randnum;//图片的名称，随便取吧
                $filetype=$ext;//图片类型
                $this->resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
                    
                }
                
                if($ext=='png')
                {
                    $im=imagecreatefrompng($jdpath);//参数是图片的存方路径
                 $maxwidth="600";//设置图片的最大宽度
                $maxheight="400";//设置图片的最大高度
                $name=$randnum;//图片的名称，随便取吧
                $filetype=$ext;//图片类型
                $this->resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
                }
                
                
                
               
                
                
                array_push($files,$path);
            }
            return $files;
        }else{
            $image =  $file;
            $ext = $image->getExtension();
            $randnum =time() . rand(1000, 9999);
            
            $randName = $randnum . "." . $ext;
          
            $rootPath = 'upload/';
            if (!file_exists($rootPath)) {
                mkdir($rootPath,true);
            }
            $image->saveAs($rootPath . $randName);
            $path=  '/'.$rootPath.$randName;
            
            
            //图片压缩：
            
            $test =APP_ROOT;
            
            $jdpath =APP_ROOT.'\\upload\\'.$randName;
            
            if($ext=='jpg')
            {
                $im=imagecreatefromjpeg($jdpath);//参数是图片的存方路径
                $maxwidth="600";//设置图片的最大宽度
                $maxheight="400";//设置图片的最大高度
                $name=$randnum;//图片的名称，随便取吧
                $filetype=$ext;//图片类型
                $this->resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
                
            }
            
            if($ext=='png')
            {
                $im=imagecreatefrompng($jdpath);//参数是图片的存方路径
                $maxwidth="600";//设置图片的最大宽度
                $maxheight="400";//设置图片的最大高度
                $name=$randnum;//图片的名称，随便取吧
                $filetype=$ext;//图片类型
                $this->resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
            }
            
            
            return $path;
        }
        return $files;
    }
    
    
    

 public  function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
 {
  $pic_width = imagesx($im);
  $pic_height = imagesy($im);
 
  if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
  {
   if($maxwidth || $pic_width>$maxwidth)
   {
    $widthratio = $maxwidth/$pic_width;
    $resizewidth_tag = true;
   }
 
   if($maxheight || $pic_height>$maxheight)
   {
    $heightratio = $maxheight/$pic_height;
    $resizeheight_tag = true;
   }
 
   if($resizewidth_tag && $resizeheight_tag)
   {
    if($widthratio<$heightratio)
     $ratio = $widthratio;
    else
     $ratio = $heightratio;
   }
 
   if($resizewidth_tag && !$resizeheight_tag)
    $ratio = $widthratio;
   if($resizeheight_tag && !$resizewidth_tag)
    $ratio = $heightratio;
 
   $newwidth = $pic_width * $ratio;
   $newheight = $pic_height * $ratio;
 
   if(function_exists("imagecopyresampled"))
   {
    $newim = imagecreatetruecolor($newwidth,$newheight);//PHP系统函数
     $result1 =  imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP系统函数
   }
   else
   {
    $newim = imagecreate($newwidth,$newheight);
     $result2= imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
   }
 
   $name = 'upload/'.$name.'.'.$filetype;
   $result3 = imagejpeg($newim,$name);
   imagedestroy($newim);
  }
  else
  {
   $name = $name.$filetype;
   imagejpeg($im,$name);
  }
 }
 
 

    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '活动名称',
            'group_id' => '组别',
            'belongarea' => '所属城市',
            'start_time' => '起始时间',
            'end_time' => '截止日期',
            'intro'=>'活动介绍',
            'rule' => '促销地点',
            'rule2' => '联系方式',
            'sponsor'=>'主办方',
            'publish'=>'是否发布',
            'surface' => '封面',
            'surface_file' => '封面',
            'homepictures'=>'首页图片',
            'newspictures'=>'精彩报道(最多三张)',
        ];
    }
    
}
