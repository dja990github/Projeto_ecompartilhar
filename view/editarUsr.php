<?php
require_once '../factory/bootstrap.php';

$usuario = $_SESSION['usuario'] ?? [];

function e($v)
{
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

function formatarTelefone($tel)
{
    $tel = preg_replace('/\D/', '', $tel);

    if (strlen($tel) === 11)
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $tel);

    if (strlen($tel) === 10)
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $tel);

    return $tel;
}

$img = "../img/usr/" . ($usuario['imagem'] ?? 'perfil.png');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>

    <script>
        function atualizarStatus() {
            if (!navigator.onLine) {
                console.log("📴 Offline");
            } else {
                console.log("🌐 Online");
            }
        }

        window.addEventListener("online", atualizarStatus);
        window.addEventListener("offline", atualizarStatus);

        atualizarStatus();
    </script>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        :root {
            --alturaNav: 485;

            --verde-escuro: #288c62;
            --verde-medio: #4aa780;
            --verde-claro: #95d5b2;
            --bege: #fbeaca;
            --cinza: #37664f;
            --white: #FFFFFF;
        }

        #conteinerCell {
            position: relative;
            height: 485px;
            background-color: var(--bege);
            border-radius: 10px;
            border: 3px solid #000000;
            overflow-y: scroll;
            margin: 0 auto;

            max-width: 260px;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .conteinerDados-geral {
            margin: 8px;
            border: 1px solid black;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }


        .sessao-foto {
            margin-top: 20px;
        }

        .sessao-foto img {
            display: block;
            margin: 3px auto;
            min-height: 100px;
            min-width: 100px;
        }

        .foto-wrapper {
            z0index: 999;
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto;
        }

        /* imagem normal */
        #foto-perfil {

            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* folhas por cima */
        .foto-wrapper::before {
            content: "";
            position: absolute;
            inset: -20px;
            /* faz a moldura invadir a imagem */
            background: url("../img/obg/imgBordaFolhas.png") no-repeat center/contain;
            pointer-events: none;
            z-index: 1;
        }

        .sessao-foto div {
            display: flex;
            justify-content: center;

        }

        .sessao-foto-conteiner {
            bsckground: white;
        }

        #sessao-foto-cont2 {
            margin-top: 30px;
        }

        #btn-alterarFoto {

            margin-block: 5px;
        }

        #btn-alterar {
            border: 1px solid #2E7D32;
            padding: 10px 20px;
        }

        #btn-cancelar {
            background-color: white;
            color: #226e26;
            border: 1px solid #2E7D32;
            padding: 10px 20px;
        }


        .sessao-opcoes {
            font-size: 80%;
        }

        ul {
            margin: 10px;
            padding: 0;
        }

        li {
            display: flex;
            align-items: center;
            list-style: none;
            border-bottom: 2px solid black;
            margin-block: 10px;
            gap: 5px;
            flex-wrap: wrap;

        }


        button {
            background: #2E7D32;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 2px 3px 4px black;
            font-size: 11px;
        }

        button:hover {
            background: #1B5E20;
        }


        .nav {
            height: 50px;
            width: 100%;
            background-color: rgb(251, 234, 202);
        }

        nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100%;
        }

        nav img {
            height: 30px;
            width: auto;
            background: none;
        }

        .sessao-sair {
            position: absolute;
            top: 20px;
            right: 5px;
        }

        #btn-sair {
            padding: 5px 10px;
        }

        #popup {
            margin: 0;
            z-index: 1;
            display: none;
            /* ESSENCIAL */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            justify-content: center;
            align-items: center;

            background: rgba(0, 0, 0, 0.4);
        }

        .popup-box {

            background-color: wheat;
            text-align: center;
            padding: 20px;
            border: 10px solid green;
            border-image: url(../img/obg/fundoFolhas.jpg);
            border-image-slice: 40;
            border-radius: 30px;
        }

        #popupSair {
            z-index: 9999;
            display: none;
            /* ESSENCIAL */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            justify-content: center;
            align-items: center;

            background: rgba(0, 0, 0, 0.4);
        }

        .popupSair-box {
            background-color: wheat;
            text-align: center;
            padding: 10px;
            max-width: 100%;
            border: 8px solid green;
            border-image: url(../img/obg/fundoFolhas.jpg);
            border-image-slice: 40;
            border-radius: 30px;
        }

        .conteinerbtn-popupSair {
            display: flex;
            justifi-content: space-between;

        }

        .conteinerbtn-popupSair button {
            margin-inline: 20px;
        }

        #btn-sair-logout {
            border-radius: 15px 15px 0 0;
            padding: 6px;
        }

        #imgHome {
            display: block;
            width: 30px;
            height: auto;
        }
    </style>
