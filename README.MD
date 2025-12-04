# Projeto Lycan

# Funcionalidades Principais

## Autenticação & Usuários

* **RF1 (Cadastro):** Usuários podem criar uma nova conta com nome, e-mail e senha.

* **RF2 (Login):** Autenticação segura com e-mail e senha, incluindo proteção contra tentativas de força bruta.

* **RF3 (Roteamento por Perfil):** Após o login, usuários admin são redirecionados para um dashboard de gerenciamento, enquanto usuários user são levados para uma página inicial.

* **RF4 (Proteção de Páginas):** Um script "guard" (verifica_sessao.php) protege o acesso a páginas restritas.

* **RF5 (Gerenciamento de Admin):** No dashboard, o administrador pode visualizar todos os usuários e alterar suas permissões.

* **RF6 (Logout):** Encerramento seguro da sessão do usuário.

## Produtos & Catálogo

* **RF7 (API REST de Produtos ):**Endpoint /app/api/produtos.php com suporte a:
- **GET** Listar todos os produtos
- **GET?id=** Buscar produto específico
- **GET?tipo=lancamentos** Listar produtos recém lançados
- **GET?tipo=mais_vendidos** Listar produtos mais vendidos
- **POST** Inserir produto (como admin)
- **PUT** Atualizar produto (como admin)
- **Delete** Deletar produto (como admin)

**RF9 (Sistema de Busca):**: Pesquisa por nome, descrição ou categoria via SQL otimizado.

**RF10 — (Detalhes do Produto):**: Página produto.php carregando dados via API.

## Carrinho de Compras

**RF11** Adicionar, remover e atualizar itens (via controllers PHP).

**RF12** Cálculo de frete automático via API externa (CEP).

**RF13** Exibição de subtotal e total, além de quantidade por item.

## Administração

**RF14 (Dashboard administrativo)**:Acesso exclusivo do admin.

**RF16 (CRUD completo de produtos)**: Listagem, Edição, Criação, Exclusão (via endpoint DELETE)

**RF16 (CRUD de usuários)**: Alteração de perfis (promover user → admin).

## Requisitos Técnicos

# Arquitetura

PHP organizado em:

**Lógica**
/app -> pasta raiz lógica
/app/api -> API REST
/app/auth -> Controle de autenticação, resets
/app/controller -> Controle dos CRUDs, alterações de dados de usuários
/app/core -> Coração da aplicação, principais dados e arquivos importantes
/app/services -> APIs Externas, como cálculo do frete

**Estilização**
/assets -> Pasta com os principais arquivos de estilização e ativos do programa
/assets/img -> Imagens nos arquivos dos produtos (futuramente substuidos por imagens alocadas no servidor)

**View**
/public -> Todos as páginas que são vistas por quem usa a aplicação

**Scripts**
/scripts -> Javascript para algumas funções das páginas

**Database**
/sql -> Banco de dados local 

## Banco de dados

* **Banco de Dados:** MySQL com PDO e prepared statements para todas as interações com o banco.
* **Tabelas:** usuarios, password_reset, user_logs, categorias, produtos

## Segurança

* **Senhas:** Armazenamento de senhas com hash (`password_hash`) e verificação com `password_verify`.
* **Sessões:** Regeneração de ID de sessão (`session_regenerate_id(true)`) após login bem-sucedido.
* **Configuração de Sessão :** `session.cookie_httponly` e `session.cookie_secure` (se HTTPS ) configurados em `config.php`.
* **Proteção Api:** API protegida contra SQL Injection.

## API & FRONTEND
* PHP RESTful utilizando métodos GET, POST, PUT, DELETE.
* HTML + CSS/JS nativo, acessando a API via PHP.

## Passos para Rodar o Projeto
# Pré-requisitos
-- Servidor web local (XAMPP, WampServer, etc.).
-- PHP (versão 7.4   ou superior).
-- Servidor de banco de dados MySQL ou MariaDB.

1.  **Configuração do Banco de Dados**
1.1 - Crie um banco de dados no seu servidor (ex: phpMyAdmin) com o nome sistema_auth.
1.2 - Importe o arquivo schema.sql para criar as tabelas usuarios e auth_tokens, e para inserir um usuário administrador de teste.

2. **Configuração do Projeto**
2.1 - Copie a pasta do projeto para o diretório do seu servidor web (ex: htdocs/ no XAMPP).
2.2 - Abra o arquivo app/config.php em um editor de texto.
2.3 - Ajuste a constante BASE_URL para corresponder à sua URL local (ex: define('BASE_URL', 'http://localhost/Projeto');).
2.4 - Se necessário, altere as credenciais de acesso ao banco de dados (DB_HOST, DB_USER, DB_PASS).

3. **Acesso**
3.1 - Abra seu navegador e acesse a URL da pasta public do projeto (ex: http://localhost/projectlycan/public/). A página de login (index.php) será exibida.

## Credenciais de Teste

**E-mail:** admin@example.com
**Senha:** Senha123!


## Fluxo da Aplicação

1 - O usuário acessa a pasta public/, que exibe index.php.

2 - As credenciais são submetidas via POST para app/auth/autentica.php.

3 - Se válidas:
-> Admin: dashboard.php
-> Usuário: pagina_inicial.php

4 - Páginas restritas são enviadas 

5 - Opções do usuário:
-> Navegar pelo catálogo
-> Ver produtos
-> Usar busca
-> Adicionar ao carrinho
-> Editar perfil

6 - Opções do admin:
-> Gerir produtos
-> Gerir usuários 
-> Ver logs de auditoria 

7 - Sair da aplicação via app/auth/logout.php

## Tecnologias utilizadas

- PHP 8
- MySQL + PDO
- API REST própria
- HTML5 & CSS3
- Sessões Seguras
- Railway (produção)

## Deploy em produção

Suporte para railway.app

