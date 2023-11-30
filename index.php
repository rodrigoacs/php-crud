<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    form {
      display: flex;
      flex-direction: column;
      width: 50%;
    }

    a {
      margin-bottom: 1rem;
      text-align: center;
      width: 50%;
    }
  </style>
  <title>PHP - CRUD</title>
</head>

<body>
  <a href="/vendas.php">vender</a>
  <form action="." method="POST">
    <input type="text" placeholder="codigo" name="codigo">
    <input type="text" placeholder="descrição" name="descricao">
    <input type="text" placeholder="marca" name="marca">
    <input type="text" placeholder="preço" name="preco">
    <input type="submit" value="Salvar">
  </form>
</body>

</html>

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

function insertItem($conn, $codigo, $descricao, $marca, $preco)
{
  $preco = (float) $preco;
  $sql = "INSERT INTO itens (codigo, descricao, marca, preco) VALUES ('$codigo', '$descricao', '$marca', '$preco')";
  $conn->query($sql);

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $codigo = $_POST['codigo'];
  $descricao = $_POST['descricao'];
  $marca = $_POST['marca'];
  $preco = $_POST['preco'];
  $conn = connectToDatabase();

  if (empty($codigo) || empty($descricao) || empty($marca) || empty($preco)) {
    die();
  } else {
    insertItem($conn, $codigo, $descricao, $marca, $preco);
  }

}