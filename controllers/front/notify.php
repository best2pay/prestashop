<?php
if (!defined('_PS_VERSION_')) exit;

/**
 * @since 1.5.0
 */
class Best2PayNotifyModuleFrontController extends ModuleFrontController {
	/**
	 * @see FrontController::initContent()
	 */
	public function postProcess() {
		header('Content-Type: text/plain');
		$response = file_get_contents("php://input");
		try{
			$best2pay_operation = $this->module->parseXML($response);
			$operation_is_valid = $this->module->operationIsValid($best2pay_operation);
			$order = new Order((int)$best2pay_operation['reference']);
			if (!Validate::isLoadedObject($order))
				throw new Exception($this->module->l('Order not found'));
		} catch(Exception $e) {
			die($e->getMessage());
		}
		
		if ($operation_is_valid) {
			$state = $this->module->getCustomOrderState($best2pay_operation['type']);
			$message = 'ok';
		} else {
			$state = Configuration::get('PS_OS_ERROR');
			$message = 'Operation is invalid';
		}
		$order->setCurrentState($state);
		$order->save();
		if($this->module->getBest2payOrder($best2pay_operation['order_id'])) {
			$amount = !empty($best2pay_operation['buyIdSumAmount']) ? $best2pay_operation['buyIdSumAmount'] : $best2pay_operation['amount'];
			$this->module->updateBest2payOrder($best2pay_operation['order_id'], [
				'amount' => $amount,
				'order_state' => $best2pay_operation['order_state']
			]);
		}
		die($message);
	}

}
