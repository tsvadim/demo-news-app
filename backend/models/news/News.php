<?php
/**
 * @copyright Copyright (c) 2021 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 10.02.2021
 */

namespace backend\models\news;


use common\models\news\NewsTree;
use yii\helpers\ArrayHelper;

class News extends \common\models\news\News
{
    protected $_inputTree;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['inputTree'], 'string'];
        return $rules;
    }

    public function attributeLabels()
    {
        $items = parent::attributeLabels();
        $items['statusLabel'] = \Yii::t('app', "Status");
        return $items;
    }


    /**
     * @return mixed
     */
    public function getInputTree()
    {
        return $this->_inputTree;
    }

    /**
     * @param $inputTree
     */
    public function setInputTree($inputTree)
    {
        $this->_inputTree = $inputTree;
    }

    /**
     * @return string
     */
    public function loadInputTree()
    {
       $trees = ArrayHelper::getColumn($this->newsTrees, 'tree_id');
       return implode(",",$trees);
    }

    /**
     * @return bool
     */
    public function saveInputTree()
    {
        $tree = $this->getInputTree();
        $items = explode(",", $tree);
        \Yii::debug($items);
        NewsTree::deleteAll(['news_id'=>$this->id]);
        foreach ($items as $item){
            $ntModel = new NewsTree();
            if($ntModel->load(['tree_id'=>$item,'news_id'=>$this->id],'')){
                if (!$ntModel->save()){

                    \Yii::error($ntModel->getFirstErrors());
                    return false;
                }
            }
        }
        return true;
    }
}