<?php

namespace App\Support;

use App\Models\Order;
use App\Models\SmsTemplate;

class OrderTemplateSmsSender
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var SmsTemplate
     */
    protected $template;

    /**
     * @var SmsSender
     */
    protected $sender;

    /**
     * SmsSender constructor.
     * @param Order $order
     * @param SmsTemplate $template
     */
    public function __construct(Order $order, SmsTemplate $template)
    {
        $this->order    = $order;
        $this->template = $template;
        $this->sender   = app(SmsSender::class);
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send()
    {
        $this->sender->send(
            $this->order->client_phone,
            $this->template->render($this->order)
        );
    }
}