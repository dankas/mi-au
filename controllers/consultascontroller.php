<?php
function listConsultas($consultas,$pets) {
     foreach($consultas as $key => $consulta){
        echo "<tr>";
        echo "<form method='POST' enctype='multipart/form-data' action='home.php?action=updateConsulta'>";
        echo "<input type='hidden' name='idconsulta' value='" . $consulta->idconsulta . "'>";
        echo "<td><input type='text' disabled name='pet' value='" . (array_find($pets, fn($p) => $p->idpet == $consulta->pet)->nome ?? '') . "'></td>";
        echo "<td><input type='text' name='nomevet' value='" . $consulta->nomevet . "'></td>"; 
        echo "<td>
            <img src='assets/imgs/uploads/" . $consulta->img . "' alt='Imagem da Receita' style='width: 50px; height: 50px; object-fit: cover;'>
            <input type='file' name='imgconsulta' accept='image/*'>
            <input type='hidden' name='existing_imgconsulta' value='" . $consulta->img . "'>
        </td>";
        echo "<td><input type='date' name='dataconsulta' value='" . $consulta->dataconsulta . "'></td>";
        echo "<td><select name='tipo' id='tipo' required>
                    <option value='1' " . ($consulta->tipoconsultaid == 1 ? "selected" : "") . ">Atendimento</option>
                    <option value='2' " . ($consulta->tipoconsultaid == 2 ? "selected" : "") . ">Vacina</option>
              </select>";
        echo "<td><textarea rows='5' cols='30' name='descricao'>" . $consulta->descricao . "</textarea></td>";
        echo "<td>
            <button type='submit'>Salvar</button>
              </form>
              <form method='POST' action='home.php?action=deleteConsulta' style='display:inline;'>
            <input type='hidden' name='idconsulta' value='" . $consulta->idconsulta . "'>
            <button type='submit'>Excluir</button>
              </form>
              </td>";
        echo "</tr>";
     }
}

function getConsultas($data) {
    try {
        $data->execute();
        return $data->fetchAll(PDO::FETCH_CLASS,'Consulta');
    }    catch (PDOException $e) {
        echo "Erro ao buscar dados: " . $e->getMessage();
    }
}

function addConsulta($consulta,$data) {
    $consulta->setAtivo(1);
    $consulta->atualizaDb($data);
 
}

function updateConsulta($consulta, $data) {
    $consulta->atualizaDb($data);
}

function deleteConsulta($consulta, $data) {
    $consulta->setAtivo(0);
    $consulta->atualizaDb($data);
}


?>