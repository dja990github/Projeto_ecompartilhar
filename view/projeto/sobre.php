<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "../../factory/conexao.php";

$usuario = $_SESSION['usuario'] ?? [];

$imagem = "../../img/usr/perfil.png";

if (!empty($usuario['imagem'])) {
    $imagem = "../../img/usr/" . $usuario['imagem'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        :root {
            --verde-escuro: #288c62;
            --verde-medio: #4aa780;
            --verde-claro: #95d5b2;
            --bege: #f1faee;
            --cinza: #37664f;
            --white: #FFFFFF;
        }

        #conteinerCell {
            position: relative;
            height: 485px;
            width: 260px;
            background-color: rgb(251, 234, 202);
            border-radius: 15px;
            border: 3px solid #000000;
            overflow-y: scroll;
            margin: 0 auto;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bege);
            color: var(--cinza);
            line-height: 1.6;
            overflow-y: hidden;
        }

        header {
            background: linear-gradient(rgba(82, 170, 133, 0.85), rgba(27, 67, 50, 0.85)),
                url(../../img/banner/teste2.webp) no-repeat center/cover;
            color: white;
            padding: 120px 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        header p {

            font-size: 1.2rem;
            max-width: 700px;
            margin: auto;


        }

        #sobre {
            display: block;

        }

        #sobre p {

            text-align: justify;
        }

        nav {
            display: flex;
            justify-content: space-around;
            background-color: #FFFFFF;
            padding: 15px;
            text-align: center;

        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-weight: bold;
            transition: 0.3s;
        }

        nav a:hover {
            color: var(--verde-claro);
        }

        #logo {
            width: 60%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #logo-img {
            display: flex;
            width: 100%;
            height: auto;
            text-align: left;
        }

        .conteiner-perfil {
            width: 60px;
            max-height: 60px;
            top: 50%;
            margin: 0;
            background-color: rgb(255, 255, 255);
            padding: 0;
            overflow: hidden;
            border-radius: 35px;
            border: 1px solid black;

            display: flex;
            justify-content: center;
            align-item: center;

        }

        #perfil {

            object-fit: cover;
            width: auto;
            height: 100%;


        }

        section {
            padding: 60px 20px;
            max-width: 700px;
            margin: auto;
        }

        section h2 {
            color: var(--verde-escuro);
            margin-bottom: 20px;
            font-size: 2rem;
        }

        #pesquisa {
            position: relative;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .card {
            background: white;
            max-width: 209px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-bottom: 15px;
            color: var(--verde-escuro);
        }

        .cta {
            background-color: var(--verde-medio);
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        .cta h2 {
            margin-bottom: 20px;
            color: white;
        }

        .cta button {
            background-color: white;
            color: var(--verde-escuro);
            border: none;
            padding: 15px 30px;
            font-size: 1rem;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 10px;
        }

        .cta button:hover {
            background-color: var(--verde-claro);
        }

        footer {
            background-color: var(--verde-escuro);
            color: white;
            text-align: center;
            padding: 30px 20px;
            font-size: 0.9rem;
        }

        #popup-participar {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;

            background-color: white;
            padding: 10px;
            border-radius: 20px;
            margin-top: 3%;
        }


        @media (max-width: 768px) {
            header h1 {
                font-size: 2.2rem;
            }
        }
    </style>
    <div id="conteinerCell">

        <nav>
            <div id="logo" onclick="window.location.href='../../index.php'">
                <img id="logo-img" src="../../img/logo/logo.png">
                <p>⬑ voltar</p>
            </div>
            <section class="conteiner-perfil">
                <img onclick="window.location.href='../editarUsr.php'" id="perfil" src="<?php echo $imagem; ?>">
            </section>
        </nav>

        <header>
            <h1>Projeto <u>Eco</u>mpartilhar</h1>
            <p>Ecologia, Meio Ambiente e Colaboratividade para um Futuro Sustentável</p>
        </header>

        <section id="sobre">
            <h2>🌿 Sobre o Projeto</h2>
            <p>
                O projeto de pesquisa Ecompartilhar surge a partir da preocupação
                com o grande desperdício e o uso inadequado das plantas.
                A proposta dessa plataforma é justamente proporcionar
                um ambiente onde as pessoas possam doar ou trocar suas
                plantas, muitas vezes abandonadas e destinadas a se
                perder. Assim, incentivamos a colaboração e contribuímos
                para um meio ambiente mais rico, sustentável e com mais vida.
            </p>
        </section>

        <section id="pesquisa">
            <h2>🔬 Linhas de Pesquisa</h2>
            <div class="cards">
                <div class="card">
                    <h3>🌳 Ecologia de Ecossistemas</h3>
                    <p>Meuniavis ipsum dolor sit amet consectetur adipisicing elit. Vero quo illo inventore quam placeat
                        .</p>
                </div>
                <div class="card">
                    <h3>🌊 Sustentabilidade Ambiental</h3>
                    <p>labore voluptatem velit sequi dolore unde quia, blanditiis nulla, a temporibus voluptatem
                        magni.!.</p>
                </div>
                <div class="card">
                    <h3>🤝 Ciência Colaborativa</h3>
                    <p>Apsum dolor sit amet consectetur adipisicing elit. Mollitia.</p>
                </div>
            </div>
        </section>


        <section class="cta">
            <h2>Participe do Projeto</h2>
            <p>Contribua com sua participação, ajude-nos a fazer um mundo mais ecológico.</p>
            <button id="quero-participar">Quero Participar</button>
        </section>

        <footer id="contato">
            <p>📧 danielcrajo@gmail.com</p>
            <p>© 2026 Projeto Ecompartilhar</p>
            <p>Direitos reservados</p>
        </footer>

    </div>
    <script>
        $("#popup-participar").hide();
        $(document).ready(function () {
            $("#quero-participar").click(function () {
                $("#popup-participar").show(500);
            });
        });

    </script>
</body>

</html>