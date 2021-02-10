<?php
/**
 * @copyright Copyright (c) 2021 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 10.02.2021
 */

namespace frontend\modules\api\models;

/**
 *@OA\Schema(
 *  schema="News",
 *  @OA\Property(
 *     property="name",
 *     type="string",
 *     description="Name"
 *  ),
 *  @OA\Property(
 *     property="short_text",
 *     type="integer",
 *     description="Short Text"
 *  ),
 *  @OA\Property(
 *     property="text",
 *     type="integer",
 *     description="Text"
 *  ),
 *  @OA\Property(
 *     property="trees",
 *     type="integer",
 *     format="array",
 *     description="Categories"
 *  )
 *)
 */
class News extends \common\models\news\News
{
    public function fields()
    {
        return ['id', 'name', 'short_text', 'text','categories'];
    }

    /**
     * Gets query for [[Trees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'tree_id'])->viaTable('{{%news_tree}}', ['news_id' => 'id']);
    }
}