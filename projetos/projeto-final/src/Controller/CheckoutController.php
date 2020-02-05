<?php
namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\User;
use Code\Session\Session;
use Code\Payment\PagSeguro\{
	Session as PagSeguroSession,
	CreditCard
};
use Code\View\View;
use Code\Entity\UserOrder;

class CheckoutController
{
	public function index()
	{
		/* if(!Session::has('user')) {
			return header('Location: ' . HOME . '/store/login');
		}

		var_dump(Session::get('user'));
        */

		if(!Session::has('cart')) return header('Location: ' . HOME);

		$cart = Session::get('cart');
		$cart = array_map(function($line){
			return $line['price'] * $line['qtd'];
		}, $cart);
		$totalCart = array_sum($cart);

		//Session::remove('pagseguro_session');
		//Criar o checkout no PagSeguro
		PagSeguroSession::createSession();

		$view = new View('site/checkout.phtml');
		$view->totalCart = $totalCart;

		return $view->render();
	}

	public function proccess()
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST')
			return json_encode(['data' => ['error' => 'Método não suportado!']]);


		$items = Session::get('cart');
		$data = $_POST;
		$reference = 'XPTO';

		$creditCardPayment = new CreditCard($reference, $items, $data);
		$result = $creditCardPayment->doPayment();

		$userOrder = new UserOrder(Connection::getInstance());
		$userOrder = $userOrder->createOrder([
			'user_id' => 2,
			'reference' => $reference,
			'pagseguro_code' => $result->getCode(),
			'pagseguro_status' => $result->getStatus(),
			'items' => serialize($items)
		]);

		Session::remove('pagseguro_session');
		Session::remove('cart');

		return json_encode(['data' => [
			'ref_order' => $userOrder['reference'],
			'message'   => 'Transação concluída com sucesso!'
		]]);
	}

	public function thanks()
	{
		if(!isset($_GET['ref'])) return header('Location: ' . HOME);

		try {
			$reference = htmlentities($_GET['ref']);

			$userOrder = (new UserOrder(Connection::getInstance()))->where(['reference' => $reference]);

			$view = new View('site/thanks.phtml');
			$view->reference = $userOrder['reference'];

			return $view->render();

		} catch (\Exception $e) {
			return header('Location: ' .  HOME);
		}
	}
}