# ✅ Guia de Instalação e Verificação - CRUD Plantas

## 📋 Checklist de Pré-Requisitos

- [x] PHP 7.2+
- [x] MySQL/MariaDB
- [x] PDO extensão PHP
- [x] Servidor web (Apache/Nginx)
- [x] Pasta `/var/www/html/ecompartilhar_beckup`

---

## 🔧 Passo 1: Verificar Banco de Dados

### 1.1 Conectar ao MySQL

```bash
mysql -u root -p123
```

### 1.2 Verificar tabelas

```sql
USE ecompartilhar;

-- Verificar tabela tbplantas
SHOW TABLES;

-- Ver estrutura
DESCRIBE tbplantas;

-- Ver colunas
SHOW COLUMNS FROM tbplantas;
```

### 1.3 Esperado

```
| Field       | Type        | Null | Key | Default             | Extra          |
|-------------|-------------|------|-----|---------------------|-----------------|
| id          | int(11)     | NO   | PRI | NULL                | auto_increment  |
| usuario_id  | int(11)     | YES  | MUL | NULL                |                 |
| nome        | varchar(100)| YES  |     | NULL                |                 |
| descricao   | text        | YES  |     | NULL                |                 |
| troca       | tinyint(1)  | YES  |     | 0                   |                 |
| doacao      | tinyint(1)  | YES  |     | 0                   |                 |
| especie     | varchar(100)| YES  |     | NULL                |                 |
| tamanho     | varchar(50) | YES  |     | NULL                |                 |
| estado      | varchar(50) | YES  |     | NULL                |                 |
| imagem      | varchar(255)| YES  |     | NULL                |                 |
| contato     | varchar(100)| YES  |     | NULL                |                 |
| data_cad    | timestamp   | YES  |     | CURRENT_TIMESTAMP   |                 |
```

---

## 🔧 Passo 2: Verificar Diretórios e Permissões

### 2.1 Verificar estrutura de pastas

```bash
cd /var/www/html/ecompartilhar_beckup

# Listar estrutura
ls -la

# Deve conter:
# drwxr-xr-x api/
# drwxr-xr-x factory/
# drwxr-xr-x view/
# drwxr-xr-x img/
# -rw-r--r-- index.php
# -rw-r--r-- offline.html
# -rw-r--r-- select.php
# -rw-r--r-- sw.js
```

### 2.2 Verificar pasta de imagens

```bash
# Entrar em img
cd img

# Verificar se usr/ existe
ls -la | grep usr

# Se não existir, criar
mkdir -p usr
chmod 755 usr
chmod 755 img

# Verificar permissões
ls -la
# drwxr-xr-x usr (755)
```

### 2.3 Verificar arquivos criados

```bash
cd /var/www/html/ecompartilhar_beckup

# Verific arquivos factory
ls -la factory/
# Deve conter:
# -rw-r--r-- Planta.php ✨
# -rw-r--r-- UploadImagem.php ✨
# -rw-r--r-- bootstrap.php
# -rw-r--r-- conexao.php

# Verificar arquivos api
ls -la api/
# Deve conter:
# -rw-r--r-- salvarplanta.php ✨
# -rw-r--r-- atualizarplanta.php ✨
# -rw-r--r-- deletarplanta.php ✨
# -rw-r--r-- logout.php
# -rw-r--r-- salvar_dicas.php
# -rw-r--r-- salvarUsr.php

# Verificar arquivos view/plantas
ls -la view/plantas/
# Deve conter:
# -rw-r--r-- addplanta.php 🔄
# -rw-r--r-- editarplanta.php ✨
# -rw-r--r-- viewPlantas.php 🔄
# -rw-r--r-- excluirplanta.php (pode remover)
# -rw-r--r-- alerartphp (typo - remover)
```

---

## 🔧 Passo 3: Testar Conexão com Banco

### 3.1 Criar arquivo de teste (temporário)

```bash
cat > /tmp/teste_conexao.php << 'EOF'
<?php
require_once 'factory/conexao.php';

try {
    $conn = Caminho::getConn();
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tbplantas WHERE usuario_id = :uid");
    $stmt->execute([':uid' => 1]);
    
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "✅ Conexão OK!\n";
    echo "Total de plantas do usuário 1: " . $resultado['total'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
}
?>
EOF

# Executar teste
cd /var/www/html/ecompartilhar_beckup
php /tmp/teste_conexao.php
```

### 3.2 Resultado esperado

```
✅ Conexão OK!
Total de plantas do usuário 1: 0 (ou mais se tiver dados)
```

---

## 🔧 Passo 4: Testar Classes PHP

### 4.1 Testar classe Planta

```bash
cat > /tmp/teste_planta.php << 'EOF'
<?php
require_once '/var/www/html/ecompartilhar_beckup/factory/bootstrap.php';
require_once '/var/www/html/ecompartilhar_beckup/factory/Planta.php';

try {
    $planta = new Planta();
    
    // Testar obtenção de planta
    $resultado = $planta->obter(1);
    
    if ($resultado) {
        echo "✅ Planta encontrada:\n";
        print_r($resultado);
    } else {
        echo "ℹ️ Nenhuma planta com ID 1\n";
    }
    
    // Testar listagem
    $todas = $planta->listarDoUsuario(1);
    echo "\n✅ Total de plantas do usuário: " . count($todas) . "\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>
EOF

# Executar teste
php /tmp/teste_planta.php
```

---

## 🌐 Passo 5: Testar via Browser

### 5.1 Iniciar sessão

```
1. Vá para http://localhost/ecompartilhar_beckup
2. Faça login com:
   - Email: sim@não.com
   - Senha: 123
```

### 5.2 Testar Cadastro

