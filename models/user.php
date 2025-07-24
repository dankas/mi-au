<?php 


class User {
    public private(set) int $iduser;
    public private(set) string $username;
    public private(set) string $telefone;
    public private(set) string $imgprofile;
    public private(set) string $password;

    public function __construct()        {

        }

    
    public function setIduser(int $iduser) {
        $this->iduser = $iduser;
    }

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setTelefone(string $telefone) {
        $this->telefone = $telefone;
    }

    public function setImgprofile(string $imgprofile) {
        $this->imgprofile = $imgprofile;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }
    public function atualizaDb($pdo) {
        try {
            $data = $pdo->prepare("SELECT * FROM users");
            $data->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
        $data = $pdo->prepare("UPDATE users SET username = :username, telefone = :telefone, imgprofile = :imgprofile, password = :password WHERE iduser = :id");
        $data->bindValue(":id", $this->iduser, PDO::PARAM_INT);
        $data->bindValue(":username", $this->username);
        $data->bindValue(":telefone", $this->telefone);
        $data->bindValue(":imgprofile", $this->imgprofile);
        $data->bindValue(":password", $this->password);
        $data->execute();
       

    }
}

?>