<?php 


class Pet {
    public private(set) int $idpet;
    public $nome;
    public $race;
    public $tipo;
    public $tutor;
    public $bio;
    public $imgperfil;
    public $nascimento;
    public private(set) int $ativo;
    public $datetimeregistro;
    public $perdido;

    public function __construct()        {

        }
    
    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function setId($id) {
        $this->idpet = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setRace($race) {
        $this->race = $race;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setTutor($tutor) {
        $this->tutor = $tutor;
    }

    public function setBio($bio) {
        $this->bio = $bio;
    }

    public function setNascimento($nascimento) {
        $this->nascimento = $nascimento;
    }
    public function setImgperfil($imgperfil) {
        $this->imgperfil = $imgperfil;
    }
    public function setPerdido($perdido) {
        $this->perdido = $perdido;
    }


    public function atualizaDb($pdo) {
        $existe = null;
        try {
            $data = $pdo->prepare("SELECT * FROM pet");
            $data->execute();
            $existe = array_find($data->fetchAll(PDO::FETCH_CLASS, 'Pet'), fn($p) => $p->idpet == $_POST['idpet']);
        } catch   (PDOException $e) {
            error_log("Erro ao atualizar pet: " . $e->getMessage());
            return false;
        }

        switch (true) {
            case !isset($existe):
                // Inserir novo pet
                try {
                    $data = $pdo->prepare("INSERT INTO pet (nome,race,tipoid,tutor,bio,nascimento,ativo,datetimeregistro,imgperfil,perdido) VALUES (:nomepet,:racepet, :tipopet, :userid, :biopet, :nascimentopet, :ativo, NOW(), :imgperfil, :perdido)");
                    $data->bindValue(":nomepet", $this->nome);
                    $data->bindValue(":tipopet", $this->tipo, PDO::PARAM_INT);
                    $data->bindValue(":racepet", $this->race);
                    $data->bindValue(":biopet", $this->bio);
                    $data->bindValue(":nascimentopet", $this->nascimento);
                    $data->bindValue(":userid", $this->tutor, PDO::PARAM_INT);
                    $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
                    $data->bindValue(":perdido", $this->perdido, PDO::PARAM_INT);
                    $data->bindValue(":imgperfil", $this->imgperfil);
                    $data->execute();
                    $this->idpet = $pdo->lastInsertId();
                } catch (PDOException $e) {
                    error_log("Erro ao inserir pet: " . $e->getMessage());
                }
                break;

            case (isset($existe) && $this->ativo):
                // Atualizar pet existente
                try {
                    $data = $pdo->prepare("UPDATE pet SET nome = :nomepet, race = :racepet, tipoid = :tipopet, tutor = :userid, bio = :biopet, nascimento = :nascimentopet, imgperfil = :imgperfil, perdido = :perdido WHERE idpet = :idpet");
                    $data->bindValue(":nomepet", $this->nome);
                    $data->bindValue(":tipopet", $this->tipo, PDO::PARAM_INT);
                    $data->bindValue(":racepet", $this->race);
                    $data->bindValue(":biopet", $this->bio);
                    $data->bindValue(":nascimentopet", $this->nascimento);
                    $data->bindValue(":userid", $this->tutor, PDO::PARAM_INT);
                    $data->bindValue(":imgperfil", $this->imgperfil);
                    $data->bindValue(":idpet", $this->idpet, PDO::PARAM_INT);
                    $data->bindValue(":perdido", $this->perdido, PDO::PARAM_INT);

                    $data->execute();
                                
                } catch (PDOException $e) {
                    error_log("Erro ao atualizar pet: " . $e->getMessage());
                }
                break;
        }

        if (isset($existe) && !$this->ativo) {
                    try {
                        $data = $pdo->prepare("UPDATE pet SET ativo = :ativo WHERE idpet = :idpet");
                        $data->bindValue(":idpet", $this->idpet, PDO::PARAM_INT);
                        $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
                        $data->execute();
                        
                    } catch (PDOException $e) {
                        
                        error_log("Erro ao atualizar ativo do pet: " . $e->getMessage());
                    }
        }
        

  
    }

}

?>