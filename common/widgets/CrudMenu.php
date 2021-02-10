<?php
/**
 * @copyright Copyright (c) 2017 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 21.07.2017
 */

namespace common\widgets;


use Yii;
use yii\base\Widget;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;


/**
 * Виджет создания кнопок управления adminLte.Пример:
 * ```
 *    <?= common\widgets\CrudMenu::widget([
 *          'visibleButtons'=>['create'=>true,'list'=>true,'myCreate'=>true],
 *          'buttons' => [
 *              'myCreate' => [
 *                  'label' => '<i class="fa fa-thumbs-o-up"></i> <span>' . Yii::t('app', 'Like') . '</span>',
 *                  'url' => ['create'],
 *                  'encode' => false,
 *          ],
 *      ]]) ?>
 * ```
 */
class CrudMenu extends Widget
{
    /** @var \yii\db\ActiveRecord */
    public $model;

    /** @var array  Nav options*/
    public $options = [];
    /** @var array  */
    public $buttonOptions = [];

    /** @var array  */
    public $buttons = [];
    /** @deprecated string  */
    //public $template = '<div class="btn-group" role="group">{create} {list} {view} {update} {delete}</div>';

    /** @deprecated array Последовательность отображения */
    public $renderPositions = ['list','create','update','view','delete'];

    /** @var bool Разрешаем автоматическую инициализацию кнопок в зависимости от Url */
    public $autoInitButtons = true;
    /** @var string Name primary key mode */
    public $primaryKey = 'id';
    /** @var string Name primary key for url */
    public $urlPrimaryKey = 'id';

    /** @var  array Элементы меню готовые к рендеру*/
    protected $_renderItems = [];

    /** @var array visibility conditions for each button. The array keys are the button names (without curly brackets),
     * and the values are the boolean true/false or the anonymous function.
     */
    public $visibleButtons = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->options['class'] = ArrayHelper::getValue($this->options,'class','menu menu-top-main');
        $this->initDefaultButtons();
    }


    public function run()
    {
        $this->prepareButtons();
        return  Nav::widget([
            'options' => $this->options,
            'items' => $this->getRenderItems(),
        ]);
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        // $this->setRenderItems(array_merge($this->defaultButtons(),$this->buttons));
        $buttons = $this->defaultButtons();
        $this->buttons = array_merge($buttons,$this->buttons);
//        var_dump($this->buttons);
    }


    private function defaultButtons()
    {
        return [
            'list'=>[
                'label'   => '<i class="fa fa-list"></i> ' . Yii::t('app', 'List').'</span>',
                'url'     => ['index'],
                //'visible' => ((Yii::$app->controller->action->id != 'index')),
                'encode'  => false,
            ],
            'create'=> [
                'label'   => '<i class="fa fa-plus"></i> <span>' . Yii::t('app', 'Create').'</span>',
                'url'     => ['create'],
                'linkOptions'=>['class'=>"btn btn-flat bg-olive"],
//                'visible' => ((Yii::$app->controller->action->id != 'create')),
                'encode'  => false,

            ],
            'update'=> [
                'label'   => '<i class="fa fa-pencil"></i> <span>' . Yii::t('app', 'Update').'</span>',
                'url'     => ['update',$this->urlPrimaryKey=>(isset($this->model)?$this->model->{$this->primaryKey}:'')],
//                'visible' => ((Yii::$app->controller->action->id != 'update') && (isset($this->model) && isset($this->model->id))),
                'encode'  => false,

            ],
            'view'=> [
                'label'   => '<i class="fa fa-eye"></i> <span>' . Yii::t('app', 'View').'</span>',
                'url'     => ['view',$this->urlPrimaryKey=>(isset($this->model)?$this->model->{$this->primaryKey}:'')],
//                'visible' => ((Yii::$app->controller->action->id != 'list') && (Yii::$app->controller->action->id != 'view') && (Yii::$app->controller->action->id != 'create') && (isset($this->model) && isset($this->model->id))),
                'encode'  => false,

            ],
            'delete'=> [
                'label'   => '<i class="fa fa-trash"></i> <span>' . Yii::t('app', 'Delete').'</span>',
                'url'     => ['delete',$this->urlPrimaryKey=>((isset($this->model) && isset($this->model->{$this->primaryKey}))?$this->model->{$this->primaryKey}:'')],
                'linkOptions' => [
                    'data-method' => 'post',
                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                ],
//                'visible' => ((isset($this->model) && isset($this->model->id))),
                'encode'  => false,
            ],
        ];
    }

    private function prepareButtons()
    {
        /** @var Controller $controller */
        $controller = Yii::$app->controller;
        if (empty($this->visibleButtons) && $this->autoInitButtons===true){
            //Недоступно на главной странице
            if(($controller->action->id != 'index')){
                $this->visibleButtons['list'] = true;
            }
            //Недоступно на странице добавления
            if(($controller->action->id != 'create')){
                $this->visibleButtons['create'] = true;
            }

            $modelIsValid = ((isset($this->model) && $this->model->isNewRecord === false));
            //Недоступно на странице редактирования
            if(($controller->action->id != 'update') && $modelIsValid){
                $this->visibleButtons['update'] = true;
            }

            $isValidAction = (($controller->action->id != 'list') &&
                ($controller->action->id != 'view') && ($controller->action->id != 'create'));
            //Недоступно на странице подробностей
            if($isValidAction && $modelIsValid){
                $this->visibleButtons['view'] = true;
            }

            if((isset($this->model) && isset($this->model->id))){
                $this->visibleButtons['delete'] = true;
            }
        }

        if(!empty($this->buttons) && is_array($this->buttons)){
            foreach ($this->buttons as $name=>$data){
                //По умолчанию все кнопки скрыты
                $isVisible = false;
                if (isset($this->visibleButtons[$name])) {
                    $isVisible = $this->visibleButtons[$name];
                }

                if ($isVisible && isset($this->buttons[$name])) {
                    $this->pushRenderItem($name,$this->buttons[$name]);
                }
            }
        }
    }

    /**
     * Возвращает элементы меню
     * @return array|null
     */
    protected function getRenderItems()
    {
        if (is_array($this->_renderItems)) {
            return $this->_renderItems;
        }
        $this->setRenderItems([]);
        return $this->_renderItems;
    }

    /**
     * Устанавливает эдементы меню
     * @param array|null $renderItems
     */
    protected function setRenderItems($renderItems)
    {
        $this->_renderItems = $renderItems;
    }

    /**
     * Добавляет элемент меню
     * @param $name
     * @param $options
     */
    private function pushRenderItem($name,$options)
    {
        if (is_array($this->_renderItems)){
            $this->_renderItems[$name] = $options;
        }
    }
}