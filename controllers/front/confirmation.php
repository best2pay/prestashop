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
		$order_id = intval($_REQUEST["reference"]);
		// Need this pause to avoid receiving PENDING status
		sleep(2);
		
		if (!$order_id)
			Tools::redirect('index.php?controller=order&step=1');

		$order = new Order($order_id);
		$res = $this->checkPaymentStatus();
			
		if ($res == 'valid_approval') {
			$order->setCurrentState(Configuration::get('PS_OS_PAYMENT'));
			$order->save();
			$cart = $this->context->cart;
			$customer = new Customer($cart->id_customer);
			//Tools::redirect('index.php?controller=order-confirmation&id_cart=' . $cart->id . '&id_module=' . $this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key);
			Tools::redirect('https://rokastudio.ru/content/21-oplata-proshla-uspeshno' );
		} else {
			//$order->setCurrentState(Configuration::get('PS_OS_ERROR'));
			//$order->save();
			//Tools::redirect('index.php?controller=order-detail&id_order=' . $order_id);
			if ( ($res == 'error_133') || ($res == 'error_159') ) {
			    Tools::redirect('https://rokastudio.ru/content/22-dannyj-zakaz-oplachen-ranee' );
			} elseif ($res == 'error_169') {
			    Tools::redirect('https://rokastudio.ru/content/23-srok-oplaty-zakza-istek' );			    
			} else {
			    $order->setCurrentState(Configuration::get('PS_OS_ERROR'));
			    $order->save();
			    Tools::redirect('https://rokastudio.ru/content/20-oshibka-oplaty' );	
			}
		}
	}

	private function checkPaymentStatus() {
		$b2p_order_id = intval($_REQUEST["id"]);
		if (!$b2p_order_id)
			return 'no_b2p_order_id_in_redirect';

		$order_id = intval($_REQUEST["reference"]);
		if (!$order_id)
			return 'no_reference_in_redirect';

		$b2p_operation_id = intval($_REQUEST["operation"]);
		if (!$b2p_operation_id) {
            $b2p_error_id = intval($_REQUEST["error"]);
		    if (!(!$b2p_error_id)) {
			    return 'error_' . $b2p_error_id;
		    } else {
			    return 'unknown_error';
            }
		}

		$signature = base64_encode(md5($this->module->sector_id . $b2p_order_id . $b2p_operation_id . $this->module->password));

		if (!$this->module->test_mode) {
			$best2pay_url = 'https://pay.best2pay.net';
		} else {
			$best2pay_url = 'https://test.best2pay.net';
		}

		$context  = stream_context_create(array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query(array(
					'sector' => $this->module->sector_id,
					'id' => $b2p_order_id,
					'operation' => $b2p_operation_id,
					'signature' => $signature
				)),
			)
		));

		$repeat = 3;

		while ($repeat) {
			$repeat--;

			$xml = file_get_contents($best2pay_url . '/webapi/Operation', false, $context);

			if (!$xml) {
			    Tools::redirect('index.php?controller=order-confirmation&id_cart=notxml' );
				sleep(2);
				continue;
			}

			$xml = simplexml_load_string($xml);
			if (!$xml) {
			    Tools::redirect('index.php?controller=order-confirmation&id_cart=notxml1' );
				sleep(2);
				continue;
			}

			$response = json_decode(json_encode($xml));
			if (!$response) {
			    Tools::redirect('index.php?controller=order-confirmation&id_cart=notresponse' );
				sleep(2);
				continue;
			}

//			if (!$this->module->orderWasPayed($response)) {
//				sleep(2);
//				continue;
//			}
			
			return $this->module->orderWasPayed($response);
		}

		return false;
	}

}
