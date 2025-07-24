<?php


session_start();

require_once 'controllers/authcontroller.php';

if(isset($_SESSION['isLogged'] )) {
    header('Location: home.php');
    
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>

<body>
    <div class="login-container">
        <img class="logo-img" src="assets/imgs/logos/logo.jpeg" alt="Logo">
        <h2>Login</h2>
        <form method="POST">
            <label for="username">Usuário:</label>
            <input timgype="text" name="username" id="username" required>
            <br>
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit">Entrar</button>
        </form>
    </div>
    <style>
        .logo-img {
            width: 150px;
            height: auto;
            margin-bottom: 5px;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .login-container {
            background: var(--card-bg, #fff);
            padding: 2rem 2.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .login-container h2 {
            color: var(--primary, #2c3e50);
            margin-bottom: 1.5rem;
        }
        .login-container label {
            color: var(--primary, #2c3e50);
            margin-top: 0.5rem;
            text-align: left;
            display: block;
        }
        .login-container input {
            width: 220px;
            padding: 0.5rem;
            margin-top: 0.25rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container button {
            background: var(--primary, #2c3e50);
            color: #fff;
            border: none;
            padding: 0.6rem 2rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 0.5rem;
        }
        .login-container button:hover {
            background: var(--secondary, #2980b9);
        }
    </style>
</body>
</html>