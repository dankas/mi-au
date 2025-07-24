<?php
session_start();
require_once 'core/Database.php';
require_once 'core/uploads.php';
require_once 'models/user.php';
require_once 'models/pet.php';
require_once 'models/consulta.php';
require_once 'models/fotos.php';
require_once 'userscontroller.php';
require_once 'petscontroller.php';
require_once 'userscontroller.php';
require_once 'consultascontroller.php';
require_once 'fotoscontroller.php';

// parâmetros de ordenação para os pets
$allowedSortColumns = ['nome', 'nascimento', 'datetimeregistro'];
$allowedSortOrders = ['ASC', 'DESC'];

$sortBy = $_GET['sort'] ?? 'datetimeregistro';
$sortOrder = strtoupper($_GET['order'] ?? 'DESC');

if (!in_array($sortBy, $allowedSortColumns)) {
    $sortBy = 'datetimeregistro'; // Valor padrão seguro
}
if (!in_array($sortOrder, $allowedSortOrders)) {
    $sortOrder = 'DESC'; // Valor padrão seguro
}

$pets  = getPets($_SESSION['userId'], $pdo->prepare("SELECT * FROM pet JOIN tipo ON pet.tipoid = tipo.idtipo WHERE tutor = :id AND ativo = 1 ORDER BY $sortBy $sortOrder"));
$user = getUser($_SESSION['userId'], $pdo->prepare("SELECT * FROM users WHERE iduser = :id"));
$consultas = getConsultas( $pdo->prepare("SELECT * FROM consulta JOIN tipo_consulta ON consulta.tipoconsultaid = tipo_consulta.idtipoconsulta WHERE ativo = 1 ORDER BY datetimeregistro"));
$fotos = getFotos( $pdo->prepare("SELECT * FROM fotos WHERE ativo = 1 ORDER BY datetimeregistro"));


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'exportJson' )  {
    $commitMessage = 'commit ' . date('Y-m-d H:i:s').'';
    exportJson("pets", json_encode($pets));
    exportJson("user", json_encode($user));
    exportJson("consultas", json_encode($consultas));
    exportJson("fotos", json_encode($fotos));
    $gitaddMessage = system(('git add .'));
    $gitcommitMessage = system('git commit -m "' . $commitMessage . '"'); // This line was commented out
    $gitpushMessage = system('git push origin master '); // This line was commented out
    header('Location: home.php?section=home&message=Dados exportados com sucesso ' . $commitMessage . ' ' . $gitaddMessage . ' ' . $gitcommitMessage . ' ' . $gitpushMessage . ' ');
    exit();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'updateUser') {
    $user->setUsername($_POST['username']);
    $user->setTelefone($_POST['telefone']);
    $user->setImgprofile($_POST['imgprofile']);
    $user->setPassword($_POST['password']);
    updateUser($user,$pdo);
    header('Location: home.php?section=home&message=Usuário atualizado com sucesso');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'addpet') {
    $pet = new Pet;
    $pet->setNome($_POST['nome']);
    $pet->setRace($_POST['race']);
    $pet->setBio($_POST['bio']);
    $pet->setNascimento($_POST['nascimento']);
    $pet->setTipo(($_POST['tipo'] === 'Gato' ) ? 1 : 2 );
    $pet->setTutor( $_SESSION['userId']);
    $pet->setPerdido($_POST['perdido']);
    $novaImgPerfil = uploadImage($_FILES['img_upload'], NULL);
    $pet->setImgperfil($novaImgPerfil);
    array_push($pets, $pet);
    addPet($pet,$pdo);
    header("Location: home.php?section=pets&message=Pet adicionado com sucesso&sort=$sort_by&order=$sort_order");
    exit();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'updatePet') {
    $pet = array_find($pets, fn($p) => $p->idpet == $_POST['idpet']);
    $novaImgPerfil = uploadImage($_FILES['img_upload'], NULL);
    if ($novaImgPerfil) {
        $pet->setImgperfil($novaImgPerfil);
    } else if ($_POST['existing_imgperfil']) {
        $pet->setImgperfil($_POST['existing_imgperfil']);
    };
    $pet->setNome($_POST['nome']);
    $pet->setRace($_POST['race']);
    $pet->setBio($_POST['bio']);
    $pet->setNascimento($_POST['nascimento']);
    $pet->setTipo(($_POST['tipo'] === 'Gato' ) ? 1 : 2 );
    $pet->setPerdido($_POST['perdido']);
    $pet->setTutor( $_SESSION['userId']);
    updatePet($pet,$pdo);
    header("Location: home.php?section=pets&message=Pet atualizado com sucesso&sort=$sort_by&order=$sort_order");
    exit();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'deletePet') {
   $pet = array_find($pets, fn($p) => $p->idpet == $_POST['idpet']);
   deletePet($pet,$consultas,$pdo);
   header("Location: home.php?section=pets&message=Pet excluído com sucesso&sort=$sort_by&order=$sort_order");
   exit();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'addConsulta')  {
    $novaImg = uploadImage($_FILES['imgconsulta'], NULL);
    $consulta = new Consulta;
    $consulta->setNomevet($_POST['nomevet']);
    $consulta->setDescricao($_POST['descricao']);
    $consulta->setPet($_POST['pet']);
    $consulta->setDataconsulta($_POST['dataconsulta']);
    $consulta->setImg($novaImg);
    array_push($consultas, $consulta);
    addConsulta($consulta,$pdo);
    header('Location: home.php?section=consultas&message=Consulta registrada com sucesso');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'updateConsulta') {
    $consulta = array_find($consultas, fn($c) => $c->idconsulta == $_POST['idconsulta']);
    $consulta->setNomevet($_POST['nomevet']);
    $consulta->setDescricao($_POST['descricao']);
    $consulta->setDataconsulta($_POST['dataconsulta']);
    $novaImg = uploadImage($_FILES['imgconsulta'], NULL);
    if ($novaImg) {
        $consulta->setImg($novaImg);
    } else {
        $consulta->setImg($_POST['existing_imgconsulta']);
    };
    updateConsulta($consulta,$pdo);
    header('Location: home.php?section=consultas&message=Consulta atualizada com sucesso');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'deleteConsulta') {
    $consulta = array_find($consultas, fn($c) => $c->idconsulta == $_POST['idconsulta']);
    deleteConsulta($consulta,$pdo);
    header('Location: home.php?section=consultas&message=Consulta excluída com sucesso');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'addFoto') {
    $nomePet = array_find($pets, fn($p) => $p->idpet == $_POST['pet']);
    $novaImg = uploadImage($_FILES['foto'],$nomePet->nome);
    $foto = new Foto;
    $foto->setidpet($_POST['pet']);
    $foto->setImg($novaImg);
    array_push($fotos, $foto);
    addFoto($foto,$pdo);
    header('Location: home.php?section=fotos&message=Foto adicionada com sucesso');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'deleteFoto') {
    $foto = array_find($fotos, fn($f) => $f->idfoto == $_POST['idfoto']);
    deleteFoto($foto,$pdo);
    header('Location: home.php?section=fotos&message=Foto excluída com sucesso');
    exit();
}

function exportJson($nome,$json) {
    $fp = fopen("export/".$nome.".json","wb");
    fwrite($fp,$json);
    fclose($fp);
}



?>