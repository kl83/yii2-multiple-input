<?php

namespace unclead\multipleinput;

use yii\base\Model;
use yii\helpers\Html;

/**
 * Same as the MultipleInputColumn, expect for one. It always generates input
 * names for sending to the server in the form of two-dimensional arrays
 * [0 => ['one col' => 'whatever']]
 */
class MultiDimColumn extends MultipleInputColumn
{
    /**
     * {@inheritdoc}
     */
    public function getElementName($index, $withPrefix = true)
    {
        $name = parent::getElementName($index, $withPrefix);
        if ($this->isRendererHasOneColumn()) {
            return preg_replace(
                '~(\[[^\]]+\])(\[[^\]]+\])$~',
                '$2$1',
                $name
            );
        } else {
            return $name;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstError($index)
    {
        $model = $this->context->model;
        if ($index !== null && $model instanceof Model) {
            $attribute = $this->context->attribute .
                $this->getElementName($index, false);
            return $model->getFirstError($attribute);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getInputNamePrefix()
    {
        $model = $this->context->model;
        if ($model instanceof Model) {
            if (empty($this->renderer->columns)) {
                return $model->formName();
            } else {
                return Html::getInputName(
                    $this->context->model,
                    $this->context->attribute
                );
            }
        } else {
            return $this->context->name;
        }
    }

    /**
     * @return bool
     */
    private function isRendererHasOneColumn()
    {
        return count($this->renderer->columns) === 1;
    }
}
