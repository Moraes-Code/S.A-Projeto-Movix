MOVIX — Gestão Ferroviária Inteligente
1. Sobre o projeto
O MOVIX é um sistema web de gestão ferroviária desenvolvido como parte da Situação de Aprendizagem Ferroramas, no curso Técnico em Desenvolvimento de Sistemas do SESI SENAI Joinville.

O projeto tem como objetivo centralizar informações e apoiar processos relacionados à operação ferroviária, incluindo o gerenciamento de trens, rotas, usuários, monitoramento, alertas e relatórios.

Este README também documenta as regras de negócio, os requisitos funcionais e os requisitos não funcionais definidos para o sistema. A inclusão de um requisito nesta documentação não significa, por si só, que ele já esteja implementado: cada item deve ser validado durante o desenvolvimento e os testes.
2. Objetivos
- Organizar informações relacionadas à operação ferroviária.
- Permitir o gerenciamento de trens, rotas e usuários.
- Apoiar o acompanhamento do status das viagens e da ocupação dos trens.
- Documentar as regras de negócio e os requisitos do sistema.
- Aplicar conceitos de programação web, banco de dados, segurança e desenvolvimento em equipe.
3. Funcionalidades previstas
O escopo documentado inclui as seguintes funcionalidades:
- Cadastro e autenticação de usuários e passageiros.
- Consulta de horários e status de viagens.
- Compra, cancelamento e remarcação de passagens.
- Escolha e controle de ocupação dos assentos.
- Geração e validação de bilhetes eletrônicos.
- Cadastro e gerenciamento de trens e rotas.
- Histórico de compras e viagens.
- Rastreamento de trens e notificações de atraso e embarque.
- Programa de fidelidade.
- Controle de acesso e proteção dos dados dos usuários.
Observação: a lista acima representa o escopo previsto nos requisitos fornecidos. A disponibilidade de cada funcionalidade deve ser confirmada no código e por testes no ambiente de execução.
4. Regras de negócio
As regras de negócio definem as condições e restrições que o sistema deve respeitar durante as operações.
Código	Regra de negócio
- RN 01	Cada passageiro poderá transportar bagagem respeitando o limite máximo de peso permitido pela empresa.
- RN 02	O embarque deverá ser encerrado 15 minutos antes do horário de partida.
- RN 04	O sistema deverá bloquear automaticamente a venda quando todos os assentos estiverem ocupados.
- RN 05	O passageiro deverá apresentar documento oficial com foto no momento do embarque.
- RN 06	O passageiro que perder o horário de embarque não terá direito automático ao reembolso da passagem.
- RN 07	Não será permitido o embarque com dados divergentes entre documento e passagem.
- RN 08	O sistema não permitirá cadastro de usuários menores de idade sem responsável legal.
- RN 09	Uma passagem será vinculada apenas a um único CPF.
- RN 10	Caso o trem apresente atraso superior a determinado período, o sistema deverá notificar os passageiros cadastrados.
- RN 11	O passageiro poderá remarcar sua viagem apenas uma vez sem taxa, dentro do prazo permitido.
- RN 12	Compras promocionais poderão possuir restrições de cancelamento ou reembolso.
- RN 13	O sistema deverá registrar histórico de compras e viagens de cada usuário.
- RN 14	O pagamento deverá ser confirmado antes da emissão do bilhete eletrônico.
- RN 15	O sistema não permitirá reserva sem a seleção obrigatória do trecho da viagem.
- RN 16	O trem não poderá sair antes do horário oficial programado.
- RN 17	Em caso de falha no pagamento, a reserva do assento será cancelada automaticamente após determinado tempo.
- RN 18	Usuários com muitas tentativas inválidas de login poderão ter acesso temporariamente bloqueado.
- RN 19	Um passageiro não poderá embarcar sem possuir passagem válida cadastrada no sistema.
- RN 20	Cada assento poderá ser reservado por apenas um passageiro em uma mesma viagem.
- RN 21	O cancelamento da passagem poderá ser realizado somente até 24 horas antes da viagem.
- RN 22	Passageiros com direito à meia-entrada deverão apresentar documento comprobatório no embarque.
- RN 23	Crianças de até 5 anos poderão viajar gratuitamente, desde que não ocupem assento exclusivo.
- RN 24	O trem só poderá iniciar a viagem caso toda a tripulação obrigatória esteja confirmada no sistema.
- RN 25	O mesmo CPF não poderá reservar o mesmo assento para a mesma viagem mais de uma vez.


