# MOVIX — Gestão Ferroviária Inteligente

Sistema web desenvolvido para a Situação de Aprendizagem **Ferroramas**, do curso Técnico em Desenvolvimento de Sistemas do **SESI SENAI Joinville**.

O MOVIX tem como objetivo centralizar o monitoramento, o controle e a gestão de operações ferroviárias, reunindo informações sobre usuários, trens, rotas e sensores em uma única plataforma.

---

## 1. Objetivo do Projeto

Desenvolver um sistema de gestão ferroviária que facilite o acompanhamento das operações, permitindo organizar informações, monitorar equipamentos e administrar os dados da malha ferroviária.

## 2. Funcionalidades

- Login e logout de usuários.
- Controle de acesso conforme o perfil.
- Dashboard administrativo e de usuário.
- Cadastro, consulta, edição e exclusão de usuários.
- Cadastro, consulta, edição e exclusão de trens.
- Gerenciamento de rotas ferroviárias.
- Gerenciamento de sensores.
- Monitoramento de trens e sensores.
- Consulta de status operacional.
- Visualização de indicadores e informações do sistema.
- Controle de permissões de acesso.

## 3. Perfis de Acesso

| Perfil | Permissões |
|---|---|
| Administrador | Gerenciamento administrativo e controle dos registros. |
| Operário | Acesso às funcionalidades operacionais permitidas. |
| Usuário | Acesso às funcionalidades de consulta disponibilizadas. |

## 4. Tecnologias Utilizadas

| Tecnologia | Aplicação |
|---|---|
| PHP | Desenvolvimento do backend. |
| MySQL | Banco de dados relacional. |
| MySQLi | Comunicação com o banco de dados. |
| HTML5 | Estrutura das páginas. |
| CSS3 | Estilização da interface. |
| Bootstrap 5.3.7 | Componentes e responsividade. |
| JavaScript | Interações no frontend. |
| Font Awesome 6.7.2 | Ícones. |
| XAMPP | Ambiente de desenvolvimento local. |
| Git e GitHub | Versionamento e armazenamento do projeto. |

## 5. Banco de Dados

O banco de dados utilizado possui o nome `movix`.

O script de criação está disponível em:

`database/db.sql`

Principais tabelas:

| Tabela | Descrição |
|---|---|
| `usuarios` | Armazena os dados dos usuários. |
| `perfis` | Define os níveis de acesso. |
| `trens` | Armazena informações dos trens. |
| `rotas` | Registra as rotas ferroviárias. |
| `sensores` | Armazena informações dos sensores. |

### Relacionamentos

- Usuários podem ser associados às rotas como responsáveis.
- Rotas podem possuir trens relacionados.
- Trens podem possuir sensores associados.

## 6. Estrutura do Projeto

```text
S.A-Projeto-Movix/
│
├── assets/
│   ├── imgs/
│   └── prints/
│
├── components/
│   ├── sidebar.php
│   └── notificacao.php
│
├── database/
│   └── db.sql
│
├── doc/
│   └── APIs.md
│
├── funcoes/
│   ├── cadastro_usuarios.php
│   └── cadastro_trens.php
│
├── infra/
│   └── conexao.php
│
├── public/
│   ├── dashboard_adm.php
│   ├── dashboard_usuario.php
│   ├── monitoramento.php
│   ├── usuarios.php
│   ├── trens.php
│   ├── rotas.php
│   └── sensores.php
│
├── style/
│   └── style.css
│
├── index.php
├── MANUAL.md
└── README.md
```

## 7. Como Executar o Projeto

### Pré-requisitos

- XAMPP instalado.
- Apache e MySQL.
- Navegador atualizado.
- Editor de código (recomendado: Visual Studio Code).

### 1. Clonar o repositório

```bash
git clone https://github.com/Moraes-Code/S.A-Projeto-Movix.git
```

### 2. Configurar a pasta

Coloque o projeto dentro do diretório:

```text
C:\xampp\htdocs\
```

### 3. Iniciar o XAMPP

Abra o painel do XAMPP e inicie os serviços:

- Apache
- MySQL

### 4. Importar o banco de dados

1. Acesse `http://localhost/phpmyadmin`.
2. Entre na opção **Importar**.
3. Selecione o arquivo `database/db.sql`.
4. Execute a importação.
5. Verifique se o banco `movix` foi criado corretamente.

### 5. Configurar a conexão

O arquivo responsável pela conexão é:

`infra/conexao.php`

Configuração utilizada no ambiente de desenvolvimento:

- Host: `localhost`
- Usuário: `root`
- Senha: vazia (configuração local padrão).
- Banco: `movix`
- Porta: `3315`

Caso o MySQL esteja configurado em outra porta, ajuste o arquivo de conexão.

### 6. Acessar o sistema

Abra o navegador e acesse:

```text
http://localhost/S.A-Projeto-Movix/
```

Utilize uma conta previamente cadastrada no banco de dados para realizar o login.

## 8. Segurança

O sistema utiliza mecanismos de segurança para proteger as operações e os dados, incluindo:

- Armazenamento de senhas com `password_hash()`.
- Verificação de senhas com `password_verify()`.
- Consultas preparadas para operações com dados recebidos dos formulários.
- Controle de acesso por sessões.
- Verificação de permissões conforme o perfil.
- Validação de informações no backend.
- Tokens de sessão para operações específicas.

## 9. Documentação

O projeto possui materiais complementares:

- **MANUAL.md:** manual de utilização do sistema, com orientações para acesso e navegação.
- **doc/APIs.md:** pesquisa sobre APIs e integração entre sistemas.
- **database/db.sql:** script de criação do banco de dados.

## 10. Identidade Visual

A identidade visual do MOVIX foi desenvolvida com foco no contexto ferroviário e tecnológico.

| Elemento | Cor |
|---|---|
| Azul principal | `#0D1B2A` |
| Azul secundário | `#1B4F72` |
| Azul de destaque | `#5DADE2` |
| Fundo claro | `#F4F6F9` |
| Verde | `#22C55E` |
| Vermelho | `#EF4444` |
| Amarelo | `#F59E0B` |

## 11. Contexto Acadêmico

O MOVIX foi desenvolvido como parte da Situação de Aprendizagem Ferroramas, aplicando conhecimentos adquiridos durante o curso Técnico em Desenvolvimento de Sistemas.

Durante o desenvolvimento, foram trabalhados conceitos de:

- Desenvolvimento web.
- Programação backend e frontend.
- Modelagem e gerenciamento de banco de dados.
- Operações CRUD.
- Autenticação e controle de acesso.
- Segurança da informação.
- Organização de código.
- Versionamento com Git e GitHub.
- Trabalho em equipe e resolução de problemas.

---

**MOVIX — Gestão Ferroviária Inteligente**

Projeto desenvolvido para a Situação de Aprendizagem Ferroramas — SESI SENAI Joinville.