</head>



<body>

    <div id="conteinerCell">

        <fieldset class="conteinerDados-geral">

            <div class="sessao-sair">
                <button onclick="window.location.href='../index.php'">
                    <img id="imgHome" src="../img/btn/casa.png">

                </button>
            </div>

            <section class="sessao-foto">
                <div id="sessao-foto-cont1" class="sessao-foto-conteiner">
                    <div class="foto-wrapper">
                        <img id="foto-perfil" src="../img/usr/<?= $usuario['imagem'] ?? 'perfil.png' ?>" width="100">
                    </div>
                </div>
                <div id="sessao-foto-cont2">
                    <button onclick="window.location.href='./editarFoto.php'">
                        Alterar foto
                    </button>
                </div>
            </section>

            <div class="sessao-opcoes">
                <ul>
                    <li>
                        Nome:<span id="nome"><?= $usuario['nome'] ?? '' ?></span>
                    </li><button onclick="abrirPopup('nome','<?= $usuario['nome'] ?>')">Alterar</button>

                    <li>
                        Email: <span id="email"><?= $usuario['email'] ?? '' ?></span>
                    </li><button onclick="abrirPopup('email','<?= $usuario['email'] ?>')">Alterar</button>

                    <li>
                        Tel: <span id="telefone">
                            <?= formatarTelefone($usuario['telefone'] ?? '') ?>
                        </span>
                    </li>
                    <button onclick="abrirPopup('telefone','<?= formatarTelefone($usuario['telefone'] ?? '') ?>')">
                        Alterar
                    </button>
                </ul>
            </div>

            <div id="popup">
                <div class="popup-box">

                    <h3 id="titulo">Alterar</h3>

                    <form method="POST" id="formUser">
                        <input type="hidden" name="campo" id="campo">
                        <input type="text" placeholder="(11) 98765-4321" name="valor" id="valor"
                            style="width: 90%; padding:5px;">

                        <br><br>

                        <button type="submit">Salvar</button>
                        <button type="button" onclick="fecharPopup()">Cancelar</button>
                    </form>

                </div>
            </div>

            <div id="popupSair">
                <div class="popupSair-box">

                    <h3 id="tituloSair">Tem certeza que deseja sair?</h3>
                    <p>(isso vai apagar sua conta do nosso sistema)</p>

                    <section class="conteinerBTN-popupSair">
                        <button onclick="window.location.href = '../api/logout.php'" id="btnSair">Caonfirmar</button>
                        <button onclick="fecharPopup()">Cancelar</button>
                    </section>

                </div>
            </div>

        </fieldset>

        <button id="btn-sair-logout" onclick="confirmarLogout()">❌ Sair(logout do sistema) ❌</button>
    </div>


    <script>
        const popup = document.getElementById("popup");
        const popupSair = document.getElementById("popupSair");
        const campoInput = document.getElementById("campo");
        const valorInput = document.getElementById("valor");
        const titulo = document.getElementById("titulo");

        function abrirPopup(campo, valor) {
            popup.style.display = "block";
            campoInput.value = campo;
            valorInput.placeholder = 'Novo ' + campo;
            titulo.textContent = 'Alterar ' + campo;
        }

        function fecharPopup() {
            popup.style.display = "none";
            popupSair.style.display = "none";
        }

        document.getElementById("formUser").addEventListener("submit", async (e) => {
            e.preventDefault();

            let valor = valorInput.value;

            if (campoInput.value === "telefone") {
                valor = valor.replace(/\D/g, "");
            }

            const formData = new FormData();
            formData.append("campo", campoInput.value);
            formData.append("valor", valor);

            const res = await fetch("../api/salvarUsr.php", {
                method: "POST",
                body: formData
            });

            const data = await res.json();

            if (data.sucesso) location.reload();
            else alert("Erro");
        });

        function confirmarLogout() {
            popupSair.style.display = "block";
        }
    </script>

</body>

</html>