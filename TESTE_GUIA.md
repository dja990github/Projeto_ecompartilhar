# 🧪 Guia de Testes - CRUD de Plantas

## ✅ Testes de Segurança

### 1. Testar Proteção CSRF
```bash
# Tentar fazer POST sem CSRF token
curl -X POST http://localhost/ecompartilhar_beckup/api/salvarplanta.php \
  -F "nome=Planta Teste" \
  -F "descricao=Teste" \
  -F "tipo=troca"

# Resultado esperado: ❌ "Token de segurança inválido"
```

### 2. Testar SQL Injection
```bash
# Tentar SQL injection no ID
# URL: editarplanta.php?id=1' OR '1'='1

# Resultado esperado: ❌ Sem resultados (dados não são exibidos)
# PDO prepared statement protege automaticamente
```

### 3. Testar XSS
```
Nome: <script>alert('xss')</script>
Descrição: <img src=x onerror="alert('xss')">

Resultado esperado: ✅ Script exibido como texto, não executado
```

### 4. Testar Autorização
```bash
# User 1 tenta editar planta de User 2
# Resultado esperado: ❌ "Você não tem permissão para editar esta planta"
```

### 5. Testar Upload de Arquivo
```bash
# Tentar upload de arquivo não-imagem
curl -X POST http://localhost/ecompartilhar_beckup/api/salvarplanta.php \
  -F "nome=Teste" \
  -F "descricao=Teste" \
  -F "tipo=troca" \
  -F "foto=@documento.pdf"

# Resultado esperado: ❌ "Tipo de arquivo não permitido"
```

### 6. Testar Tamanho de Arquivo
```bash
# Tentar upload de arquivo > 5MB
# Resultado esperado: ❌ "Arquivo muito grande. Máximo: 5MB"
```

---

## ✅ Testes Funcionais

### 1. Criar Planta
- [ ] Acessar http://localhost/ecompartilhar_beckup/view/plantas/addplanta.php
- [ ] Preencher todos os campos obrigatórios
- [ ] Fazer upload de imagem (opcional)
- [ ] Clicar "Cadastrar Planta"
- [ ] Verificar redirecionamento para viewPlantas.php
- [ ] Verificar planta na lista

### 2. Editar Planta
- [ ] Acessar viewPlantas.php
- [ ] Clicar "✏️ Editar" em uma planta
- [ ] Modificar nome
- [ ] Alterar tipo (Troca → Doação)
- [ ] Fazer upload de nova imagem
- [ ] Clicar "💾 Salvar Alterações"
- [ ] Verificar alterações em viewPlantas.php

### 3. Deletar Planta
- [ ] Acessar viewPlantas.php
- [ ] Clicar "🗑️ Deletar" em uma planta
- [ ] Confirmar deleção
- [ ] Verificar que planta foi removida da lista
- [ ] Verificar mensagem de sucesso

### 4. Validação de Campos
- [ ] Tentar enviar formulário sem "Nome" → Erro
- [ ] Tentar enviar formulário sem "Descrição" → Erro
- [ ] Tentar enviar formulário sem "Tipo" → Erro
- [ ] Tentar digitar > 100 caracteres em "Nome" → Truncado
- [ ] Tentar digitar > 1000 caracteres em "Descrição" → Truncado

### 5. Listar Plantas
- [ ] Criar 3 plantas
- [ ] Acessar viewPlantas.php
- [ ] Verificar que as 3 plantas são exibidas
- [ ] Verificar tipos de transação (Troca/Doação)
- [ ] Verificar datas de cadastro
- [ ] Verificar imagens ou placeholder

---

## 🗂️ Checklist de Arquivo

### Factory
- [x] factory/Planta.php - Classe CRUD
- [x] factory/UploadImagem.php - Classe upload

### API Endpoints
- [x] api/salvarplanta.php - POST criar
- [x] api/atualizarplanta.php - POST atualizar
- [x] api/deletarplanta.php - POST deletar

### Views
- [x] view/plantas/addplanta.php - Formulário criar
- [x] view/plantas/editarplanta.php - Formulário editar
- [x] view/plantas/viewPlantas.php - Dashboard listar

