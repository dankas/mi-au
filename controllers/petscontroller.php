<?php
function listPets($pets) {
     foreach($pets as $key => $pet){
        echo "<tr>";
        echo "<form method='POST' enctype='multipart/form-data' action='home.php?action=updatePet'>";
        echo "<input type='hidden' name='idpet' value='" . $pet->idpet . "'>";
        echo "<td><input type='text' name='nome' value='" . $pet->nome . "'></td>";
        echo "<td><select name='tipo' id='tipo' required>
                    <option value='Cachorro' " . ($pet->tipo == 2 ? "selected" : "") . ">Cachorro</option>
                    <option value='Gato' " . ($pet->tipo == 1 ? "selected" : "") . ">Gato</option>
              </select>";
        echo "<td><input type='text' name='race' value='" . $pet->race . "'></td>";
        echo "<td><input type='date' name='nascimento' value='" . $pet->nascimento . "'></td>";
        echo "<td>
            <img src='assets/imgs/uploads/" . $pet->imgperfil . "' alt='Imagem de Perfil' style='display:block; width: 50px; height: 50px; object-fit: cover;'>
            <input type='file' name='img_upload' accept='image/*'>
            <input type='hidden' name='existing_imgperfil' value='" . $pet->imgperfil . "'></td>";
        echo "<td><textarea rows='5' cols='30' name='bio'>" . $pet->bio . "</textarea></td>";
        echo "<td>
            <select name='perdido' id='perdido' required>
                <option value='1' " . ($pet->perdido == 1 ? "selected" : "") . ">Sim</option>
                <option value='0' " . ($pet->perdido == 0 ? "selected" : "") . ">Não</option>
            </select>
        </td>";
        echo "<td>
            <button type='submit'>Salvar</button>
              </form>
              <form method='POST' action='home.php?action=deletePet' style='display:inline;'>
            <input type='hidden' name='idpet' value='" . $pet->idpet . "'>
            <button type='submit'>Excluir</button>
              </form>
              </td>";
        echo "</tr>";
     }
}
function getPets($idtutor,$data) {
    try {
        $data->bindValue(":id",$idtutor);
        $data->execute();
        return $data->fetchAll(PDO::FETCH_CLASS,'Pet');
    }    catch (PDOException $e) {
        echo "Erro ao buscar dados: " . $e->getMessage();
    }
}
function addPet($pet,$data) {
    $pet->setAtivo(1);
    $pet->atualizaDb($data);
 
}
function deletePet($pet,$consultas,$data) {
    // softdelete das consultas relacionadas ao pet
    foreach ($consultas as $consulta) {
        if ($consulta->pet == $pet->idpet) {
            deleteConsulta($consulta,$data);
        }
    }  
    $pet->setAtivo(0);
    $pet->atualizaDb($data);

}
function updatePet($pet,$data) {
    $pet->atualizaDb($data);



}

?>