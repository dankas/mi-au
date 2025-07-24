<?php
function getUser($idtutor,$data) {
    try {
        $data->bindValue(":id",$idtutor);
        $data->execute();
        return $data->fetchObject('User');
    }
    catch (PDOException $e) {
        echo "Erro ao buscar dados: " . $e->getMessage();
    }
}
function updateUser($user,$data) {
    $user->atualizaDb($data);
}

function atualizaUsername($username,$iduser,$data) {
        $data->prepare("UPDATE users SET username = :username WHERE id = :id");
        $data->bindValue(":id",$iduser,PDO::PARAM_INT);
        $data->bindValue(":username",$username);
        $data->execute();
}
function atualizaPassword($password,$iduser,$data) {
        $data->prepare("UPDATE users SET password = :password WHERE id = :id");
        $data->bindValue(":id",$iduser,PDO::PARAM_INT);
        $data->bindValue(":password",$password);
        $data->execute();

}

?>