<?php
/**
 * @copyright Copyright (c) 2021 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 05.02.2021
 */

namespace common\models;


use Yii;

use kartik\tree\models\TreeTrait;

/**
 * {@inheritDoc}
 */
class Tree extends \kartik\tree\models\Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tree}}';
    }
}