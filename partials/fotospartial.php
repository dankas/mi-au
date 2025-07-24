        <h2>Consultas cadastradas: </h2>
        <table border="1" cellpadding="8" cellspacing="0" style="background:#fff; border-collapse:collapse; width:100%;">
                <thead>
                        <tr>
                                <th>Pet</th>
                                <th>imagem</th>
                                <th>Data</th>
                                <th></th>
                        </tr>
                </thead>
                <tbody>
                        <tr>
                                <form method="POST" enctype='multipart/form-data' action="home.php?action=addFoto">
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
                                                <input type='file' name='foto' accept='image/*'>
                                        </td>
                                        <td>
                                                <input type="date" name="data" id="data" required>
                                        </td>
                                        <td>
                                                <button type="submit">Adicionar</button>
                                        </td>
                                </form>
                        </tr>
                        
                        <?php listFotos($fotos, $pets); ?>
                </tbody>
        </table>