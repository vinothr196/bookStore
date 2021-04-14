<?php
include_once('../db_connection.php');

class Load
{
	private $customerTable = 'customer';
	private $productTable = 'product';
	private $saleTable = 'sale';
	private $conn = null;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function sales()
	{
		$resultData = [];
		$whereClause = "";
		
		// Filter
		$customer = $_POST['customer'];
		$product = $_POST['product'];
		$priceFrom = $_POST['priceFrom'];
		$priceTo = $_POST['priceTo'];

		if(isset($customer) && $customer != '')
		{
			$whereClause .= " AND c.name LIKE '%". $this->sanitize($customer) ."%'";
		}

		if(isset($product) && $product != '')
		{
			$whereClause .= " AND p.name LIKE '%". $this->sanitize($product) ."%'";
		}

		if(isset($priceFrom) && $priceFrom != '' || isset($priceTo) && $priceTo != '')
		{
			$whereClause .= isset($priceFrom) && $priceFrom != '' ? " AND s.price >= '".$this->sanitize($priceFrom)."'" : "";
			$whereClause .= isset($priceTo) && $priceTo != '' ? " AND s.price <= '".$this->sanitize($priceTo)."'" : "";
		}

		$sql = "SELECT c.name as `cname`, c.mail, p.name as `product`,s.price,s.sale_date FROM `sale` as `s` LEFT JOIN `customer` as `c` ON (c.id = customer_id) LEFT JOIN `product` as `p` ON (p.id = product_id) WHERE 1 ".$whereClause." ORDER by s.id";

		$result = $this->conn->query($sql);
		while($row = $result->fetch())
		{
			$resultData['sales'][] = [
				'cname' => $row['cname'],
				'mail' => $row['mail'],
				'product' => $row['product'],
				'price' => $row['price'],
				'sale_date' => $row['sale_date'] 
			];
        	$resultData['total'] += $row['price'];
		}
		$resultData['total'] = number_format($resultData['total'], 2);
        return $resultData;
	}

	public function sanitize($data)
	{
		return str_replace("'","''",$data);
	}

}

$load = new Load($conn);
echo json_encode($load->sales());
?> 