<?php
include_once('../db_connection.php');

class Upload
{
	private $customerTable = 'customer';
	private $productTable = 'product';
	private $saleTable = 'sale';
	private $conn = null;
	private $UTCVersion = '1.0.17+60';

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function updateSales()
	{
		try
		{
			$jsonData = file_get_contents($_FILES['upload']['tmp_name']);
			$jsonData = json_decode($jsonData, true);

			foreach($jsonData as $data)
			{
				$mail = $data['customer_mail'];
				$cname = $data['customer_name'];
				
				$pId = $data['product_id'];
				$pName = $data['product_name'];
				$pPrice = $data['product_price'];

				$saleId = $data['sale_id'];
				$saleDate = $data['sale_date'];
				$isUTC = version_compare($data['version'], $this->UTCVersion, '>=');
				if(!$isUTC)
				{
					$defaultTimeZone = date_default_timezone_get();
					date_default_timezone_set('Europe/Berlin');

					$datetime = new DateTime($saleDate);
					$utcTime = new DateTimeZone('UTC');
					$datetime->setTimezone($utcTime);
					$saleDate = $datetime->format('Y-m-d H:i:s');
					date_default_timezone_set($defaultTimeZone);
				}


				$customerId = $this->insertCustomer($mail, $cname);
				if(!$customerId)
				{
					$customerId = $this->selectCustomer($mail)['id'];
				}
				
				$productId = $this->insertProduct($pId, $pName);
				if(!$productId)
				{
					$productId = $pId;
				}

				$this->insertSale($saleId, $customerId, $productId, $pPrice, $saleDate);
			}
			echo json_encode(['status'=>'success', 'message'=>'uploaded successfully']);
		} catch(Exception $e) {
			echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
		}

	}

	public function selectCustomer($mail = null)
	{
		$sql = "SELECT * FROM `customer` WHERE 1";
		if(isset($mail))
		{
			$sql .= " AND mail = ".$this->sanitize($mail);
		}
		$result = $this->conn->query($sql);
		return $result->fetch();
	}

	public function insertCustomer($mail, $name)
	{
		$sql = "INSERT IGNORE INTO `customer` (`mail`, `name`) VALUES (".$this->sanitize($mail).", ".$this->sanitize($name).");";
		$this->conn->exec($sql);
		return $this->conn->lastInsertId();
	}

	public function insertProduct($id = NULL, $name)
	{
		$sql = "INSERT IGNORE INTO `product` (`id`,`name`) VALUES (".$this->sanitize($id).", ".$this->sanitize($name).");";
		$this->conn->exec($sql);
		return $this->conn->lastInsertId();
	}

	public function insertSale($id = NULL, $customerId, $productId, $price, $saleDate)
	{
		$sql = "INSERT IGNORE INTO `sale` (`id`,`customer_id`,`product_id`,`price`,`sale_date`) VALUES (".$this->sanitize($id).", ".$this->sanitize($customerId).", ".$this->sanitize($productId).", ".$this->sanitize($price).", ".$this->sanitize($saleDate).");";
		$this->conn->exec($sql);
	}

	public function sanitize($data)
	{
		return "'".str_replace("'","''",$data)."'";
	}

}

$upload = new Upload($conn);
$upload->updateSales();
?> 