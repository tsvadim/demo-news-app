<?php
/**
 * @copyright Copyright (c) 2021 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 10.02.2021
 */

namespace frontend\modules\api\models;


use common\models\Tree;

/**
 *@OA\Schema(
 *  schema="Category",
 *  @OA\Property(
 *     property="name",
 *     type="string",
 *     description="Name"
 *  ),
 *  @OA\Property(
 *     property="lvl",
 *     type="integer",
 *     description="Level"
 *  ),
 *  @OA\Property(
 *     property="children",
 *     type="integer",
 *     format="array",
 *     description="Children"
 *  )
 *)
 */
class Category extends Tree
{

    public function fields()
    {
        return ['id', 'name', 'lft', 'rgt','lvl','children'];
    }


    public function extraFields()
    {
        return ['children'];
    }

    public function getChildren()
    {
        return $this->children(1)->all();
    }
}