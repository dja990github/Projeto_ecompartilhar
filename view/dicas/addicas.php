<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar dicas</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

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
            width: 260px;
            background-color: var(--bege);
            border-radius: 15px;
            border: 3px solid #000000;
            overflow-y: scroll;
            margin: 0 auto;
            box-sizing: border-box;
        }


        .menu-lateral {
            position: absolute;
            background-color: #dd8046;
            width: 70%;
            height: 100%;
            top: 0;
            left: -70%;
            z-index: 900;
            transition: left 0.5s ease;
        }

        .menu-lateral.ativo {
            left: 0;
        }

        #logo-manu-lateral {
            position: absolute;
            bottom: 0;
            width: 90%;
            height: max-content;
        }

        nav {
            margin: 0 0 0 10px;
        }

        nav img {
            height: 30px;
            width: auto;
            background: none;
            margin-inline: 5px 7px;

        }

        nav h3 {
            width: fit-content;
        }

        #cnteinerPesquisa {
            z-index: 5;
            position: fixed;
        }

        .sessaoPesquisa {
            display: flex;
            justify-content: space-between;
            min-height: 5%;
            background-color: var(--verde-escuro);
            align-items: center;
            border-radius: 10px 10px 0 0;

        }

        .sessaoPesquisaX {
            display: flex;
            justify-content: space-between;
            background-color: var(--verde-escuro);
            min-height: 5%;
            align-items: center;
        }

        #btn-menuX {
            max-width: 50px;
            height: auto;

        }

        #btn-menu {
            max-width: 50px;
            height: auto;
        }

        #btn-voltar {
            background: none;
            border: none;
            padding: 0;
        }

        #btnnomenu {
            max-width: 50px;
            height: auto;
        }

        #pesquisa {
            display: flex;
            align-items: center;

        }

        #banner {
            flex: 0 0 auto;
            width: 150px;
            height: 35px;
            border: none;
        }


        h3 {
            margin: 0;
            padding: 0;
        }

        fieldset {
            width: 90%;
            display: inline;
            border: 9px solid transparent;
            border-image: url(../../img/obg/bordaFolhas.png);
            border-image-slice: 80;
            box-sizing: border-box;
        }

        .cards {
            position: relative;
            margin-block: 15px;
            padding: 0;
            max-width: 100%;
            left: 50%;
            transform: translateX(-50%);

        }

        .card-show {
            display: flex;
            align-items: center;
        }

        .seta {
            margin: 0;
            padding: 0;
            width: 20%;
        }

        .seta-img {
            transform: rotate(0deg);
            width: 100%;
            transition: transform 0.3s ease;
        }

        .cards.ativo .seta-img {
            transform: rotate(-90deg);
        }

        .titulo {
            margin-left: 10px;
            width: 80%;
        }


        .card-txt {
            display: flex;
            width: 100%;
            height: 100%;
            overflow: hidden;
            transform: height 1s ease;
            margin: 4px;
        }

        .form-txt-dica {
            width: 100%;
            min-height: 120px;

            padding: 10px;
            font-size: 14px;

            border-radius: 8px;
            border: 1px solid #ccc;

            resize: vertical;
            box-sizing: border-box;

            overflow-x: hidden;

            white-space: pre-wrap;
            overflow-wrap: break-word;
            word-break: break-word;
            /* ESSA aqui resolve palavras gigantes */
        }

        .card-txt {
            width: 100%;
            overflow-x: hidden;
        }

        .form-txt-dica:focus {
            border-color: var(--verde-escuro);
            box-shadow: 0 0 5px rgba(40, 140, 98, 0.5);
        }

        .confirmar {
            display: flex;
            justify-content: center;

        }

        #confirmar {
            padding: 20px 15px;
            background-color: var(--verde-escuro);
            border-radius: 30%;
            font-size: large;
        }
    </style>
    <div id="conteinerCell">

        <aside class="menu-lateral">
            <button id="btn-voltar">
                <img id="btnnomenu" src="../../img/btn/menu.png">
            </button>

            <nav>
                <h3 onclick="window.location.href='../../index.php '"><img src="../../img/btn/casa.png">Início</h3>
                <h3 onclick="window.location.href='../../view/doar.php'"><img src="../../img/btn/doar.png">Doar</h3>
                <h3 onclick="window.location.href='../../view/acessorios.php'"><img
                        src="../../img/btn/acess.png">Acessórios</h3>
                <section onclick="window.location.href='../../index.php'">
                    <img id="logo-manu-lateral" src="../../img/logo/logo-black.png">
                </section>
            </nav>
        </aside>

        <section id="cnteinerPesquisa">
            <aside class="sessaoPesquisa">
                <img id="btn-menu" src="../../img/btn/menu.png">

                <img id="banner" src="../../img/logo/logo.png">
            </aside>
        </section>

        <aside class="sessaoPesquisaX">
            <article>
                <img id="btn-menuX" src="../../img/btn/menu.png">
            </article>
        </aside>

        <form method="POST" action="../../api/salvar_dicas.php" id="formDica">
            <fieldset class="cards">

                <section class="card-show">
                    <div class="seta">
                        <img class="seta-img" src="../../img/obg/seta.png">
                    </div>

                    <h3 class="titulo">
                        <input name="titulo" style="width:90%;" type="text" placeholder="Título">
                    </h3>
                </section>

                <div class="card-txt">
                    <textarea name="dica" class="form-txt-dica" placeholder="Digite sua dica..."></textarea>
                </div>

            </fieldset>

            <section class="confirmar">
                <button id="confirmar" type="submit">Confirmar e Salvar</button>
            </section>
        </form>

    </div>
    <script>

        $(document).ready(function () {

            $(".card-show").click(function () {
                const card = $(this).closest(".cards");
                const txt = card.find(".card-txt");

                $(".cards").not(card).removeClass("ativo")
                    .find(".card-txt").css("height", "0px");

                if (card.hasClass("ativo")) {
                    txt.css("height", txt.prop("scrollHeight") + "px");
                    card.removeClass("ativo");
                } else {
                    txt.css("height", "0px");
                    card.addClass("ativo");
                }
            });



        });

        const menuLateral = document.querySelector('.menu-lateral');
        const btnMenu = document.getElementById('btn-menu');
        const btnVoltar = document.getElementById("btn-voltar");

        btnMenu.addEventListener('click', () => {
            menuLateral.style.top = 0;
            menuLateral.style.left = 0;
        });

        btnVoltar.addEventListener('click', () => {
            menuLateral.style.left = "-70%";
        });

    </script>
</body>

</html>