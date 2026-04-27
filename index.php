<?php
require_once './factory/bootstrap.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$usuario = $_SESSION['usuario'];

function e($v)
{
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

$imagem_perfil = "./img/usr/" . ($usuario['imagem'] ?? 'perfil.png');

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Compartilhe dicas sobre plantas">
    <meta name="theme-color" content="#288c62">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Ecompartilhar">
    <meta name="theme-color" content="#288c62">

    <link rel="icon" type="image/png" href="./img/logo/icon-192.png">
    <link rel="apple-touch-icon" href="./img/logo/icon-192.png">
    <link rel="manifest" href="./manifest.json">

    <title>Início</title>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        #conteinerCell {
            height: 485px;
            width: 260px;
            margin: 0 auto;
            background-color: rgb(251, 234, 202);
            border-radius: 15px;
            border: 3px solid #000000;
            position: relative;
        }



        fieldset {
            display: flex;
            justify-content: space-around;
            background-color: #ffffff;
            max-width: 100%;
            height: 10%;
            margin: 0;
            border-radius: 10px 10px 0 0;
            align-items: center;
        }

        #perfil {
            border: 1px solid black;
            border-radius: 30px;
            width: 40px;
            height: 40px
        }

        span {
            display: none;
            font-size: larger;
        }

        #logo {
            width: auto;
            height: 100%;

        }

        h4 {
            font-size: x-small;
        }

        aside {
            display: flex;
            align-items: start;
        }

        nav {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: auto;
            width: 50%;
            background-color: #ffffff;
            padding: 5%;
            border-radius: 0 25px 25px 0;
            border-style: groove;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 0;
            padding: 0;
        }

        button {
            display: flex;
            align-items: center;
            box-shadow: 2px 3px 4px rgb(60, 60, 60);
            background-color: #ffffff;
            border-style: none;
        }

        button:hover {
            transform: translateX(20px);
            transition: transform 1s ease;
        }

        button .tittle {
            margin-left: 5px;
            text-decoration: none;
            color: black;
        }

        button a {
            color: black;
            text-decoration: none;
        }

        .button-group img {
            display: flex;
            justify-content: start;
            width: 30%;
            margin: 0;
            padding: 0;
            filter: grayScale(100%);
        }

        .button-group p {
            display: flex;
            align-items: center;
        }

        #acessorios {
            padding: 5px;
        }

        nav button {
            border-radius: 0 15px 15px 0;
            border-style: groove;
            padding-left: 0;
        }

        #imgPlantaCanto {
            width: 55%;
            min-height: 200px;

            animation: balanco 5s linear infinite;
        }

        @keyframes balanco {
            0% {
                transform: rotate(3deg) translateX(-6px)
            }

            ;

            50% {
                transform: rotate(-5deg) translateX(10px)
            }

            ;

            100% {
                transform: rotate(3deg) translateX(-6px)
            }

            ;
        }

        @keyframes balancoP2 {
            0% {
                transform: rotate(1deg) translateX(-3px)
            }

            ;

            50% {
                transform: rotate(-2deg)
            }

            ;

            100% {
                transform: rotate(1deg) translateX(-3px)
            }

            ;
        }

        #imgPrincipal2 {
            max-width: 90%;
            animation: balancoP2 6s linear infinite;
        }

        footer {
            height: auto;
            width: 100%;
            margin-top: 8%;
            transform: translateY(-18%);

        }

        #img-chao {
            display: block;
            height: auto;
            width: 100%;
            border-radius: 0 0 15px 15px;
        }


        #trosso {
            width: 200px;
            height: 90px;
            background: linear-gradient(to right,
                    #c2c2c2 0%,
                    #000000 10%,
                    #7e331c 20%,
                    #f4d69e 30%,
                    #ffffff 40%,
                    #5cff5c 50%,
                    #26f126 60%,
                    #17b617 70%,
                    #0b8b0b 80%,
                    #008000 90%,
                    #ffc629 100%);
        }





        /* 🔐 POPUP LOGIN */
        #popupLogin {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            display: flex;
            justify-content: center;
            align-items: center;

            background: rgba(0, 0, 0, 0.4);
            border-radius: 15px;
            overflow: hidden;

            z-index: 99999;
        }

        .popup-box {
            background: #ffffff;
            padding: 15px;
            border-radius: 15px;
            width: 90%;
            text-align: center;

            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .popup-box h2 {
            font-size: 20px;
        }

        .popup-box p {
            font-size: 14px;
        }

        .popup-box input {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .popup-box button {
            margin-top: 10px;
            padding: 12px;
            width: 100%;
            font-size: 18px;
            background: #4aa780;
            color: black;
            border-radius: 10px;
            border: none;
        }
    </style>



</head>

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

<body>


    <div id="conteinerCell">






        <fieldset>
            <img id="logo" src="./img/logo/logo.png">
            <img onclick="window.location.href='./view/editarUsr.php'" id="perfil" src="<?php echo $imagem_perfil ?>">
        </fieldset>

        <aside>
            <nav>
                <div class="button-group">
                    <button onclick="window.location.href='./view/projeto/sobre.php'">
                        <img src="./img/btn/sobre.png">
                        <u class="tittle">Sobre</u>
                    </button>
                    <button onclick="window.location.href='./view/plantas/viewPlantas.php'">
                        <img src="./img/btn/trocar.png">
                        <u class="tittle">Trocar</u>
                    </button>
                    <button onclick="window.location.href='./view/plantas/viewPlantas.php.php'">
                        <img src="./img/btn/doar.png">
                        <u class="tittle">Doar</u>
                    </button>
                    <button onclick="window.location.href='./view/acessorios.html'" id="acessorios">
                        <img src="./img/btn/acessorio.png">
                        <u class="tittle">Acessórios</u>
                    </button>
                </div>

                <img id="imgPrincipal2" src="./img/obg/planta2.png">

                <div class="button-group">
                    <button onclick="window.location.href='./view/dicas/dicas.php'">
                        <img src="./img/btn/dicas.png">
                        <u class="tittle">Dicas</u>
                    </button>
                </div>
            </nav>

            <img id="imgPlantaCanto" src="./img/obg/planta.png">

        </aside>
        <span id="nomeUsuario">
            <?= e($usuario['nome']) ?>
        </span>
        <footer>
            <img id="img-chao" src="./img/obg/chao.png">
        </footer>

    </div>

    <script>
        // 📱 Registrar Service Worker para PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('./sw.js')
                    .then((registration) => {
                        console.log('✅ Service Worker registrado:', registration);

                        // Verificar updates a cada 6 horas
                        setInterval(() => {
                            registration.update();
                        }, 6 * 60 * 60 * 1000);
                    })
                    .catch((error) => {
                        console.log('❌ Erro ao registrar Service Worker:', error);
                    });
            });

            // Notificar quando nova versão estiver pronta
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                console.log('🔄 Nova versão do app disponível!');
            });
        }

        // 📲 Detectar se é instalável
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            console.log('📲 App pode ser instalado');

            // Você pode mostrar um botão "Instalar" aqui
            // document.getElementById('installBtn').style.display = 'block';
        });

        // Função para instalar o app (opcional)
        function instalarApp() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('✅ Usuário aceitou instalação');
                    } else {
                        console.log('❌ Usuário recusou instalação');
                    }
                    deferredPrompt = null;
                });
            }
        }
    </script>

</body>

</html>