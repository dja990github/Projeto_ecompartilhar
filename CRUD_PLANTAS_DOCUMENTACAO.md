# 🌿 CRUD de Plantas - Documentação Completa

## 📋 Estrutura do Sistema

O sistema de CRUD de plantas foi implementado com as seguintes características:

### ✅ Segurança Implementada

- **PDO com Prepared Statements**: Proteção contra SQL Injection
- **CSRF Protection**: Tokens únicos em formulários
- **Validação e Sanitização**: Todos os inputs validados
- **XSS Protection**: htmlspecialchars() em saídas
- **Upload de Arquivos**: Validação de tipo MIME, tamanho e extensão
- **Autenticação**: Verificação de sessão em todos os endpoints
- **Autorização**: Verificação de propriedade do recurso

---

## 📁 Arquivos Criados/Modificados

### 1️⃣ **Classes (factory/)**

#### `factory/Planta.php` ✨ NOVO
Classe principal com métodos CRUD:
- `criar()` - Criar nova planta
- `obter()` - Obter planta por ID
- `listarDoUsuario()` - Listar plantas do usuário
- `listarTodas()` - Listar todas as plantas (público)
- `atualizar()` - Atualizar planta existente
- `deletar()` - Deletar planta
- Validação completa de dados
- Sanitização automática

#### `factory/UploadImagem.php` ✨ NOVO
Classe para gerenciar uploads de imagens:
- Validação de tipo MIME
- Validação de extensão (JPG, PNG, GIF, WEBP)
- Limite de tamanho (5MB)
- Geração de nomes únicos com hash
- Deletar imagens antigas

---

### 2️⃣ **API Endpoints (api/)**

#### `api/salvarplanta.php` ✨ NOVO / ATUALIZADO
Criar nova planta com upload opcional de imagem
- **Method**: POST
- **Requer**: Autenticação + CSRF Token
- **Campos**:
  - `csrf_token` (obrigatório)
  - `nome` (obrigatório, max 100 chars)
  - `descricao` (obrigatório, max 1000 chars)
  - `tipo` (obrigatório: 'troca' ou 'doacao')
  - `especie` (opcional, max 100 chars)
  - `tamanho` (opcional, max 50 chars)
  - `estado` (opcional, max 50 chars)
  - `contato` (opcional, max 100 chars)
  - `foto` (opcional, arquivo de imagem)

#### `api/atualizarplanta.php` ✨ NOVO / ATUALIZADO
Atualizar planta existente
- **Method**: POST
- **Requer**: Autenticação + CSRF Token + Propriedade do recurso
- **Campos**: Mesmos do `salvarplanta.php` + `id` (ID da planta)

#### `api/deletarplanta.php` ✨ NOVO
Deletar planta existente
- **Method**: POST
- **Requer**: Autenticação + CSRF Token + Propriedade do recurso
- **Campos**:
  - `csrf_token` (obrigatório)
  - `id` (obrigatório, ID da planta)

---

### 3️⃣ **Views (view/plantas/)**

#### `view/plantas/addplanta.php` 🔄 ATUALIZADO
Formulário de cadastro de nova planta
- ✅ CSRF Protection integrado
- ✅ Validação client-side
- ✅ Upload de imagem
- ✅ Campos obrigatórios e opcionais
- ✅ Feedback visual em tempo real
- ✅ Redirecionamento após sucesso

#### `view/plantas/editarplanta.php` ✨ NOVO
Formulário de edição de planta existente
- ✅ Carrega dados da planta
- ✅ Verificação de permissão
- ✅ Previewde imagem atual
- ✅ Mesmo design de addplanta.php
- ✅ Edição de todos os campos

#### `view/plantas/viewPlantas.php` 🔄 TOTALMENTE REESCRITO
Dashboard de gerenciamento de plantas
- ✅ Lista todas as plantas do usuário
- ✅ Grid responsivo
- ✅ Botões para editar/deletar
- ✅ Tipo de transação (Troca/Doação)
- ✅ Data de cadastro
- ✅ Botão para adicionar nova planta
- ✅ Mensagens de feedback

---

## 🔒 Segurança - Detalhes Técnicos

### SQL Injection Protection
```php
// ❌ ERRADO - Nunca fazer isso
$sql = "SELECT * FROM tbplantas WHERE id = " . $_GET['id'];

// ✅ CORRETO - Usar prepared statements
$sql = "SELECT * FROM tbplantas WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => (int)$id]);
```

