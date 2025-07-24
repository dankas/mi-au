<?php
function listFotos($fotos,$pets) {
     foreach($fotos as $key => $foto){
        echo "<tr>";
        echo "<input type='hidden' name='idfoto' value='" . $foto->idfoto . "'>";
        echo "<td><input type='text' disabled name='pet' value='" . (array_find($pets, fn($p) => $p->idpet == $foto->idpet)->nome ?? '') . "'></td>";
        echo "<td><a href='assets/imgs/uploads/" . $foto->img . "' target='_blank'><img src='assets/imgs/uploads/" . $foto->img . "' alt='Imagem da Foto' style='width: 400px; height: 400px; object-fit: cover;'></a></td>";
        echo "<td><input type='text' name='datetimeregistro' value='" . $foto->datetimeregistro . "'></td>";
        echo "<td>

              
            <form method='POST' action='home.php?action=deleteFoto' style='display:inline;'>
            <input type='hidden' name='idfoto' value='" . $foto->idfoto . "'>
            <button type='submit'>Excluir</button> 
              </form>
              </td>";
        echo "</tr>";
     }
}

function getFotos($data) {
    try {
        $data->execute();
        return $data->fetchAll(PDO::FETCH_CLASS,'Foto');
    }    catch (PDOException $e) {
        echo "Erro ao buscar dados: " . $e->getMessage();
    }
}

function addFoto($foto,$data) {
    $foto->setAtivo(1);
    $foto->atualizaDb($data);

}


function deleteFoto($foto, $data) {
    $foto->setAtivo(0);
    $foto->atualizaDb($data);
}


?>