<?php 
class Consulta {
    public $idconsulta;
    public $nomevet;
    public $descricao;
    public $pet;
    public $dataconsulta;
    public $img;
    public $ativo;
    public $datetimeregistro;
    public $tipoconsultaid;

    public function __construct()        {

    }

    public function setIdconsulta($idconsulta) {
        $this->idconsulta = $idconsulta;
    }

    public function setNomevet($nomevet) {
        $this->nomevet = $nomevet;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setPet($pet) {
        $this->pet = $pet;
    }

    public function setDataconsulta($dataconsulta) {
        $this->dataconsulta = $dataconsulta;
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
            $data = $pdo->prepare("SELECT * FROM consulta");
            $data->execute();
            $existe = array_find($data->fetchAll(PDO::FETCH_CLASS, 'Consulta'), fn($c) => $c->idconsulta == $this->idconsulta);
        } catch   (PDOException $e) {
            error_log("Erro ao atualizar consulta: " . $e->getMessage());
            return false;
        }
        switch (true) {
            case !isset($existe):
                // Inserir nova consulta
                try {
                    $data = $pdo->prepare("INSERT INTO consulta (nomevet,descricao,pet,dataconsulta,ativo,img,tipoconsultaid) VALUES (:nomevet,:descricao,:pet,:dataconsulta,:ativo,:img,:tipoconsultaid)");
                    $data->bindValue(":nomevet", $this->nomevet);
                    $data->bindValue(":descricao", $this->descricao);
                    $data->bindValue(":pet", $this->pet);
                    $data->bindValue(":dataconsulta", $this->dataconsulta);
                    $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
                    $data->bindValue(":img", $this->img);
                    $data->bindValue(":tipoconsultaid", $this->tipoconsultaid, PDO::PARAM_INT);
                    $data->execute();
                    $this->idconsulta = $pdo->lastInsertId();
                } catch (PDOException $e) {
                    error_log("Erro ao inserir consulta: " . $e->getMessage());
                }
                break;

            case (isset($existe) && $this->ativo):
                // Atualizar consulta existente
                try {
                    $data = $pdo->prepare("UPDATE consulta SET nomevet = :nomevet, descricao = :descricao,tipoconsultaid = :tipoconsultaid, pet = :pet, dataconsulta = :dataconsulta, ativo = :ativo, img = :img WHERE idconsulta = :idconsulta");
                    $data->bindValue(":nomevet", $this->nomevet);
                    $data->bindValue(":descricao", $this->descricao);
                    $data->bindValue(":pet", $this->pet);
                    $data->bindValue(":dataconsulta", $this->dataconsulta);
                    $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
                    $data->bindValue(":img", $this->img);
                    $data->bindValue(":idconsulta", $this->idconsulta, PDO::PARAM_INT);
                    $data->bindValue(":tipoconsultaid", $this->tipoconsultaid  , PDO::PARAM_INT);

                    $data->execute();

                } catch (PDOException $e) {
                    error_log("Erro ao atualizar consulta: " . $e->getMessage());
                }
                break;
        }

        if (isset($existe) && !$this->ativo) {
            try {
                $data = $pdo->prepare("UPDATE consulta SET ativo = :ativo WHERE idconsulta = :idconsulta");
                $data->bindValue(":idconsulta", $this->idconsulta, PDO::PARAM_INT);
                $data->bindValue(":ativo", $this->ativo, PDO::PARAM_INT);
                $data->execute();

            } catch (PDOException $e) {
                error_log("Erro ao atualizar ativo da consulta: " . $e->getMessage());
            }
        }

    }

}