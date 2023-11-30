<?php

require_once __DIR__ . '/vendor/autoload.php'; // Caminho para o autoloader do Composer

function connectToDatabase()
{
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  $servername = $_ENV['DB_HOST'];
  $database = $_ENV['DB_DATABASE'];
  $username = $_ENV['DB_USER'];
  $password = $_ENV['DB_PASSWORD'];

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  return $conn;
}

function getItem($conn, $codigo)
{
  $sql = "SELECT * FROM itens WHERE codigo = '$codigo'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();

  // return as json if the item exists
  if ($row) {
    return $row;
  } else {
    return null;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;

  $conn = connectToDatabase();

  if (empty($codigo)) {
    die("Código não fornecido");
  } else {
    $item = getItem($conn, $codigo);

    if ($item) {
      echo json_encode($item);
    } else {
      echo json_encode(['error' => 'Item não encontrado']);
    }
  }
}
