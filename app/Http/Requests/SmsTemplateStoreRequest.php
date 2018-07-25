<?php

namespace App\Http\Requests;

use App\Models\SmsTemplate;

/**
 * Форма редактирования смс шаблона.
 *
 * @package App\Http\Requests
 */
class SmsTemplateStoreRequest extends Request
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
        /** @var SmsTemplate|null $smsTemplate */
        $smsTemplate = $this->route('smsTemplate');

        $rules = [
            'name'                   => 'required|max:255',
            'template'               => 'required',
            'after_order_status_id'  => 'required|integer|exists:order_status,status_id|unique_with:sms_templates,before_order_status_id',
        ];

        // Для существующий записи надо дописать ignore_id на валидацию unique_with
        if ($smsTemplate) {
            $rules['after_order_status_id'] .= (',' . $smsTemplate->id);
        }

        if ($this->get('before_order_status_id', 'NULL') != 'NULL') {
            $rules['before_order_status_id'] = 'integer|exists:order_status,status_id';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return trans('sms_templates.attributes');
    }
}