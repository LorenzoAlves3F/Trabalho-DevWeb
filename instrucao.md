Trabalho T2: Desenvolvimento de um Sistema 
Web Completo usando PHP e MySQL
Objetivo
 Desenvolver um sistema web completo utilizando PHP e MySQL, aplicando 
conceitos de programação back-end, banco de dados, front-end (HTML, CSS, 
JavaScript)  e  segurança.  O  sistema  deve  incluir  funcionalidades  como  cadastro  de 
usuários, autenticação, CRUD (Create, Read, Update, Delete) e relatórios.  
 Você deve definir livremente o tema do sistema web, escolhendo um contexto que 
faça  sentido  para  sua  realidade  (ex.:  sistema  de  agendamento  de  aulas,  gestão  de 
biblioteca, controle de treinos esportivos, controle financeiro simples, reservas de 
laboratório, gestão de TCCs, agenda de clientes, etc.). 
Requisitos do Sistema
1. Front-end (Interface do Usuário) 
•Páginas responsivas usando HTML5, CSS3 e JavaScript. 
•Uso  de  Bootstrap  ou  outro  framework  CSS  para  melhorar  a  aparência  e 
usabilidade. 
•Formulários  válidos  e  interativos  (feedback  visual  de  erros,  mensagens  de 
sucesso, etc.). 
2. Back-end (Lógica do Sistema) 
•Desenvolvimento em PHP puro (sem frameworks como Laravel). 
•Conexão com banco de dados MySQL usando PDO ou MySQLi, com 
tratamento adequado de erros. 
•Organização  do  código  em  MVC  (Model-View-Controller)  ou  outra  estrutura 
bem  definida,  com  separação  entre  regras  de  negócio,  acesso  a  dados  e 
apresentação. 
3. Banco de Dados 
•Modelagem do banco de dados (diagrama ER) do problema escolhido. 
•Criação  de  tabelas  necessárias,  com  chaves  primárias  e  estrangeiras  bem 
definidas. 
•Queries SQL para inserção, atualização, exclusão e consulta. Evite 
redundâncias e garanta integridade dos dados. 
4. Funcionalidades Obrigatórias 
•Autenticação de usuários (login, logout, recuperação de senha). 
•CRUD completo para pelo menos uma entidade (ex.: alunos, clientes, 
produtos, treinos, reservas, etc.). 
•Sessões/cookies  para  controle  de  acesso  e  manutenção  de  estado  entre 
páginas.. 
•Validação  de  formulários  no  front-end  (JavaScript)  e  no  back-end  (PHP),  com 
mensagens de erro adequadas. 
•Geração  de  relatórios  (por  exemplo,  PDF  ou  Excel)  a  partir  dos  dados  do 
sistema, com filtros úteis para o tema escolhido. 
Segurança 
•Proteção  contra  SQL  Injection,  utilizando  prepared  statements  e  validação 
adequada de entradas. 
•Validação e saneamento de dados no back-end antes de qualquer operação no 
banco. 
•Hash  de  senhas  usando  password_hash  (ou  função  equivalente  atualizada), 
nunca armazenando senhas em texto plano. 
•Medidas  básicas  contra  XSS  (Cross-Site  Scripting),  como  escape  de  saídas  e 
cuidado com dados exibidos em páginas.
Critério Nota Descrição
Front-end (UI/UX) 1,5 Design responsivo, usabilidade, validação de 
formulários no cliente.
Back-end (PHP) 3,0 Lógica funcional, estrutura organizada, tratamento de 
erros.
Banco de Dados 
(MySQL) 2,0 Modelagem correta, queries eficientes, integridade 
dos dados.
Funcionalidades 2,0 CRUD completo, autenticação, sessões, relatórios.
Segurança 1,0 Proteção contra SQL Injection, XSS, hash de senhas.
Documentação 0,5 README com instruções de instalação, diagrama do 
banco de dados.
Entrega
•Código Fonte (Link do GitHub). 
•Script do Banco de Dados (arquivo .sql). 
•Documentação (README.md explicando como executar o projeto). 
•Relatório (explicando as decisões técnicas). 