<?php
require_once '../factory/bootstrap.php';

$conn = Caminho::getConn();
$usuario = $_SESSION['usuario'];

$foto = "../img/usr/" . ($usuario['imagem'] ?? 'perfil.png');

/* SE FOR ANÔNIMO → PRECISA VIRAR USUÁRIO */
if (empty($usuario['id'])) {

    $token = bin2hex(random_bytes(32));

    $stmt = $conn->prepare("
        INSERT INTO tbusuarios (nome, token_login)
        VALUES (:nome, :token)
    ");

    $stmt->execute([
        ':nome' => $usuario['nome'],
        ':token' => $token
    ]);

    $_SESSION['usuario']['id'] = $conn->lastInsertId();
    $_SESSION['usuario']['token_login'] = $token;
    $_SESSION['usuario']['anonimo'] = false;

    setcookie("login_token", $token, time() + (86400 * 30), "/");
}

/* UPLOAD */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_FILES['imagem']['tmp_name'])) {

        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $permitidas)) {
            $erro = "Formato inválido";
        } else {

            $nome = uniqid() . "." . $ext;
            $destino = "../img/usr/" . $nome;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {

                $stmt = $conn->prepare("UPDATE tbusuarios SET imagem = :img WHERE id = :id");

                $stmt->execute([
                    ':img' => $nome,
                    ':id' => $_SESSION['usuario']['id']
                ]);

                $_SESSION['usuario']['imagem'] = $nome;

                header("Location: editarUsr.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Foto</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        #conteinerCell {
            position: relative;
            height: 485px;
            width: 260px;
            background-color: #fbeaca;
            border-radius: 15px;
            border: 3px solid #000;
            overflow: hidden;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card {
            background:
                url(../img/banner/teste4.png) no-repeat left/cover;

            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #2E7D32;
            margin-bottom: 15px;
        }

        /* FOTO */
        .foto-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 20px auto;
        }

        .preview {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            margin-block: 30px;
        }

        /* moldura */
        .foto-wrapper::before {
            content: "";
            position: absolute;
            inset: -20px;
            background: url("../img/obg/imgBordaFolhas.png") no-repeat center/contain;
            pointer-events: none;
        }

        /* INPUT FILE */
        input[type="file"] {
            display: none;
        }

        .custom-file {
            display: inline-block;
            background: #2E7D32;
            color: white;
            padding: 10px 18px;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 15px;
        }

        button {
            margin: 10px 0;
            background: #2E7D32;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 20px;
            cursor: pointer;
        }

        .secondary {
            background: white;
            color: #2E7D32;
            border: 1px solid #2E7D32;
        }

        /* NAV */
        .nav {
            background: #fbeaca;
            padding: 5px 0;
        }

        nav {
            display: flex;
            justify-content: space-around;
        }

        nav img {
            height: 30px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.getElementById('fileInput').addEventListener('change', function (event) {
                const file = event.target.files[0];

                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert("Selecione um arquivo de imagem válido.");
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('preview').style.backgroundImage =
                        `url('${e.target.result}')`;
                };

                reader.readAsDataURL(file);
            });

        });
    </script>
</head>



<body>

    <div id="conteinerCell">

        <div class="card">

            <h2>🌿 Alterar Foto</h2>

            <div class="foto-wrapper">
                <div class="preview" id="preview" style="background-image: url('<?= $foto ?>');">
                </div>
            </div>

            <?php if (isset($_GET['sucesso'])): ?>
                <p style="color:green;">Foto atualizada com sucesso 🌿</p>
            <?php endif; ?>

            <?php if (isset($erro)): ?>
                <p style="color:red;"><?= $erro ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

                <label for="fileInput" class="custom-file">
                    Escolher imagem
                </label>

                <input type="file" name="imagem" id="fileInput" accept="image/*">

                <br>

                <button type="submit">Salvar</button>

                <br>

                <button type="button" class="secondary" onclick="window.location.href='./editarUsr.php'">
                    Cancelar
                </button>

            </form>

        </div>

        <!-- NAV -->
        <section class="nav">
            <nav>
                <img src="../img/btn/casa.png" onclick="location.href='../index.php'">
                <img src="../img/btn/trocar.png">
                <img src="../img/btn/doar.png">
                <img src="../img/btn/acess.png">
            </nav>
        </section>

    </div>
</body>

</html>