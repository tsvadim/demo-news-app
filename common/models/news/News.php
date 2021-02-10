<?php

namespace common\models\news;

use common\models\Tree;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_text
 * @property string|null $text
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property NewsTree[] $newsTrees
 * @property Tree[] $trees
 */
class News extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class
            ],
            'SluggableBehavior' => [
                'class' => SluggableBehavior::class,
                'ensureUnique' => true,
                'attribute' => "name",
                'slugAttribute' => 'slug',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','short_text', 'text'], 'filter','filter'=>'strip_tags'],
            [['name','short_text', 'text'], 'filter','filter'=>'trim'],
            [['short_text', 'text'], 'string'],
            [['status'], 'integer'],
            [['name'],'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'short_text' => Yii::t('app', 'Short Text'),
            'text' => Yii::t('app', 'Text'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[NewsTrees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsTrees()
    {
        return $this->hasMany(NewsTree::className(), ['news_id' => 'id']);
    }

    /**
     * Gets query for [[Trees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrees()
    {
        return $this->hasMany(Tree::className(), ['id' => 'tree_id'])->viaTable('{{%news_tree}}', ['news_id' => 'id']);
    }

    /**
     * Statuses
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE  => Yii::t('app', 'Actively'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactively'),
        ];
    }

    /**
     * Active Status label
     * @return mixed
     * @throws \Exception
     */
    public function getStatusLabel()
    {
        $statuses = self::getStatuses();
        return ArrayHelper::getValue($statuses, $this->status);
    }
}
