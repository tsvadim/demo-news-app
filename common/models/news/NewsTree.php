<?php

namespace common\models\news;

use common\models\Tree;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%news_tree}}".
 *
 * @property int $news_id
 * @property int $tree_id
 * @property string|null $created_at
 *
 * @property News $news
 * @property Tree $tree
 */
class NewsTree extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news_tree}}';
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW(3)')
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'tree_id'], 'required'],
            [['news_id', 'tree_id'], 'integer'],
            [['created_at'], 'safe'],
            [['news_id', 'tree_id'], 'unique', 'targetAttribute' => ['news_id', 'tree_id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
            [['tree_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tree::className(), 'targetAttribute' => ['tree_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'news_id' => Yii::t('app', 'News ID'),
            'tree_id' => Yii::t('app', 'Tree ID'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[News]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

    /**
     * Gets query for [[Tree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTree()
    {
        return $this->hasOne(Tree::className(), ['id' => 'tree_id']);
    }
}
