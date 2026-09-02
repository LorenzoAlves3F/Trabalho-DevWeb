# Relatório de Decisões Técnicas — Sistema Carlore's

## 1. Tema e escopo

O sistema escolhido gerencia uma casa de festas fictícia ("Carlore's") que aluga múltiplos salões para
eventos. Optou-se por um escopo mais amplo que o mínimo exigido pelo enunciado — dois perfis de usuário
(Administrador e Cliente), controle financeiro completo (pagamentos parciais) e CRUD completo para cinco
entidades (Salões, Pacotes, Clientes, Reservas, Pagamentos) — para explorar de forma mais completa os
critérios de avaliação de Back-end, Banco de Dados e Funcionalidades.

## 2. Arquitetura: por que essa estrutura de pastas

O enunciado exige "PHP puro" (sem frameworks) organizado em MVC ou estrutura equivalente. Em vez de um
front-controller/router (que reintroduziria a complexidade de um mini-framework), optou-se por uma
estrutura pragmática comum em projetos PHP simples:

- **`models/`** — uma classe por tabela, responsável apenas por acesso a dados via PDO.
- **`services/`** — regras de negócio que cruzam tabelas ou envolvem validação/transação
  (`AuthService`, `ReservaService`, `PagamentoService`, `RelatorioService`, `UploadService`).
- **`public/`** — arquivos de entrada (um por página/ação), que funcionam como "controllers":
  recebem a requisição, chamam Service/Model e incluem a view correspondente.
- **`views/`** — apresentação, sem lógica de negócio além de formatação de exibição.

A separação mais importante do ponto de vista de segurança é que **apenas `public/` é o document root**
do Apache. `config/`, `models/`, `services/`, `views/` e `database/` (com o `.sql` do banco) ficam fora
da árvore servida — não são acessíveis por URL, mesmo que alguém tente adivinhar o caminho.

## 3. Modelagem do banco de dados

### 3.1 Separação `usuarios` / `clientes`

Em vez de uma tabela única com colunas opcionais para dados de cliente, `usuarios` guarda apenas dados
de autenticação (email, hash, papel) e `clientes` guarda o perfil (CPF, telefone, endereço) em relação
1:1. Isso evita colunas NULL desnecessárias para o Administrador (que não tem CPF/endereço) e mantém a
tabela de autenticação enxuta.

### 3.2 Controle de disponibilidade de salão (coluna gerada + UNIQUE)

O requisito "um salão não pode ter duas reservas ativas na mesma data" foi resolvido com duas camadas:

1. **Camada de aplicação** (`ReservaService::criar/atualizar`): antes de gravar, uma consulta
   (`Reserva::existeConflito`) verifica se já existe reserva ativa no salão/data, permitindo mostrar uma
   mensagem de erro amigável no formulário.
2. **Camada de banco** (garantia real contra condições de corrida): a coluna `bloqueio_data` é
   **gerada automaticamente** (`GENERATED ALWAYS AS`) a partir de `data_evento`, mas vira `NULL` quando
   `status = 'cancelada'`. Uma constraint `UNIQUE(salao_id, bloqueio_data)` garante, a nível de MySQL,
   que nunca existam duas linhas com o mesmo salão e mesma data enquanto ambas estiverem ativas — e como
   `NULL` nunca colide consigo mesmo em uma UNIQUE KEY, reservas canceladas liberam a data automaticamente
   para uma nova reserva no mesmo salão.

Se duas requisições simultâneas tentarem reservar o mesmo salão/data, a camada de aplicação pode deixar
passar ambas (condição de corrida clássica), mas o banco rejeita a segunda com um erro de chave
duplicada (`SQLSTATE 23000`), que é capturado no `catch (PDOException $e)` e convertido de volta em uma
mensagem de erro amigável no formulário.

### 3.3 `numero_convidados <= capacidade do salão`: por que em PHP e não em trigger

Essa regra foi implementada em `ReservaService`, e não como `TRIGGER` no banco, por três motivos:
(1) é uma comparação simples, sem risco de condição de corrida (diferente da checagem de disponibilidade
acima); (2) uma trigger devolveria um erro de SQL genérico, difícil de transformar em uma mensagem de
formulário amigável presa ao campo certo; (3) o enunciado avalia "lógica funcional" do back-end em PHP —
essa é exatamente a regra de negócio que deve estar visível no código da aplicação.

