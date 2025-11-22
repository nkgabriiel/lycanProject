# Projeto Lycan

# Funcionalidades Principais
* **RF1 (Cadastro):** Usuários podem criar uma nova conta com nome, e-mail e senha.

* **RF2 (Login):** Autenticação segura com e-mail e senha, incluindo proteção contra tentativas de força bruta.

* **RF3 (Roteamento por Perfil):** Após o login, usuários admin são redirecionados para um dashboard de gerenciamento, enquanto usuários user são levados para uma página inicial.

* **RF4 (Proteção de Páginas):** Um script "guard" (verifica_sessao.php) protege o acesso a páginas restritas.

* **RF5 (Gerenciamento de Admin):** No dashboard, o administrador pode visualizar todos os usuários e alterar suas permissões.

* **RF6 (Logout):** Encerramento seguro da sessão do usuário.

## Requisitos Técnicos
Linguagem: PHP com estrutura de pastas separando lógica (app/) e arquivos públicos (public/).

* **Banco de Dados:** MySQL com PDO e prepared statements para todas as interações com o banco.
* **Senhas:** Armazenamento de senhas com hash (`password_hash`) e verificação com `password_verify`.
*   **Sessões:** Regeneração de ID de sessão (`session_regenerate_id(true)`) após login bem-sucedido.
* **Redirecionamento:** Uso de `header('Location: ...'); exit;` para redirecionamentos seguros.
* **Configuração de Sessão (Opcional/Bônus):** `session.cookie_httponly` e `session.cookie_secure` (se HTTPS ) configurados em `config.php`.

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
3.1 - Abra seu navegador e acesse a URL da pasta public do projeto (ex: http://localhost/Projeto/public/). A página de login (index.php) será exibida.

## Credenciais de Teste

**E-mail:** admin@example.com
**Senha:** Senha123!


## Fluxo da Aplicação
1 - O usuário acessa a pasta public/, que exibe index.php.

2 - As credenciais são submetidas via POST para app/autentica.php.

3 - autentica.php valida os dados. Se forem válidos, uma sessão é criada e o usuário é redirecionado:

4 - Admins para public/dashboard.php.

5 - Usuários comuns para public/pagina_inicial.php.

6 - Todas as páginas restritas incluem app/verifica_sessao.php para garantir que apenas usuários logados possam acessá-las, com verificação adicional de perfil quando necessário.

7 - O link "Sair" aponta para app/logout.php, que destrói a sessão e redireciona o usuário para a página de login.
