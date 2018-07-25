<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmsTemplateStoreRequest;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\SmsTemplate;
use App\Support\OrderTemplateSmsSender;
use Illuminate\Http\Request;

/**
 * Class SmsTemplatesController
 * @package App\Http\Controllers
 */
class SmsTemplatesController extends Controller
{
    /**
     * @param Order $order
     * @throws
     * @return OrderTemplateSmsSender
     */
    public function send(Order $order, Request $request)
    {
        $smsTemplate = SmsTemplate::where('after_order_status_id', $request->get('after_order_status_id'));
        $prev = $request->get('before_order_status_id', null);

        if ($prev || $prev === '0') {
            $smsTemplate->where('before_order_status_id', $prev);
        } else {
            $smsTemplate->whereNull('before_order_status_id');
        }

        $smsTemplate = $smsTemplate->first();

        if (!$smsTemplate) {
            return $this->buildFailedValidationResponse($request, [
                'Не найден шаблон СМС',
            ]);
        }

        $sender = new OrderTemplateSmsSender($order, $smsTemplate);
        try {
            $sender->send();
            return 'OK';
        } catch (\Exception $e) {
            \Log::critical($e->getMessage(), $e->getTrace());
            return $this->buildFailedValidationResponse($request, [
                'Ошибка отправки СМС',
            ]);
        }
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('admin.sms_templates.lists')->with([
            'templates' => SmsTemplate::orderBy('id', 'DESC')
                ->with([
                    'beforeOrderStatus',
                    'afterOrderStatus',
                ])
                ->get(),
        ]);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return $this->formPage(new SmsTemplate());
    }

    /**
     * @param SmsTemplate $template
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(SmsTemplate $template)
    {
        return $this->formPage($template);
    }

    /**
     * @param SmsTemplateStoreRequest $request
     * @param SmsTemplate|null $smsTemplate
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SmsTemplateStoreRequest $request, SmsTemplate $smsTemplate = null)
    {
        with($smsTemplate ?: new SmsTemplate())->fill($request->all())->save();

        return redirect()->route('admin.sms_templates.index');
    }

    /**
     * @param SmsTemplate $template
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function formPage(SmsTemplate $template)
    {
        return view('admin.sms_templates.form')->with([
            'template'        => $template,
            'orderStatusList' => OrderStatus::orderBy('status_name')->get(),
        ]);
    }
}