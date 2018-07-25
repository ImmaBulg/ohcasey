<?php

namespace App\Http\Requests;

/**
 * Форма редактирования статуса заказа
 *
 * @package App\Http\Requests
 */
class OrderStatusStoreRequest extends Request
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
            'status_name'    => 'required|max:255',
            'status_color'   => 'required|max:255',
            'sort'           => 'required|numeric',
            'status_success' => 'numeric|max:1',
            // не атрибут
            'delete'         => 'numeric|max:1',
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return trans('order_status.attributes');
    }
}