### Outros
- [x] CRUD_PLANTAS_DOCUMENTACAO.md - Documentação
- [x] TESTE_GUIA.md - Este arquivo

---

## 🛠️ Testes com Ferramentas

### Usando Postman

#### 1. Salvar Planta
```
POST http://localhost/ecompartilhar_beckup/api/salvarplanta.php

Form Data:
- csrf_token: [pegar do session]
- nome: Suculenta Jade
- descricao: Planta pequena e suculenta
- tipo: troca
- especie: Crassula ovata
- tamanho: Pequena
- estado: Saudável
- contato: (11)98765-4321
- foto: [arquivo de imagem]

Headers:
- Cookie: PHPSESSID=[seu_session_id]

Resposta esperada:
{
  "sucesso": true,
  "mensagem": "Planta cadastrada com sucesso!",
  "id": 1
}
```

#### 2. Atualizar Planta
```
POST http://localhost/ecompartilhar_beckup/api/atualizarplanta.php

Form Data:
- id: 1
- csrf_token: [pegar do session]
- nome: Suculenta Jade Editada
- descricao: Descrição atualizada
- tipo: doacao
- [outros campos...]

Resposta esperada:
{
  "sucesso": true,
  "mensagem": "Planta atualizada com sucesso!"
}
```

#### 3. Deletar Planta
```
POST http://localhost/ecompartilhar_beckup/api/deletarplanta.php

Form Data:
- id: 1
- csrf_token: [pegar do session]

Resposta esperada:
{
  "sucesso": true,
  "mensagem": "Planta deletada com sucesso!"
}
```

---

## 📊 Dados de Teste

### Usuário Teste
```
ID: 1
Nome: Armando Umgolpe
Email: sim@não.com
Telefone: (11)989296427
Senha: 123
```

### Plantas Teste
```
1. Suculenta Jade
   - Descrição: Pequena suculenta verde escuro
   - Tipo: Troca
   - Tamanho: 20cm
   - Estado: Saudável

2. Girassol
   - Descrição: Flor grande e amarela
   - Tipo: Doação
   - Tamanho: Grande
   - Estado: Brotando

3. Samambaia
   - Descrição: Planta com folhas verdes e exuberantes
   - Tipo: Troca
   - Tamanho: Média
   - Estado: Com manchas marrons (precisa água)
```

---

## 🐛 Verificação de Bugs Comuns

### 1. CSRF Token Inválido
- [ ] Token sendo regenerado a cada requisição?
- [ ] Token guardado na sessão?
- [ ] Token passado corretamente no formulário?

### 2. Imagem Não Upload
- [ ] Pasta `/img/usr/` existe e tem permissão 755?
- [ ] Arquivo é realmente uma imagem?
- [ ] Tamanho < 5MB?

### 3. Planta Não Aparece
- [ ] Usuário está logado?
- [ ] usuario_id na tabela tbplantas está correto?
- [ ] Session está ativa?

### 4. Erro ao Atualizar
- [ ] usuario_id da planta é igual ao da sessão?
- [ ] CSRF token é válido?
- [ ] ID da planta existe no banco?

---

## 📝 Relatório de Testes

| Funcionalidade | Status | Observações |
|---|---|---|
| Criar Planta | ⚪ | |
| Listar Plantas | ⚪ | |
| Editar Planta | ⚪ | |
| Deletar Planta | ⚪ | |
| Upload Imagem | ⚪ | |
| Validação Nome | ⚪ | |
| Validação Descrição | ⚪ | |
| CSRF Protection | ⚪ | |
| SQL Injection | ⚪ | |
| XSS Protection | ⚪ | |
| Autorização | ⚪ | |

**Legenda**: ⚪ = Por testar | 🟢 = OK | 🔴 = Falhou

---

## 💡 Dicas

1. **Sempre fazer login primeiro** antes de testar o CRUD
2. **Inspecionar Network** (F12) para ver requisições
3. **Verificar Console** para erros JavaScript
4. **Checar error_log** do PHP para erros backend
5. **Usar DevTools** para verificar requests/responses
6. **Testar com diferentes tipos de imagem** (JPG, PNG, GIF)
7. **Testar com caracteres especiais** em nomes

---

**Boa sorte nos testes! 🚀**
