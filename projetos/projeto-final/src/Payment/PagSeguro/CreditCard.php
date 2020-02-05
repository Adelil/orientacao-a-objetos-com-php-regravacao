<?php
namespace Code\Payment\PagSeguro;


class CreditCard
{
	//referencia, produtos - items, data -> post
	private $reference;
	private $items;
	private $data;
	private $user;

	public function __construct($reference, $items, $data)
	{
		$this->reference = $reference;
		$this->items     = $items;
		$this->data      = $data;
	}


	public function doPayment()
	{
		$creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();
		$creditCard->setReceiverEmail(getenv('PAGSEGURO_EMAIL'));
		$creditCard->setReference($this->reference);
		$creditCard->setCurrency("BRL");

		foreach($this->items as $item){
			$creditCard->addItems()->withParameters(
				$this->reference,
				$item['name'],
				$item['qtd'],
				$item['price']
			);
		}

		// @sandbox.pagseguro.com.br <- sandbox | production -> @gmail.com, @hotmail.com
		$creditCard->setSender()->setName('João Comprador');
		$creditCard->setSender()->setEmail('email@sandbox.pagseguro.com.br');

		$creditCard->setSender()->setPhone()->withParameters(
			11,
			56273440
		);

		$creditCard->setSender()->setDocument()->withParameters(
			'CPF',
			'02551807131'
		);

		$creditCard->setSender()->setHash($this->data['hash']);

		$creditCard->setSender()->setIp('127.0.0.0');

		$creditCard->setShipping()->setAddress()->withParameters(
			'Av. Brig. Faria Lima',
			'1384',
			'Jardim Paulistano',
			'01452002',
			'São Paulo',
			'SP',
			'BRA',
			'apto. 114'
		);


		$creditCard->setBilling()->setAddress()->withParameters(
			'Av. Brig. Faria Lima',
			'1384',
			'Jardim Paulistano',
			'01452002',
			'São Paulo',
			'SP',
			'BRA',
			'apto. 114'
		);

		$creditCard->setToken($this->data['card_token']);

		list($installmentNumber, $installmentValue) = explode('|', $this->data['installments']);

		$installmentValue = number_format((float) $installmentValue, 2, '.', '');
		$creditCard->setInstallment()->withParameters($installmentNumber, $installmentValue);

		$creditCard->setHolder()->setBirthdate('01/10/1979');
		$creditCard->setHolder()->setName($this->data['card_name']);

		$creditCard->setHolder()->setPhone()->withParameters(
			11,
			56273440
		);

		$creditCard->setHolder()->setDocument()->withParameters(
			'CPF',
			'02551807131'
		);

		$creditCard->setMode('DEFAULT');


		$result = $creditCard->register(
			\PagSeguro\Configuration\Configure::getAccountCredentials()
		);

		return $result;
	}
}