### 3.4 Status de pagamento: calculado, não armazenado

`pagamentos.valor` somado por reserva é comparado com `reservas.valor_total` em tempo de consulta
(`PagamentoService::statusPagamento`) para decidir entre `pendente`/`parcial`/`pago`. Evitar uma coluna
`status_pagamento` redundante em `reservas` elimina o risco clássico de o valor ficar dessincronizado
depois de um pagamento ser editado ou excluído — não existe estado duplicado para ficar inconsistente.

### 3.5 Exclusão lógica (soft delete) em Salões, Pacotes e Clientes

`reservas` referencia `saloes`, `pacotes` e `clientes` com `ON DELETE RESTRICT`: o MySQL impede apagar
um salão, pacote ou cliente que já tenha reservas associadas. Por isso o "excluir" desses CRUDs apenas
alterna a coluna `ativo` — o registro histórico das reservas antigas nunca é perdido, e o formulário de
nova reserva só lista salões/pacotes ativos.

### 3.6 Gotcha de PDO com colunas `DECIMAL`

O driver PDO do MySQL retorna colunas `DECIMAL` como **strings**, não `float` — um `"9200.00" + "3.5"`
faria concatenação, não soma, se usado sem cuidado. Por isso todo cálculo monetário em PHP passa por um
cast explícito `(float)` antes de somar ou comparar (ver `PagamentoService::saldoDevedor`).

## 4. Autenticação e recuperação de senha simulada

A recuperação de senha não envia e-mail real (evita depender de configuração de SMTP em ambiente local
de estudo). O fluxo ainda segue as práticas de um sistema real: gera-se um token aleatório de 32 bytes
(`random_bytes`), grava-se no banco apenas o **hash SHA-256** do token (nunca o token em claro), com
expiração de 30 minutos e uso único. O link de redefinição — que em produção seria enviado por e-mail —
é exibido diretamente na tela, com aviso explícito de que essa etapa é simulada.

## 5. Relatórios sem bibliotecas externas

Em vez de Dompdf/PhpSpreadsheet (que exigiriam Composer), os dois relatórios exigidos foram implementados
com recursos nativos do PHP e do navegador:

- **"PDF"**: a própria página HTML do relatório tem uma folha de estilo `@media print`
  (`assets/css/print.css`) que oculta menu/filtros e formata a tabela para impressão; o botão
  "Imprimir/Salvar PDF" chama `window.print()`, que no Chrome/Edge permite salvar como PDF diretamente.
- **"Excel"**: exportação em CSV gerada via `fputcsv()`, com BOM UTF-8 (para acentuação correta) e
  separador `;` (padrão do Excel em pt-BR), enviada com os headers `Content-Type`/`Content-Disposition`
  apropriados.

Essa abordagem elimina uma dependência externa sem abrir mão do requisito funcional (filtros + exportação).

## 6. Segurança

| Item | Implementação |
|---|---|
| SQL Injection | `PDO::ATTR_EMULATE_PREPARES = false` + só `prepare()`/`execute()` com parâmetros nomeados em toda a camada `models/`. |
| Senhas | `password_hash(PASSWORD_DEFAULT)` / `password_verify()`; nunca texto plano, nem no fluxo de reset. |
| XSS | Helper `e()` (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) em toda saída dinâmica das views. |
| CSRF | Token por sessão, campo oculto em todo formulário POST, verificado com `hash_equals()`. |
| Validação | Dupla camada: HTML5/JS no cliente (feedback imediato) e `Validator` em PHP no servidor (nunca confia apenas no cliente). |
| Sessão | `session_regenerate_id(true)` no login (previne session fixation); cookie `httponly` + `samesite=Lax`. |
| Enumeração de usuários | Mensagens genéricas em login e recuperação de senha (não revelam se um e-mail existe). |
| Erros | `display_errors` desligado; exceções de banco nunca chegam à tela, só ao log do servidor. |

## 7. O que ficou fora do escopo (e por quê)

- **Múltiplos idiomas/moedas**: fora do escopo de um sistema local para uma única casa de festas.
- **Edição/cancelamento de reserva pelo próprio cliente**: o cliente solicita e acompanha o status; a
  confirmação/cancelamento é sempre uma decisão do administrador, o que mantém o fluxo auditável e evita
  condições de corrida entre cliente e admin editando a mesma reserva simultaneamente.
- **Envio real de e-mail**: descrito na seção 4.
