<?php

namespace unclead\multipleinput;

use Closure;
use yii\base\Model;

/**
 * This column-class generates specified input-widget for a single-column
 * MultipleInput. It uses the name property of the MultipleInput widget.
 *
 * *This class was tested only in the following case. And, with a high degree of
 * probability, it needs serious improvement in other cases.*
 *
 *
 * **Use case:**
 *
 * The Company model has a one-to-many relationship to the Mode model.
 *
 * The ModeInput widget is an input widget designed to edit the Mode model.
 *
 * Then, in the form of creating/updating the Company model, we have this code:
 * ``` php
 * // modes attribute is array of Mode objects
 * $form->field($company, 'modes')->widget(MultipleInput::className(), [
 *      'columnClass' => SingleColumn::className(),
 *      'columns' => [
 *          [
 *              'type' => ModeInput::className(),
 *          ],
 *      ],
 * ]);
 * ```
 *
 * After submitting the form, the server will receive something like this:
 * ```
 * Company[modes][0][someModeAttribute1] => value
 * Company[modes][0][someModeAttribute2] => value
 * Company[modes][1][someModeAttribute1] => value
 * Company[modes][1][someModeAttribute2] => value
 * ```
 */
class SingleColumn extends MultipleInputColumn
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->name = $this->context->attribute;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareValue()
    {
        $data = $this->getModel();
        if (!($this->value instanceof Closure) && $data instanceof Model) {
            return $data->toArray();
        } else {
            return parent::prepareValue();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderWidget($type, $name, $value, $options)
    {
        $options['name'] = $name;
        $options['value'] = $value;
        return $type::widget($options);
    }
}
