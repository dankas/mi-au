<?php

class Foto
{
    public $idfoto;
    public $idpet;
    public $img;
    public $ativo;
    public $datetimeregistro;


    function __construct() {}

    public function setidpet($idpet) {
        $this->idpet = $idpet;
    }

    public function setImg($img) {
        $this->img = $img;
    }  
    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }


    public function atualizaDb($pdo) {
        $existe = null;
        try {
            $data = $pdo->prepare("SELECT * FROM fotos");
            $data->execute();
            $existe = array_find($data->fetchAll(PDO::FETCH_CLASS, 'Foto'), fn($p) => $p->id == $this->idfoto);
        } catch   (PDOException $e) {
            error_log("Erro ao atualizar pet: " . $e->getMessage());
            return false;
        }

        switch (true) {
            case !isset($existe):
                // Inserir nova foto
                try {
                    $data = $pdo->prepare("INSERT INTO fotos (idpet, img, ativo, datetimeregistro) VALUES (:idpet, :img, :ativo, NOW())");
                    $data->bindValue(":idpet", $this->idpet, PDO::PARAM_INT);
                    $data->bindValue(":img", $this->img, PDO::PARAM_STR);
                    $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
                    $data->execute();
                    $this->idfoto = $pdo->lastInsertId();
                } catch (PDOException $e) {
                    error_log("Erro ao inserir foto: " . $e->getMessage());
                }
                break;

            // case (isset($existe) && $this->ativo):
            //     // Atualizar foto existente
            //     try {
            //         $data = $pdo->prepare("UPDATE fotos SET idpet = :idpet, img = :img, ativo = :ativo WHERE idfoto = :idfoto");
            //         $data->bindValue(":idpet", $this->idpet, PDO::PARAM_INT);
            //         $data->bindValue(":img", $this->img, PDO::PARAM_STR);
            //         $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
            //         $data->bindValue(":idfoto", $this->idfoto, PDO::PARAM_INT);
            //         $data->execute();
            //     } catch (PDOException $e) {
            //         error_log("Erro ao atualizar foto: " . $e->getMessage());
            //     }
            //     break;
        }

        if (isset($existe) && !$this->ativo) {
                    try {
                        $data = $pdo->prepare("UPDATE fotos SET ativo = :ativo WHERE idfoto = :idfoto");
                        $data->bindValue(":idfoto", $this->idfoto, PDO::PARAM_INT);
                        $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
                        $data->execute();
                        
                    } catch (PDOException $e) {
                        
                        error_log("Erro ao atualizar ativo do pet: " . $e->getMessage());
                    }
        }
        

  
    }


}
?>