Nota sobre a numeração: a relação recebida não contém uma descrição para RN 03; por isso, a numeração original foi preservada.

Algumas regras ainda precisam de parâmetros definidos antes de serem implementadas, como o limite de peso da bagagem, o período que caracteriza atraso, o prazo permitido para remarcação e o tempo para cancelar uma reserva após falha de pagamento.

5. Requisitos funcionais
Os requisitos funcionais descrevem as ações e os serviços que o sistema deve oferecer aos usuários.
Código	Requisito funcional
- RF 01	O sistema deve permitir cadastro de passageiros.
- RF 02	O sistema deve permitir login de usuários.
- RF 03	O sistema deve permitir consulta de horários dos trens.
- RF 04	O sistema deve permitir compra de passagens.
- RF 05	O sistema deve permitir escolha de assento.
- RF 06	O sistema deve gerar bilhete eletrônico.
- RF 07	O sistema deve permitir cancelamento de passagens.
- RF 08	O sistema deve exibir status da viagem.
- RF 09	O sistema deve permitir cadastro de trens.
- RF 10	O sistema deve permitir gerenciamento de rotas.
- RF 11	O sistema deve registrar histórico de viagens do usuário.
- RF 12	O sistema deve permitir validação de bilhetes no embarque.
- RF 13	O sistema deve permitir rastreamento do trem em tempo real.
- RF 14	O sistema deve informar o nível de ocupação do trem.
- RF 15	O sistema deve sugerir assentos próximos para grupos e famílias.
- RF 16	O sistema deve enviar notificações de atraso e embarque.
- RF 17	O sistema deve permitir programa de fidelidade.
- RF 18	O sistema deve permitir remarcação de passagens.


6. Requisitos não funcionais
Os requisitos não funcionais definem critérios de qualidade, segurança, desempenho e compatibilidade.
Código	Requisito não funcional
- RNF 01	O sistema deve responder em até 2 segundos.
- RNF 02	O sistema deve estar disponível 24 horas por dia.
- RNF 03	O sistema deve suportar grande número de acessos simultâneos.
- RNF 04	O sistema deve garantir segurança dos dados dos usuários.
- RNF 05	O sistema deve ter criptografia nos pagamentos.
- RNF 06	O sistema deve ser compatível com dispositivos móveis.
- RNF 07	O sistema deve ter interface intuitiva e fácil de usar.
- RNF 08	O sistema deve ter alta disponibilidade (99%).
- RNF 09	O sistema deve permitir backup automático dos dados.
- RNF 10	O sistema deve ser rápido na busca de passagens.
- RNF 11	O sistema deve possuir autenticação em duas etapas.
- RNF 12	O sistema deve funcionar em navegadores modernos.
- RNF 13	O sistema deve ter acessibilidade para pessoas com deficiência visual.
- RNF 14	O sistema deve suportar crescimento do banco de dados sem perda de desempenho.
- RNF 15	O sistema deve seguir a LGPD para proteção de dados.


7. Tecnologias do projeto
As tecnologias presentes no projeto MOVIX incluem:
- PHP para a programação backend.
- HTML5 para a estrutura das páginas.
- CSS3 para os estilos e a identidade visual.
- Bootstrap para componentes e layout responsivo.
- JavaScript para interações no frontend.
- MySQL para o banco de dados.
- MySQLi para a comunicação entre PHP e MySQL.
- XAMPP como ambiente de desenvolvimento local.
- Visual Studio Code como editor.
- Git e GitHub para versionamento.
A utilização de cada tecnologia e a implementação dos requisitos devem ser confirmadas na versão atual do código.

8. Estrutura do projeto
A organização de referência do repositório inclui os seguintes diretórios e arquivos:
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
├── pesquisas/
│   └── apis.md
├── style/
│   └── style.css
├── index.php
└── README.md
A estrutura pode variar conforme as alterações realizadas no desenvolvimento. Consulte os arquivos existentes no repositório antes de adicionar ou mover páginas.

