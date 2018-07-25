<?php

namespace App\Http\Requests;

/**
 * Форма редактирования смс шаблона.
 *
 * @package App\Http\Requests
 */
class SpecialItemStoreRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric',
            'order_id' => 'integer',
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return trans('special_order_item.attributes');
    }
}