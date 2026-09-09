PESQUISA SOBRE PHP	              				

O QUE É PDO?

O PDO (PHP Data Objects) é uma extensão do PHP que fornece uma interface leve e consistente para acessar bancos de dados.

Ele funciona como uma camada de abstração de acesso a dados. Isso significa que você usa os mesmos métodos e comandos para se conectar e manipular diferentes sistemas de gerenciamento de banco de dados (SGBDs), como MySQL, PostgreSQL, SQLite e Oracle.

Como o PDO é utilizado no PHP?

O PDO (PHP Data Objects) é uma extensão do PHP utilizada como uma interface padronizada para conectar e interagir com bancos de dados. 

Padronizar conexões: Permite que você use o mesmo código (as mesmas funções) para conversar com diferentes bancos de dados, como MySQL, PostgreSQL, SQLite e SQL Server. Se você mudar o banco, muda quase nada no código PHP.
Aumentar a segurança: Ele possui suporte nativo a Prepared Statements (comandos preparados), que é a forma mais eficiente de proteger sua aplicação contra ataques de SQL Injection.

Como funciona uma conexão php utilizando o PDO?

Uma conexão em PHP utilizando PDO (PHP Data Objects) funciona por meio da instanciação de um objeto da classe nativa PDO, que utiliza uma string DSN (Data Source Name) para definir o tipo de banco de dados, servidor e nome da base, juntamente com as credenciais de acesso 

Principais características

O PDO (PHP Data Objects) é uma extensão do PHP que fornece uma interface leve e consistente para acessar bancos de dados relacionais.
Principais Características do PDO
Interface Uniforme: Usa os mesmos métodos para consultar e buscar dados, independentemente do banco de dados utilizado. 
Orientação a Objetos: Todo o seu funcionamento é baseado em classes e objetos, facilitando a organização do código. 
Suporte a Múltiplos Bancos: Funciona com diversos sistemas, como MySQL, PostgreSQL, SQLite, Oracle e SQL Server, bastando trocar o driver. 
Segurança contra SQL Injection: Oferece suporte nativo a prepared statements (declarações preparadas), permitindo a utilização de consultas parametrizadas. 
Gerenciamento de Transações: Permite controlar transações (com commit e rollback) de forma segura e integrada. 
Bom Desempenho: É escrito em linguagem C e compilado diretamente no PHP, o que garante alta velocidade de execução. 
Principal diferença entre PDO  e PHP
A principal diferença entre PDO e MySQLi no PHP é que o PDO suporta mais de 10 drivers diferentes (como MySQL, PostgreSQL, SQLite, Oracle). Enquanto o MySQLi funciona exclusivamente com servidores MySQL e MariaDB 
Vantagens e Desvantagens de utilizar o PDO
Vantagens
Suporte a múltiplos bancos: Funciona com MySQL, PostgreSQL, SQLite, Oracle e outros usando a mesma interface básica.
Segurança avançada: Facilita o uso de prepared statements, o que protege o sistema contra ataques de SQL Injection.
Orientação a objetos: Usa uma API moderna baseada em classes e objetos.
Tratamento de erros: Permite capturar erros facilmente usando blocos try/catch com exceções nativas.
Desvantagens
Curva de aprendizado: Pode ser mais complexo para iniciantes que estão acostumados com funções procedural antigas.
Falsa ideia de portabilidade: Mudar de um SGBD para outro (como do MySQL para o PostgreSQL) ainda exige ajustes, pois a sintaxe SQL específica de cada banco pode variar.
Sem tradução nativa: Não traduz comandos SQL específicos, apenas envia a instrução diretamente ao driver do banco correspondente.
