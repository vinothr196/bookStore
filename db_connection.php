<?php
$servername = "172.28.1.1";
$username = "root";
$password = "admin123";
$dbname = "bookStore";
$conn = null;
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
#  $sql = "INSERT IGNORE INTO `customer` (`id`, `mail`, `name`, `created_on`, `updated_on`) VALUES (NULL, 'ramesh200392@gmail.com', 'Ramesh', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";
  // use exec() because no results are returned
#  $conn->exec($sql);
#  $last_id = $conn->lastInsertId();
#  echo "New record created successfully. Last inserted ID is: " . $last_id;
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

return $conn;
?> 