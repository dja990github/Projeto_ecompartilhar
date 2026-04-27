<?php
require_once '../../factory/bootstrap.php';

// Gerar token CSRF
if (empty($_SESSION['usuario'])) {
    $_SESSION['usuario'] = bin2hex(random_bytes(32));
}
$usuario = $_SESSION['usuario'];

$logado = !empty($_SESSION['usuario']['id']) && $_SESSION['usuario']['id'] !== null;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Planta</title>
</head>

<body>

    <div id="conteinerCell">

        <aside class="menu-lateral">
            <button id="btn-voltar">
                <img id="btnnomenu" src="../../img/btn/menu.png">
            </button>

            <nav>
                <h3 onclick="window.location.href='../../index.html'"><img src="../../img/btn/casa.png">Início</h3>
                <h3 onclick="window.location.href='./../doar.html'"><img src="../../img/btn/doar.png">Doar</h3>
                <h3 onclick="window.location.href='./../acessorios.html'"><img src="../../img/btn/acess.png">Acessórios
                </h3>
                <section onclick="window.location.href='../../index.html'">
                    <img id="logo-manu-lateral" src="../../logo/logo-black.png">
                </section>
            </nav>
        </aside>


        <section id="cnteinerPesquisa">
            <aside class="sessaoPesquisa">
                <img id="btn-menu" src="../../img/btn/menu.png">
                <h4 style="color:white;">Cadastrar Planta</h4>
                <img onclick="window.location.href='./index.html'" id="perfil" src="../../img/btn/perfil.png">
            </aside>
        </section>


        <div class="conteudo-form">
            <div id="alerta"
                style="display: none; padding: 10px; border-radius: 8px; margin-bottom: 10px; font-size: 13px;"></div>

            <?php if (!$logado): ?>
                <div
                    style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 15px;">
                    ⚠️ Você deve estar logado para cadastrar!
                </div>
            <?php endif; ?>

            <form id="form-planta" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <label>🔄 Tipo:</label>
                <select name="tipo" id="tipo" required>
                    <option value="">Selecione</option>
                    <option value="troca">Troca</option>
                    <option value="doacao">Doação</option>
                </select>


                <label>📷 Foto da planta</label>
                <input type="file" id="foto" name="foto" accept="image/*">

                <label>🌿 Nome da planta</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: Suculenta Jade" maxlength="100" required>

                <label>📝 Descrição</label>
                <textarea id="descricao" name="descricao" placeholder="Fale sobre essa planta..." maxlength="1000"
                    required></textarea>

                <hr>

                <b style="text-align: center;margin-bottom: 20px;">Campos opcionais:</b>

                <b>🌿 Espécie de planta</b>
                <textarea name="especie" id="espc" placeholder="Fale um pouco mais sobre a planta..."
                    maxlength="100"></textarea>

                <b>📐 Tamanho</b>
                <textarea name="tamanho" id="tamanho" placeholder="Ex: Pequena, Média, Grande ou 50cm,  1 metro"
                    maxlength="50"></textarea>

                <b>⚠️ Estado da planta</b>
                <textarea name="estado" id="estado" placeholder="Descreva a situação da planta..."
                    maxlength="50"></textarea>


                <button id="btn-add" type="submit">Cadastrar 🌱</button>

                <input type="submit" value="Cadastrar">
            </form>

        </div>

    </div>

    <script>
        const menuLateral = document.querySelector('.menu-lateral');
        const btnMenu = document.getElementById('btn-menu');
        const btnVoltar = document.getElementById("btn-voltar");
        const form = document.getElementById('form-planta');
        const alerta = document.getElementById('alerta');

        btnMenu.addEventListener('click', () => {
            menuLateral.style.left = 0;
        });

        btnVoltar.addEventListener('click', () => {
            menuLateral.style.left = "-70%";
        });

        // Enviar formulário
        form?.addEventListener('submit', async (e) => {
            e.preventDefault();

            alerta.style.display = 'none';
            alerta.className = '';
            alerta.innerHTML = '';

            const formData = new FormData(form);

            try {
                const response = await fetch('../../api/salvarplanta.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.json();

                if (resultado.sucesso) {
                    alerta.style.backgroundColor = '#d4edda';
                    alerta.style.color = '#155724';
                    alerta.style.border = '1px solid #c3e6cb';
                    alerta.innerHTML = '✅ ' + resultado.mensagem;
                    alerta.style.display = 'block';
                    form.reset();

                    setTimeout(() => {
                        window.location.href = './viewPlantas.php';
                    }, 1500);
                } else {
                    alerta.style.backgroundColor = '#f8d7da';
                    alerta.style.color = '#721c24';
                    alerta.style.border = '1px solid #f5c6cb';
                    alerta.innerHTML = '❌ ' + resultado.mensagem;
                    alerta.style.display = 'block';
                }
            } catch (erro) {
                alerta.style.backgroundColor = '#f8d7da';
                alerta.style.color = '#721c24';
                alerta.style.border = '1px solid #f5c6cb';
                alerta.innerHTML = '❌ Erro ao conectar: ' + erro.message;
                alerta.style.display = 'block';
                console.error('Erro:', erro);
            }
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
            --verde-escuro-hover: #1f694a;
            --verde-escuro: #288c62;
            --verde-medio: #4aa780;
            --verde-claro: #95d5b2;
            --bege: #fbeaca;
        }

        #conteinerCell {
            position: relative;
            height: 485px;
            width: 260px;
            background-color: var(--bege);
            border-radius: 15px;
            border: 3px solid black;
            overflow-y: scroll;
            margin: auto;
        }

        /* MENU */
        .menu-lateral {
            position: absolute;
            background-color: #dd8046;
            width: 70%;
            height: 100%;
            left: -70%;
            transition: 0.5s;
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

        /* TOPO */
        .sessaoPesquisa {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--verde-escuro);
            border-radius: 10px 10px 0 0;
            padding: 5px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.53);
        }

        #btn-voltar {
            border: none;
            padding: 0;
        }

        #btn-menu {
            width: 50px;
            height: auto;


        }

        .menu-lateral section {
            margin: 0;
        }

        #logo-manu-lateral {
            position: absolute;
            bottom: 0;
            width: 90%;
            height: max-content;

        }

        #btnnomenu {
            width: 50px;
            height: auto;
            background-color: #dd8046;
        }

        #perfil {
            width: 50px;
            height: auto;
            border-radius: 35px;
        }

        /* FORM */
        .conteudo-form {
            padding: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            border-radius: 10px;
            border: none;
            padding: 8px;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 60px;
        }

        #btn-add {

            position: fixed;
            bottom: 20px;
            background-color: var(--verde-escuro);
            border-radius: 10px;
            padding: 8px 15px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 3px 4px 5px rgba(0, 0, 0, 0.456);
        }

        #btn-add:hover {
            font-size: larger;
            width: max-content;
            height: auto;
            transition-duration: 1s;
            background-color: var(--verde-escuro-hover);


        }

        /* BOTÃO */
        button {
            margin-top: 10px;
            padding: 10px;
            border: none;
            border-radius: 15px;
            background-color: var(--verde-medio);
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--verde-escuro);
        }
    </style>

</body>

</html>