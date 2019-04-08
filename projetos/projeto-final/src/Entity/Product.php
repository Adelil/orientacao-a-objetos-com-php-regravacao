<?php
namespace Code\Entity;

use Code\DB\Entity;

class Product extends Entity
{
	protected  $table = 'products';
	static $filters = [
		'name' => FILTER_SANITIZE_STRING,
		'description' => FILTER_SANITIZE_STRING,
		'content' => FILTER_SANITIZE_STRING,
		'price' => [ 'filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_THOUSAND]
	];
}