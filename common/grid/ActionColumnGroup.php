<?php
/**
 * @copyright Copyright (c) 2020 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 23.02.2020
 */

namespace common\grid;


use Yii;
use yii\grid\ActionColumn;
use yii\helpers\Html;

class ActionColumnGroup extends ActionColumn
{
    public $template = '<div class="btn-group" role="group">{view} {update} {delete}</div>';

    /** @var array html Individual options to be applied to the [[initDefaultButton()|default button]]. */
    public $buttonsOptions = [
        'view'=>[ 'class' => 'btn btn-default'],
        'update'=>[ 'class' => 'btn btn-primary'],
        'delete'=>[ 'class' => 'btn btn-danger']
    ];

    /**
     * Initializes the default button rendering callback for single button
     * @param string $name Button name as it's written in template
     * @param string $iconName The part of Bootstrap glyphicon class that makes it unique
     * @param array $additionalOptions Array of additional options
     * @since 2.0.11
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }

                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);

                if (isset($this->buttonsOptions[$name]) && is_array($this->buttonsOptions[$name]) && !empty($this->buttonsOptions[$name]))
                    $options = array_merge($options,$this->buttonsOptions[$name]);

                return Html::a($icon, $url, $options);
            };
        }
    }
}