```
1. Acesse: http://localhost/ecompartilhar_beckup/view/plantas/addplanta.php
2. Preencha o formulário:
   - Nome: Suculenta Jade
   - Descrição: Planta pequena com folhas verdes
   - Tipo: Troca
3. Clique "Cadastrar Planta"
4. Verificar redirecionamento para viewPlantas.php
5. Verificar planta na lista
```

### 5.3 Testar Edição

```
1. Em viewPlantas.php, clique "✏️ Editar"
2. Modifique Nome para: Suculenta Jade Editada
3. Clique "💾 Salvar Alterações"
4. Verificar atualização na lista
```

### 5.4 Testar Deleção

```
1. Em viewPlantas.php, clique "🗑️ Deletar"
2. Confirme a exclusão
3. Verificar que planta sumiu da lista
```

---

## 🔍 Passo 6: Verificar Segurança

### 6.1 Testar CSRF Protection

```bash
# Criar teste sem CSRF token
curl -X POST http://localhost/ecompartilhar_beckup/api/salvarplanta.php \
  -d "nome=Teste&descricao=Teste&tipo=troca" \
  -b "PHPSESSID=seu_session_id"

# Resultado esperado:
# {"sucesso":false,"mensagem":"Token de segurança inválido"}
```

### 6.2 Testar SQL Injection

```
1. Em editarplanta.php, verificar URL: ?id=1
2. Tentar: ?id=1' OR '1'='1
3. Resultado esperado: Sem resultado ou erro controlado
```

### 6.3 Testar XSS

```
1. Tentar cadastro com:
   Nome: <script>alert('xss')</script>
2. Verificar se script é exibido como texto (não executado)
3. Resultado esperado: Sem alerta
```

### 6.4 Testar Upload

```
1. Tentar upload de arquivo .txt
2. Resultado esperado: ❌ "Tipo de arquivo não permitido"

1. Tentar upload de arquivo > 5MB
2. Resultado esperado: ❌ "Arquivo muito grande"
```

---

## 📊 Passo 7: Verificação de Dados

### 7.1 Consultar banco

```sql
USE ecompartilhar;

-- Ver todas as plantas
SELECT * FROM tbplantas;

-- Ver plantas do usuário 1
SELECT * FROM tbplantas WHERE usuario_id = 1;

-- Ver total
SELECT COUNT(*) as total FROM tbplantas;

-- Ver com tipo
SELECT id, nome, 
       CASE WHEN troca = 1 THEN 'Troca' ELSE 'Doação' END as tipo,
       data_cad
FROM tbplantas;
```

### 7.2 Verificar imagens

```bash
# Listar imagens uploaded
ls -la /var/www/html/ecompartilhar_beckup/img/usr/

# Deve conter arquivos como:
# IMG_a1b2c3d4.jpg
# IMG_e5f6g7h8.png
```

---

## 🚨 Passo 8: Troubleshooting

### Problema: "Token de segurança inválido"

**Solução:**
```php
// Gerar novo token
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
echo $_SESSION['csrf_token'];
```

### Problema: Upload não funciona

**Solução:**
```bash
# Verificar permissões
chmod 755 img/usr
chmod 644 img/usr/*

# Verificar se arquivo é imagem
file img/usr/IMG_*.jpg

# Testar PHP
php -r "echo mime_content_type('imagem.jpg');"
```

### Problema: Planta não aparece

**Solução:**
```php
// Verificar session
var_dump($_SESSION['usuario']['id']);

// Verificar banco
SELECT * FROM tbplantas WHERE usuario_id = X;

// Verificar classe
$planta = new Planta();
$todas = $planta->listarDoUsuario(1);
var_dump($todas);
```

### Problema: CSRF token não gerado

**Solução:**
```php
// No bootstrap.php ou addplanta.php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
echo htmlspecialchars($_SESSION['csrf_token']);
```

---

## 📈 Passo 9: Performance

### 9.1 Verificar logs

```bash
# PHP error log
tail -f /var/log/php-errors.log

# ou em php.ini
grep error_log /etc/php/*/apache2/php.ini
```

### 9.2 Verificar query performance

```sql
-- Adicionar index se necessário
ALTER TABLE tbplantas ADD INDEX idx_usuario (usuario_id);
ALTER TABLE tbplantas ADD INDEX idx_data (data_cad);

-- Verificar índices
SHOW INDEXES FROM tbplantas;
```

---

## ✅ Checklist Final

- [ ] Banco de dados OK
- [ ] Tabela tbplantas OK
- [ ] Permissões de pasta OK
- [ ] Pasta img/usr criada
- [ ] Arquivos factory criados
- [ ] Arquivos api criados
- [ ] Arquivos view/plantas atualizados
- [ ] Conexão PDO funciona
- [ ] Classe Planta instancia
- [ ] Classe UploadImagem funciona
- [ ] Formulário cadastro acessa
- [ ] Formulário consegue enviar
- [ ] Planta aparece no banco
- [ ] Edição funciona
- [ ] Deleção funciona
- [ ] Segurança validada
- [ ] CSRF token protege
- [ ] SQL Injection protegido
- [ ] XSS protegido
- [ ] Upload seguro

---

## 🎯 Resultado Final

Ao completar todos os passos:

✅ **CRUD Completo Funcionando**
✅ **Segurança Validada**
✅ **Testes Passando**
✅ **Pronto para Produção**

---

## 📞 Suporte

Documentação:
- `CRUD_PLANTAS_DOCUMENTACAO.md` - Documentação completa
- `TESTE_GUIA.md` - Guia de testes
- `README_CRUD_PLANTAS.md` - Resumo
- `ARQUITETURA.md` - Arquitetura e diagramas

---

**Sistema pronto! 🚀**
