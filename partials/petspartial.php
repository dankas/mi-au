<?php
// Helper para criar os links de ordenação nas colunas da tabela
function getSortLink($column, $title, $currentSortBy, $currentSortOrder) {
    // Se a coluna atual é a de ordenação, inverte a ordem. Senão, o padrão é ASC.
    $order = ($currentSortBy == $column && $currentSortOrder == 'ASC') ? 'desc' : 'asc';
    $arrow = '';
    // Adiciona uma seta para indicar a coluna e a direção da ordenação
    if ($currentSortBy == $column) {
        $arrow = $currentSortOrder == 'ASC' ? ' &#9650;' : ' &#9660;'; // Triângulos para cima/baixo
    }
    // Monta o link com os parâmetros de ordenação
    return "<a href=\"home.php?section=pets&sort=$column&order=$order\" style=\"color: inherit; text-decoration: none;\">$title$arrow</a>";
}
?>
        <h2>Pets cadastrados: </h2>
        <table border="1" cellpadding="8" cellspacing="0" style=" background:#fff;; border-collapse:collapse; width:100%;">
            <thead>
                <tr>
                    <th><?php echo getSortLink('nome', '🔀Nome', $sortBy, $sortOrder); ?></th>
                    <th>Tipo</th>
                    <th>Raça</th>
                    <th><?php echo getSortLink('nascimento', '🔀Data nascimento', $sortBy, $sortOrder); ?></th>
                    <th>Imagem de perfil</th>
                    <th>Bio</th>
                    <th>Perdido</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form method="POST" enctype='multipart/form-data' action="home.php?action=addpet">
                        <td>
                            <input type="text" name="nome" id="nome" required >
                        </td>
                        <td>
                            <select name="tipo" id="tipo" required>
                                <option value="">Selecione</option>
                                <option value=2>Cão</option>
                                <option value=1>Gato</option>

                            </select>
                        </td>
                        <td>
                            <input type="text" name="race" id="race" required>
                        </td>
                        <td> 
                            <input type="date" name="nascimento" id="nascimento" required>
                        </td>
                        <td>
                        <input type='file' name='img_upload' accept='image/*'>
                         </td>
                        <td> 
                            <textarea name="bio" id="bio" required rows="5" cols="30"></textarea>        </td>

                        <td> 
                            <select name="perdido" id="perdido" required>
                                <option value="">Selecione</option>
                                <option value=1>Sim</option>
                                <option value=0>Não</option>
                            </select>
                        </td>
                        <td> 
                            <button type="submit">Adicionar</button>
                        </td>
                    </form>
                </tr>
                <?php listPets($pets); ?>
            </tbody>
        </table>
