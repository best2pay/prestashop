<?php
if (!defined('_PS_VERSION_')) exit;

/**
 * @since 1.5.0
 */
class Best2PayConfirmationModuleFrontController extends ModuleFrontController {

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent() {
		$link = Context::getContext()->link;
		try{
			$best2pay_id = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : null;
			if (!$best2pay_id)
				throw new Exception($this->module->l('Missing Best2Pay Order ID in the request'));
			$order_id = isset($_REQUEST['reference']) ? (int) $_REQUEST['reference'] : null;
			if (!$order_id)
				throw new Exception($this->module->l('Missing Order ID in the request'));
			$best2pay_operation_id = isset($_REQUEST['operation']) ? (int) $_REQUEST['operation'] : null;
			if (!$best2pay_operation_id) {
				$error = $this->module->l('Missing Operation ID in the request');
				$error .= " (error " . (isset($_REQUEST['error']) ? (int) $_REQUEST['error'] : 'unknown') . ")";
				throw new Exception($error);
			}
			$order = new Order($order_id);
			if (!Validate::isLoadedObject($order))
				throw new Exception($this->module->l('Order not found'));
			
			$best2pay_response = $this->module->getPaymentOperationInfo($best2pay_id, $best2pay_operation_id);
			$best2pay_operation = $this->module->parseXML($best2pay_response);
			$operation_is_valid = $this->module->operationIsValid($best2pay_operation);
		} catch(Exception $e) {
			$this->module->redirectWithNotification($link->getPageLink('history'), $e->getMessage());
		}
		
		if ($best2pay_operation['state'] == $this->module::BEST2PAY_OPERATION_APPROVED && in_array($best2pay_operation['type'], $this->module::BEST2PAY_PAYMENT_TYPES)) {
			$order_state = $this->module->getCustomOrderState($best2pay_operation['type']);
			$cart = $this->context->cart;
			$customer = new Customer($cart->id_customer);
			$args = 'id_cart=' . $cart->id . '&id_module=' . $this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key;
			$redirect_url = $link->getPageLink('order-confirmation') . '?' . $args;
			$this->success[] = $this->module->l('Order successfully paid');
		} else {
			$order_state = Configuration::get('PS_OS_ERROR');
			$redirect_url = $link->getPageLink('order-detail') . '?id_order=' . $order_id;
			$this->warning[] = $this->module->l('An error occurred while paying for the order');
		}
		$order->setCurrentState($order_state);
		$order->save();
		if($this->module->getBest2payOrder($best2pay_id)) {
			$amount = !empty($best2pay_operation['buyIdSumAmount']) ? $best2pay_operation['buyIdSumAmount'] : $best2pay_operation['amount'];
			$this->module->updateBest2payOrder($best2pay_id, [
				'amount' => $amount,
				'order_state' => $best2pay_operation['order_state']
			]);
		}
		
		$this->module->redirectWithNotification($redirect_url);
	}

}