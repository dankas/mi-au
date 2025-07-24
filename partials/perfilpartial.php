        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Senha</th>
                    <th>Telefone</th>
                    <th>Imagem de Perfil</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form action="home.php?action=updateUser" method="post">
                    <td><input type='text' name='username' value='<?php echo $user->username; ?>'></td>
                    <td><input type='text' name='password' value='<?php echo $user->password; ?>'></td>
                    <td><input type='text' name='telefone' value='<?php echo $user->telefone; ?>'></td>
                    <td><img src="assets/imgs/profiles/<?php echo $user->imgprofile; ?>" alt="Imagem de perfil" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;"></td>
                    <td>
                            <input type="hidden" name="id" value="<?php echo $user->iduser; ?>">
                            <input type="hidden" name="imgprofile" value="<?php echo $user->imgprofile; ?>">
                            <button type="submit">Salvar</button>
                    </td>
                    </form>
                </tr>
            </tbody>
