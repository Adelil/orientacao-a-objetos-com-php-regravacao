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

	public function getProductWithImagesById($product_id)
	{
		$sql = 'select 
					p.*, pi.id as image_id, 
					pi.image 
				from 
				    products p 
				inner join 
				    products_images pi on pi.product_id = p.id 
				where p.id = :productId';

		$select = $this->conn->prepare($sql);
		$select->bindValue(':productId', $product_id, \PDO::PARAM_INT);
		$select->execute();

		$productData = [];
		foreach ($select->fetchAll(\PDO::FETCH_ASSOC) as $product) {
			$productData['id']          = $product['id'];
			$productData['name']        = $product['name'];
			$productData['description'] = $product['description'];
			$productData['content']     = $product['content'];
			$productData['price']       = $product['price'];
			$productData['is_active']   = $product['is_active'];
			$productData['images'][]    = ['id' => $product['image_id'], 'image' => $product['image']];

		}

		return $productData;
	}
}