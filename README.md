# MOVIX — Gestão Ferroviária Inteligente

## 1. Sobre o projeto

O **MOVIX** é um sistema web de gestão ferroviária desenvolvido pela equipe do curso Técnico em Desenvolvimento de Sistemas do SESI SENAI Joinville, como parte da Situação de Aprendizagem Ferroramas.

O projeto tem como objetivo auxiliar no gerenciamento de informações ferroviárias, reunindo recursos para acompanhamento de trens, administração de usuários, controle de rotas e sensores, visualização de alertas e geração de relatórios.

A aplicação está sendo desenvolvida por etapas, integrando interface, programação backend e banco de dados.

## 2. Objetivos

- Desenvolver um sistema de gestão ferroviária organizado e funcional.
- Facilitar o gerenciamento das informações dos trens e das rotas.
- Permitir o cadastro e a administração dos usuários.
- Centralizar informações de monitoramento e alertas.
- Aplicar conceitos de programação web, banco de dados e segurança.
- Trabalhar com organização de tarefas, versionamento de código e desenvolvimento em equipe.

## 3. Funcionalidades do sistema

### Dashboard
Apresenta indicadores e informações gerais sobre a operação ferroviária.

### Monitoramento
Reúne informações relacionadas ao acompanhamento dos trens.

### Alertas
Organiza informações sobre alertas relacionados à operação ferroviária.

### Trens
Área destinada ao gerenciamento da frota, com informações como:

- Código e nome do trem;
- Linha e destino;
- Velocidade;
- Status operacional.

O gerenciamento contempla operações de cadastro, consulta, edição e exclusão, conforme a implementação de cada funcionalidade.

### Rotas
Área destinada ao gerenciamento das rotas ferroviárias, incluindo informações como nome, código, origem, destino, distância, tempo estimado e situação.

### Sensores
Área destinada à visualização e ao gerenciamento das informações dos sensores ferroviários.

### Usuários
Área destinada ao cadastro e ao gerenciamento dos usuários do sistema, incluindo informações pessoais e dados de acesso.

### Relatórios
Página destinada à consulta e à apresentação de informações relacionadas à operação ferroviária.

### Autenticação e controle de acesso
O projeto contempla o desenvolvimento de login, gerenciamento de sessões e controle de permissões por perfil de usuário.

A disponibilidade efetiva de cada operação depende da implementação e dos testes realizados na versão atual do sistema.

## 4. Tecnologias utilizadas

- **PHP:** programação do backend e das páginas do sistema;
- **HTML5:** estruturação das páginas;
- **CSS3:** estilização e identidade visual;
- **Bootstrap 5.3.7:** componentes e organização da interface;
- **JavaScript:** recursos de interação no frontend;
- **MySQL:** gerenciamento do banco de dados;
- **MySQLi:** comunicação entre PHP e MySQL;
- **XAMPP:** ambiente de desenvolvimento local;
- **Visual Studio Code:** edição do código;
- **Git e GitHub:** versionamento e compartilhamento do projeto.

## 5. Estrutura do projeto

A organização dos arquivos segue a estrutura existente no repositório:

```text
S.A-Projeto-Movix/
├── assets/
│   └── imgs/
├── components/
│   └── sidebar.php
├── database/
│   └── db.sql
├── doc/
├── funcoes/
├── infra/
│   └── conexao.php
├── public/
│   ├── alertas.php
│   ├── dashboard_adm.php
│   ├── dashboard_usuario.php
│   ├── monitoramento.php
│   ├── relatorios.php
│   ├── rotas.php
│   ├── trens.php
│   └── usuarios.php
├── style/
│   └── style.css
├── pesquisas/
│   └── apis.md
├── index.php
└── README.md
```

**Observação:** a estrutura acima representa a organização de referência do projeto. Os arquivos podem variar conforme as alterações realizadas durante o desenvolvimento. As páginas de cadastro e os demais arquivos de processamento devem permanecer nos diretórios em que estão atualmente no repositório.

## 6. Como executar o projeto

### Pré-requisitos

- XAMPP instalado;
- Apache e MySQL disponíveis no ambiente local;
- Navegador atualizado;
- Projeto clonado ou baixado do GitHub.

### Passo 1 — Obter o projeto

Clone o repositório:

