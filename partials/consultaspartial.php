        <h2>Consultas cadastradas: </h2>
        <table border="1" cellpadding="8" cellspacing="0" style="background:#fff; border-collapse:collapse; width:100%;">
                <thead>
                        <tr>
                                <th>Pet</th>
                                <th>Veterinario</th>
                                <th>Imagem Receita</th>
                                <th>Data da consulta</th>
                                <th>Tipo de Consulta </th>
                                <th>Descrição</th>
                                <th></th>
                        </tr>
                </thead>
                <tbody>
                        <tr>
                                <form method="POST" enctype='multipart/form-data' action="home.php?action=addConsulta">
                                        <td>
                                                <select name="pet" id="pet" required>
                                                        <option value="">Selecione</option>
                                                        <?php foreach ($pets as $p): ?>
                                                                if ($p->ativo) {
                                                                <option value="<?php echo $p->idpet; ?>"><?php echo $p->nome; ?></option>
                                                                }
                                                        <?php endforeach; ?>
                                                </select>
                                        </td>
                                        <td>
                                                <input type="text" name="nomevet" id="nomevet" required>
                                        </td>
                                        <td>
                                                <img src='assets/imgs/uploads/" . $consulta->img . "' alt='Imagem da Receita' style='width: 50px; height: 50px; object-fit: cover;'>
                                                <input type='file' name='imgconsulta' accept='image/*'>
                                                <input type='hidden' name='existing_imgconsulta' value='" . $consulta->img . "'>
                                        </td>
                                        <td>
                                                <input type="date" name="dataconsulta" id="dataconsulta" required>
                                        </td>
                                        <td>
                                                <select name="tipo" id="tipo" required>
                                                        <option value="">Selecione</option>
                                                        <option value=1>Atendimento</option>
                                                        <option value=2>Vacina</option>

                                                </select>
                                        </td>
                                        <td>
                                                <textarea name="descricao" id="descricao" required rows="5" cols="30"></textarea>
                                        </td>
                                        <td>
                                                <button type="submit">Adicionar</button>
                                        </td>
                                </form>
                        </tr>
                        
                        <?php listConsultas($consultas, $pets); ?>
                </tbody>
        </table>