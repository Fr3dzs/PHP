<?php

session_start();

//Configurações BD

    $host = "localhost"; // Define o host do banco de dados.
    $dbname = "moviestar"; // Define o nome do banco de dados.
    $username = "root"; // Define o nome de usuário do banco de dados.
    $password = ""; // Define a senha do banco de dados.


    try {

        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); // Tenta estabelecer uma conexão com o banco de dados usando PDO.

        //Ativar o modo de erro do PDO para lançar exceções em caso de erros.
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura o modo de erro do PDO para lançar exceções.

    } catch (PDOException $e) {
        $error = $e->getMessage(); // Captura a mensagem de erro da exceção.
        echo "Erro: $error"; // Exibe a mensagem de erro.
    }

?>