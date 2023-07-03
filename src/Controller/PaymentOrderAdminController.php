<?php

namespace Best2Pay\Controller;

require_once __DIR__. '/../../best2pay.php';

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Best2Pay;

class PaymentOrderAdminController extends FrameworkBundleAdminController
{
	protected $module;
	
	public function __construct()
	{
		parent::__construct();
		$this->module = new Best2Pay();
	}
	
	public function completeOrderAction(Request $request)
	{
		$order_id = $request->query->getInt('order_id');
		if(!$order_id)
			return $this->module->l('Order ID is empty');
		$best2pay_order = $this->module->getBest2payOrder(['order_id' => $order_id]);
		if($best2pay_order['order_state'] == Best2Pay::BEST2PAY_ORDER_AUTHORIZED){
			if($this->module->completeBest2payOrder($best2pay_order)){
				$msg_type = 'success';
				$msg_text = $this->module->l('Payment complete successful!');
			} else {
				$msg_type = 'error';
				$msg_text = $this->module->l('Failed to complete Order. Try again later');
			}
			$this->get('session')->getFlashBag()->add($msg_type, $msg_text);
			$content = ['success' => 1];
		} else {
			$content = ['error' => $this->module->l('Invalid order state')];
		}
		$response = new Response();
		$response->setContent(json_encode($content, JSON_UNESCAPED_UNICODE));
		return $response;
	}
	
	public function refundOrderAction(Request $request)
	{
		$order_id = $request->query->getInt('order_id');
		if(!$order_id)
			return $this->module->l('Order ID is empty');
		$best2pay_order = $this->module->getBest2payOrder(['order_id' => $order_id]);
		$response = new Response();
		if($best2pay_order['order_state'] == Best2Pay::BEST2PAY_ORDER_AUTHORIZED || $best2pay_order['order_state'] == Best2Pay::BEST2PAY_ORDER_COMPLETED){
			if($this->module->refundBest2payOrder($best2pay_order)){
				$msg_type = 'success';
				$msg_text = $this->module->l('Payment refunded successful!');
			} else {
				$msg_type = 'error';
				$msg_text = $this->module->l('Failed to refund Order. Try again later');
			}
			$this->get('session')->getFlashBag()->add($msg_type, $msg_text);
			$content = ['success' => 1];
		} else {
			$content = ['error' => $this->module->l('Invalid order state')];
		}
		$response->setContent(json_encode($content, JSON_UNESCAPED_UNICODE));
		return $response;
	}
}
