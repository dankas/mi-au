<?php
require_once './core/Database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $data= $pdo->prepare('SELECT * FROM users WHERE username = :usuario AND password = :senha');
    $data-> bindValue(":usuario",$username);
    $data-> bindValue(":senha",$password);
    $data-> execute();
    $user = $data-> fetch();
    
    if ($user != NULL) {
        $_SESSION['userId'] = $user["iduser"];
        $_SESSION['isLogged'] = true;
        header('Location: home.php?section=home');
        exit();
    }

}
?>