### XSS Protection
```php
// ✅ Na saída, sempre usar htmlspecialchars
echo htmlspecialchars($planta['nome']);
// Converte: <script>alert('xss')</script> 
// Para: &lt;script&gt;alert('xss')&lt;/script&gt;
```

### CSRF Protection
```php
// Gerar token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Validar token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Rejeitar requisição
}
```

### Validação de Entrada
```php
// Validação de tamanho
if (strlen($nome) > 100) {
    $erros[] = 'Nome não pode ter mais de 100 caracteres';
}

// Sanitização
$nome = trim(strip_tags($nome));

// Typecast
$id = (int)$id; // Força conversão para inteiro
```

---

## 📱 Fluxo de Uso

### 1. Cadastrar Planta
```
User → addplanta.php → salvarplanta.php → DB → viewPlantas.php
```

### 2. Editar Planta
```
User → viewPlantas.php (clica Editar) 
  → editarplanta.php (carrega dados)
  → atualizarplanta.php → DB → viewPlantas.php
```

### 3. Deletar Planta
```
User → viewPlantas.php (clica Deletar)
  → Confirmação
  → deletarplanta.php → DB → viewPlantas.php
```

---

## 🎨 Funcionalidades

### ✅ Implementadas
- [x] Criar planta com validação
- [x] Listar plantas do usuário
- [x] Editar planta existente
- [x] Deletar planta com confirmação
- [x] Upload de imagem seguro
- [x] Proteção contra CSRF
- [x] Proteção contra SQL Injection
- [x] Proteção contra XSS
- [x] Validação completa de dados
- [x] Tratamento de erros sem expor detalhes
- [x] Mensagens de feedback amigáveis
- [x] Verificação de autorização (propriedade do recurso)
- [x] Design responsivo
- [x] Interface intuitiva

---

## 🚀 Como Usar

### Cadastrar Planta
1. Clique em "Adicionar Planta"
2. Preencha os campos obrigatórios (Nome, Descrição, Tipo)
3. Opcionalmente, faça upload de uma foto
4. Complete os campos opcionais se desejar
5. Clique em "Cadastrar Planta"

### Editar Planta
1. Acesse "Minhas Plantas"
2. Encontre a planta que deseja editar
3. Clique no botão "✏️ Editar"
4. Modifique os dados desejados
5. Clique em "💾 Salvar Alterações"

### Deletar Planta
1. Acesse "Minhas Plantas"
2. Encontre a planta que deseja deletar
3. Clique no botão "🗑️ Deletar"
4. Confirme a exclusão
5. Pronto! Planta removida

---

## 📊 Estrutura do Banco de Dados

### Tabela: tbplantas
```sql
CREATE TABLE tbplantas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nome VARCHAR(100),
    descricao TEXT,
    troca BOOLEAN DEFAULT FALSE,
    doacao BOOLEAN DEFAULT FALSE,
    especie VARCHAR(100),
    tamanho VARCHAR(50),
    estado VARCHAR(50),
    imagem VARCHAR(255),
    contato VARCHAR(100),
    data_cad TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES tbusuarios(id)
);
```

---

## 🔐 Checklist de Segurança

- [x] Usar PDO com prepared statements (NUNCA concatenar SQL)
- [x] Validar e sanitizar todos os inputs
- [x] Proteger contra SQL Injection com parameterização
- [x] Proteger contra XSS usando htmlspecialchars na saída
- [x] Implementar proteção CSRF em formulários
- [x] Verificar propriedade do recurso (autorização)
- [x] Usar try/catch para tratamento de erros
- [x] Não expor detalhes de erro aos usuários
- [x] Validar tipo MIME de uploads
- [x] Limite de tamanho de arquivo
- [x] Gerar nomes únicos para uploads
- [x] Validação de extensão de arquivo

---

## 📞 Suporte

Para dúvidas ou problemas:
1. Verifique os logs em `/factory/` (error_log)
2. Teste as requisições com Postman ou similar
3. Valide a estrutura do banco de dados
4. Certifique-se de que as permissões de pasta estão corretas

---

## 📝 Notas Importantes

- Sempre faça login antes de usar o sistema
- As imagens são armazenadas em `/img/usr/`
- Os tokens CSRF são únicos por sessão
- A propriedade de plantas é verificada por usuario_id
- Erros não são expostos ao usuário por segurança
- Os logs estão em PHP error_log para auditoria

---

**Sistema criado com foco em segurança, usabilidade e boas práticas! 🌱**