9. Como executar localmente
Pré-requisitos
- XAMPP instalado.
- Apache e MySQL disponíveis.
- Navegador atualizado.
- Projeto clonado ou baixado do GitHub.

Passo 1 — Clonar o repositório.
git clone https://github.com/Moraes-Code/S.A-Projeto-Movix.git

Passo 2 — Colocar o projeto no XAMPP.
Coloque a pasta do projeto no diretório htdocs, por exemplo:
C:\xampp\htdocs\S.A-Projeto-Movix

Passo 3 — Iniciar os serviços.
Abra o painel do XAMPP e inicie o Apache e o MySQL.

Passo 4 — Configurar o banco de dados.

1. Acesse http://localhost/phpmyadmin.
2. Consulte o arquivo database/db.sql.
3. Importe o SQL para criar o banco e as tabelas definidos no arquivo.
4. Confira se o nome do banco, a porta, o usuário e a senha correspondem à configuração de infra/conexao.php.
5. Verifique se as tabelas e colunas correspondem às consultas executadas pelo PHP.

Passo 5 — Abrir o sistema.
Acesse:
http://localhost/S.A-Projeto-Movix/

A execução correta depende de o banco de dados estar configurado e de as páginas e funcionalidades necessárias estarem implementadas.

10. Segurança e proteção de dados
Para atender aos requisitos de segurança e privacidade, o desenvolvimento deve considerar:
- Armazenamento de senhas com password_hash() e verificação com password_verify().
- Consultas preparadas para interações com o banco de dados.
- Validação de dados no servidor.
- Controle de sessões e permissões de acesso.
- Proteção contra requisições indevidas e ataques CSRF.
- Proteção de dados pessoais e tratamento adequado conforme a LGPD.
- Criptografia dos dados de pagamento e uso de um provedor de pagamento apropriado, sem armazenar dados sensíveis de cartão indevidamente.
- Controle de tentativas inválidas de login.
- Backup e procedimentos de recuperação dos dados.
Esses itens devem ser validados por revisão do código, configuração do ambiente e testes de segurança. Sua presença nesta lista não comprova que já estejam implementados.
11. Validação dos requisitos
Para acompanhar o desenvolvimento, cada requisito deve ser validado com evidências de teste. Recomenda-se registrar:
- Código do requisito.
- Funcionalidade relacionada.
- Resultado esperado.
- Passos executados no teste.
- Resultado obtido.
- Situação: pendente, em desenvolvimento, aprovado ou reprovado.
- Evidência ou observação sobre a correção necessária.
Os requisitos de desempenho, disponibilidade, acessibilidade, segurança e capacidade precisam de testes e medições específicos. Não devem ser considerados atendidos apenas porque a página abre no navegador.
12. Etapa atual e próximos passos
Na etapa de consolidação do MOVIX, as prioridades são revisar a integração entre frontend, backend e banco de dados, testar os CRUDs existentes, verificar autenticação e permissões e manter a documentação atualizada.

Os requisitos de compra de passagens, pagamento, seleção de assentos, bilhete eletrônico, rastreamento em tempo real, notificações e fidelidade devem ser comparados com o escopo realmente implementado. Caso ainda não existam no código, devem ser tratados como requisitos previstos ou trabalho futuro, e não como funcionalidades concluídas.

Próximas atividades recomendadas:
1. Validar o esquema do banco de dados e as consultas PHP.
2. Testar login, sessões e controle de permissões.
3. Testar cadastro, listagem, edição e exclusão das entidades existentes.
4. Identificar quais requisitos já estão implementados e quais estão pendentes.
5. Definir os parâmetros de negócio que ainda estão indeterminados.
6. Planejar e executar testes funcionais, de segurança, desempenho e acessibilidade.
7. Preparar evidências para a apresentação do sistema.
13. Finalidade acadêmica
O MOVIX é um projeto acadêmico voltado à aplicação prática de conhecimentos de desenvolvimento de sistemas, programação web, banco de dados, segurança, documentação e trabalho em equipe.