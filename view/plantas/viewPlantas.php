<?php
require_once '../../factory/bootstrap.php';
require_once '../../factory/Planta.php';

// Gerar token CSRF se não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verificar se usuário está logado
$logado = !empty($_SESSION['usuario']['id']) && $_SESSION['usuario']['id'] !== null;
$plantas = [];

if ($logado) {
    $plantaObj = new Planta();
    $plantas = $plantaObj->listarDoUsuario($_SESSION['usuario']['id']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Plantas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            background-color: #f5f5f5;
        }

        :root {
            --verde-escuro-hover: #1f694a;
            --verde-escuro: #288c62;
            --verde-medio: #4aa780;
            --verde-claro: #95d5b2;
            --bege: #fbeaca;
            --cinza: #37664f;
            --white: #FFFFFF;
        }

        #conteinerCell {
            position: relative;
            max-width: 600px;
            margin: 20px auto;
            background-color: var(--bege);
            border-radius: 15px;
            border: 3px solid black;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Menu lateral */
        .menu-lateral {
            position: fixed;
            background-color: #dd8046;
            width: 70%;
            height: 100%;
            left: -70%;
            top: 0;
            transition: 0.5s;
            z-index: 1000;
            overflow-y: auto;
        }

        .menu-lateral nav {
            padding: 20px;
        }

        .menu-lateral h3 {
            padding: 10px 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            color: white;
        }

        .menu-lateral img {
            height: 30px;
            width: auto;
            margin-right: 10px;
        }

        /* Topo */
        #cnteinerPesquisa {
            position: sticky;
            top: 0;
            background: var(--verde-escuro);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }

        .sessaoPesquisa {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .sessaoPesquisa h4 {
            color: white;
            text-align: center;
            flex: 1;
        }

        .sessaoPesquisa img {
            height: 35px;
            width: 35px;
            cursor: pointer;
            padding: 5px;
        }

        #btn-menu, #btn-voltar {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        #btn-menu img, #btn-voltar img {
            height: 30px;
        }

        /* Container de plantas */
        .conteiner-geral {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .conteiner-produto {
            background: white;
            border: 2px solid var(--verde-escuro);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .conteiner-produto:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-color: var(--verde-medio);
        }

        .conteiner-produto h4 {
            color: var(--verde-escuro);
            font-size: 16px;
            word-break: break-word;
        }

        .conteiner-produto small {
            color: #666;
            font-size: 12px;
        }

        .conteiner-produto img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .conteiner-produto.add-novo {
            background: linear-gradient(135deg, var(--verde-claro), var(--verde-medio));
            border-color: var(--verde-escuro);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
            cursor: pointer;
        }

        .conteiner-produto.add-novo:hover {
            background: linear-gradient(135deg, var(--verde-medio), var(--verde-escuro));
        }

        .conteiner-produto.add-novo h4 {
            color: white;
            font-size: 18px;
        }

        .conteiner-produto.add-novo img {
            width: 50%;
            height: 50px;
            object-fit: contain;
        }

        /* Ações do produto */
        .produto-acoes {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-acao {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
            flex: 1;
        }

        .btn-editar {
            background-color: #007bff;
            color: white;
        }

        .btn-editar:hover {
            background-color: #0056b3;
        }

        .btn-deletar {
            background-color: #dc3545;
            color: white;
        }

        .btn-deletar:hover {
            background-color: #c82333;
        }

        /* Alerta */
        .alerta {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        .alerta.info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            display: block;
        }

        .alerta.erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        .alerta.sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lista-vazia {
            text-align: center;
            padding: 40px 20px;
            color: var(--verde-escuro);
        }

        .lista-vazia img {
            width: 100px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .tipo-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 5px;
        }

        .tipo-badge.troca {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .tipo-badge.doacao {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        @media (max-width: 600px) {
            #conteinerCell {
                margin: 10px;
                padding: 15px;
            }

            .conteiner-geral {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>

<body>

    <div id="conteinerCell">

        <aside class="menu-lateral">
            <button id="btn-voltar" style="background: none; border: none; padding: 10px;">
                <img id="btnnomenu" src="../../img/btn/menu.png" alt="Voltar">
            </button>

            <nav>
                <h3 onclick="window.location.href='../../index.html'"><img src="../../img/btn/casa.png" alt="Início">Início</h3>
                <h3 onclick="window.location.href='./../doar.html'"><img src="../../img/btn/doar.png" alt="Doar">Doar</h3>
                <h3 onclick="window.location.href='./../acessorios.html'"><img src="../../img/btn/acess.png" alt="Acessórios">Acessórios</h3>
            </nav>
        </aside>

        <section id="cnteinerPesquisa">
            <aside class="sessaoPesquisa">
                <button id="btn-menu" style="background: none; border: none;">
                    <img src="../../img/btn/menu.png" alt="Menu">
                </button>
                <h4>🌿 Minhas Plantas</h4>
                <img onclick="window.location.href='../../index.html'" id="perfil" src="../../img/btn/perfil.png" alt="Perfil">
            </aside>
        </section>

        <div id="alerta" class="alerta"></div>

        <?php if (!$logado): ?>
            <div class="alerta info">
                ⚠️ Você precisa estar logado para ver suas plantas!
            </div>
        <?php elseif (empty($plantas)): ?>
            <div class="lista-vazia">
                <h3>🌱 Você ainda não cadastrou nenhuma planta</h3>
                <p style="margin-top: 10px; color: #666;">
                    Comece agora a compartilhar suas plantas!
                </p>
            </div>

            <div class="conteiner-geral">
                <fieldset class="conteiner-produto add-novo" onclick="window.location.href='./addplanta.php'">
                    <h4>➕ Adicionar Planta</h4>
                </fieldset>
            </div>
        <?php else: ?>
            <div class="conteiner-geral">
                <fieldset class="conteiner-produto add-novo" onclick="window.location.href='./addplanta.php'">
                    <h4>➕ Adicionar Planta</h4>
                </fieldset>

                <?php foreach ($plantas as $planta): ?>
                    <fieldset class="conteiner-produto">
                        <h4><?php echo htmlspecialchars($planta['nome']); ?></h4>
                        
                        <?php if (!empty($planta['imagem'])): ?>
                            <img src="<?php echo htmlspecialchars($planta['imagem']); ?>" alt="<?php echo htmlspecialchars($planta['nome']); ?>">
                        <?php else: ?>
                            <div style="width: 100%; height: 150px; background: linear-gradient(135deg, var(--verde-claro), var(--verde-medio)); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                                🌿 Sem foto
                            </div>
                        <?php endif; ?>

                        <small><?php echo htmlspecialchars(substr($planta['descricao'], 0, 60)) . (strlen($planta['descricao']) > 60 ? '...' : ''); ?></small>

                        <div>
                            <span class="tipo-badge <?php echo $planta['troca'] ? 'troca' : 'doacao'; ?>">
                                <?php echo $planta['troca'] ? '🔄 Troca' : '🎁 Doação'; ?>
                            </span>
                        </div>

                        <small style="color: #999; font-size: 11px;">
                            <?php echo date('d/m/Y', strtotime($planta['data_cad'])); ?>
                        </small>

                        <div class="produto-acoes">
                            <button class="btn-acao btn-editar" onclick="window.location.href='./editarplanta.php?id=<?php echo htmlspecialchars($planta['id']); ?>'">✏️ Editar</button>
                            <button class="btn-acao btn-deletar" onclick="deletarPlanta(<?php echo htmlspecialchars($planta['id']); ?>, '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>')">🗑️ Deletar</button>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <script>
        const menuLateral = document.querySelector('.menu-lateral');
        const btnMenu = document.getElementById('btn-menu');
        const btnVoltar = document.getElementById('btn-voltar');
        const alerta = document.getElementById('alerta');

        // Menu toggle
        btnMenu?.addEventListener('click', () => {
            menuLateral.style.left = 0;
        });

        btnVoltar?.addEventListener('click', () => {
            menuLateral.style.left = '-70%';
        });

        // Função para deletar planta
        async function deletarPlanta(id, csrfToken) {
            if (!confirm('Tem certeza que deseja deletar esta planta? Esta ação não pode ser desfeita.')) {
                return;
            }

            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch('../../api/deletarplanta.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.json();

                alerta.classList.remove('info', 'erro', 'sucesso');

                if (resultado.sucesso) {
                    alerta.classList.add('sucesso');
                    alerta.innerHTML = '✅ ' + resultado.mensagem;
                    
                    // Recarregar página após 1.5 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alerta.classList.add('erro');
                    alerta.innerHTML = '❌ ' + resultado.mensagem;
                }
            } catch (erro) {
                alerta.classList.add('erro');
                alerta.innerHTML = '❌ Erro ao conectar com o servidor: ' + erro.message;
                console.error('Erro:', erro);
            }
        }
    </script>

</body>

</html>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar</title>
</head>

<body>
    <div id="conteinerCell">

        <aside class="menu-lateral">
            <button id="btn-voltar">
                <img id="btnnomenu" src="../../img/btn/menu.png">
            </button>

            <nav>
                <h3 onclick="window.location.href='../../index.html'"><img src="../../img/btn/casa.png">Início</h3>
                <h3 onclick="window.location.href='./doar.html'"><img src="../../img/btn/doar.png">Doar</h3>
                <h3 onclick="window.location.href='../acessorios.html'"><img src="../../img/btn/acess.png">Acessórios
                </h3>
                <section onclick="window.location.href='../../index.html'">
                    <img id="logo-manu-lateral" src="../../img/logo/logo-black.png">
                </section>
            </nav>

        </aside>

        <section id="cnteinerPesquisa">
            <aside class="sessaoPesquisa">
                <article>
                    <img id="btn-menu" src="../../img/btn/menu.png">
                </article>

                <form id="pesquisa">
                    <input id="barra-pesquisa" type="text" placeholder="🔍 Pesquisar...">
                </form>

                <article onclick="window.location.href='./perfil/index.html'">
                    <img id="perfil" src="../../img/btn/perfil.png">
                </article>
            </aside>
        </section>

        <aside class="sessaoPesquisaX">
            <article>
                <img id="btn-menuX" src="../../img/btn/menu.png">
            </article>
        </aside>


        <div class="conteiner-geral-banner-produtos">


            <div class="banner-conteiner-geral">
                <div class="banner-conteiner">
                    <p id="banner-txt">
                        Você colaborando com o meio ambiente!
                    </p>
                </div>
            </div>

            <section class="conteiner-produtos-geral">
                <fieldset onclick="window.location.href='./addplanta.php'" id="sobra" class="conteiner-produto">
                    <h4>Adicionar novo</h4>
                    <img id="btn-add" src="../../img/btn/add.png">
                </fieldset>

                <fieldset class="conteiner-produto">
                    <h4>Espatula veia</h4>
                    <img src="../../img/ex/espatula.jpeg">

                </fieldset>
                <fieldset class="conteiner-produto">
                    <h4>Giralua</h4>
                    <img src="../../img/ex/giralua.jpeg">
                </fieldset>
                <fieldset class="conteiner-produto">
                    <h4>Flor de agosto</h4>
                    <img src="../../img/ex/maio.webp">
                </fieldset>
                <fieldset class="conteiner-produto">
                    <h4>flor podre</h4>
                    <img src="../../img/ex/florP.jpeg">
                </fieldset>
                <fieldset class="conteiner-produto">
                    <h4>Dango balanco</h4>
                    <img src="../../img/ex/dango.jpg">
                </fieldset>
            </section>

        </div>

    </div>


    <script>
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

        }


        .menu-lateral {
            position: absolute;
            background-color: #dd8046;
            width: 70%;
            height: 100%;
            top: 0;
            left: -70%;
            z-index: 999;
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

        #barra-pesquisa {
            flex: 0 0 auto;
            width: 150px;
            height: 35px;
            border-radius: 10px;
            border: none;
            font-size: larger;
        }

        #perfil {
            border: 1px solid black;
            border-radius: 50px;
            max-width: 45px;
            height: auto;
            margin-inline-start: 5px;
        }


        .conteiner-geral-banner-produtos {
            height: 100%;
        }

        .banner-conteiner-geral {
            position: relative;
            color: rgb(0, 0, 0);
            width: 100%;
            height: 30%;
            background: linear-gradient(rgba(53, 124, 94, 0.5), rgba(38, 94, 70, 0.5)),
                url(../../img/banner/teste4.png) no-repeat center/cover;
        }

        .banner-conteiner-geral img {
            height: auto;
            width: 100%;
            display: block;

        }

        .banner-conteiner {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        #banner-txt {
            color: white;
            font-size: large;
            margin-inline: 20px;
        }

        p {
            margin: 7px 0 5px 0;
        }

        .btn-banner {
            margin: 0;
            background-color: rgb(255, 255, 255);
            border: 10px solid transparent;
            border-radius: 10px;
            ;

        }

        .conteiner-produtos-geral {
            display: grid;
            justify-content: center;
            align-items: center;
            text-align: center;
            grid-template-columns: repeat(1, 1fr);
            margin: 0;
            padding: 0;
        }


        #sobra:hover {
            transform: translateY(-20px);
            transition-duration: 0.5s;
        }


        .conteiner-produto {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: rgb(255, 255, 255);
            border: 15px solid transparent;
            border-image: url(../../img/obg/folhas.jpg);
            border-image-repeat: repeat;
            border-image-slice: 65;
            padding: 0;
            margin: 5px;
        }


        .conteiner-produto:hover {
            transform: scale(110%);
            transition-duration: 1s;
        }


        .conteiner-produto img {
            height: auto;
            max-width: 70%;
            padding: 0;
            margin: 10px;
        }

        #btn-add {
            height: auto;
            width: 50%;
            margin-top: 10px;
        }


        .conteiner-sobra h3 {
            font-size: 20%;
        }

        .conteiner-sobra:hover {
            transform: translateY(-10px);
            transition-duration: 0.3s;
        }

        h4 {
            width: auto;
            height: auto;
            margin: 0;
            padding: 10px 30px;
            border: 1px solid black;
            border-radius: 0 0 35px 35px;
        }

        footer {
            margin-top: 15px;
            background-color: black;
            width: 100%;
            height: 20%;
        }
    </style>
</body>

</html>