```bash
git clone https://github.com/Moraes-Code/S.A-Projeto-Movix.git
```

### Passo 2 — Configurar o ambiente

Coloque a pasta do projeto no diretório `htdocs` do XAMPP.

Exemplo:

```text
C:\xampp\htdocs\S.A-Projeto-Movix
```

Abra o painel do XAMPP e inicie o Apache e o MySQL.

### Passo 3 — Configurar o banco de dados

1. Acesse `http://localhost/phpmyadmin`.
2. Verifique o arquivo `database/db.sql`.
3. Importe o arquivo SQL no phpMyAdmin para criar as tabelas e os dados definidos nele.
4. Confira se o nome do banco de dados corresponde ao utilizado no arquivo `infra/conexao.php`.
5. Verifique as credenciais e os parâmetros da conexão.

O nome do banco, as tabelas e as colunas devem ser compatíveis com as consultas utilizadas nos arquivos PHP.

### Passo 4 — Acessar o sistema

Com o Apache iniciado, abra:

```text
http://localhost/S.A-Projeto-Movix/
```

Se houver redirecionamento para o login, utilize uma conta válida cadastrada no banco de dados, conforme as regras de autenticação implementadas.

## 7. Banco de dados e segurança

A camada de persistência utiliza MySQL, com acesso realizado pelo PHP.

Durante a implementação, devem ser observadas as seguintes práticas:

- Utilização de consultas preparadas para operações no banco de dados;
- Validação dos dados recebidos pelos formulários no servidor;
- Armazenamento seguro de senhas com `password_hash()`;
- Verificação de senhas com `password_verify()`;
- Gerenciamento de sessões para identificar usuários autenticados;
- Verificação de permissões no backend;
- Tratamento de erros de conexão e execução de consultas;
- Proteção das operações de cadastro, edição e exclusão.

As medidas de segurança devem ser verificadas por meio de testes antes da apresentação do sistema.

## 8. Documentação e organização

O projeto conta com diretórios destinados à organização do código, dos recursos visuais, da infraestrutura, das funcionalidades e dos documentos de apoio.

A pasta `pesquisas/` reúne materiais de pesquisa relacionados às tecnologias utilizadas no desenvolvimento.

O documento `pesquisas/apis.md` apresenta uma pesquisa sobre APIs, seus conceitos, funcionamento, tipos e aplicações no desenvolvimento de sistemas.

A pasta `doc/` reúne documentos complementares do projeto.

## 9. Situação atual — 9ª etapa

O projeto encontra-se em desenvolvimento na Situação de Aprendizagem Ferroramas.

Nesta etapa, o objetivo é consolidar o sistema para sua primeira apresentação funcional, verificando a integração entre frontend, backend e banco de dados.

As atividades de validação incluem:

- Conferência da conexão com o banco de dados;
- Testes das operações CRUD de usuários, trens, rotas e sensores;
- Verificação do login e do controle de sessões;
- Testes das permissões de acesso por perfil;
- Validação dos formulários e do tratamento de erros;
- Revisão da organização do código;
- Pesquisa e documentação sobre APIs;
- Registro das tarefas, dos commits e das evidências de desenvolvimento;
- Preparação de uma demonstração prática do sistema.

**Status:** em desenvolvimento. A conclusão de cada funcionalidade deve ser confirmada por testes no ambiente local.

## 10. Próximas atividades

- Corrigir e validar eventuais inconsistências entre o banco de dados e as consultas PHP;
- Concluir os testes dos cadastros, das consultas, das edições e das exclusões;
- Verificar a autenticação e as permissões de acesso;
- Revisar a documentação técnica;
- Organizar as evidências do desenvolvimento;
- Preparar a apresentação prática do MOVIX.

## 11. Equipe

Projeto desenvolvido por:

- André Foss Alflen;
- Bruno Leorci Moraes;
- Felipe Favero de Oliveira;
- Kauã Gabriel Borges;
- Matheus Bernardo de Oliveira Rüeckl.

Curso Técnico em Desenvolvimento de Sistemas — SESI SENAI Joinville.

## 12. Finalidade acadêmica

O MOVIX é um projeto acadêmico desenvolvido para aplicar, na prática, os conhecimentos adquiridos durante o curso, incluindo programação web, banco de dados, segurança, documentação, versionamento e trabalho em equipe.
