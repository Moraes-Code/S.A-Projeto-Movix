# Pesquisa sobre APIs (Application Programming Interfaces)

**Projeto:** MOVIX — Gestão Ferroviária Inteligente  
**Situação de Aprendizagem:** Ferroramas — Etapa 9  
**Tema:** APIs e integração entre sistemas

## 1. Introdução

Atualmente, muitos sistemas precisam trocar informações para realizar suas atividades. Uma empresa pode utilizar um sistema para gerenciar funcionários, outro para controlar o estoque e outro para acompanhar entregas. Para que essas aplicações consigam compartilhar dados, é necessário utilizar mecanismos de comunicação e integração.

Nesse contexto, as APIs (Application Programming Interfaces) são importantes, pois permitem que diferentes aplicações se comuniquem de maneira organizada. Elas definem regras para solicitar informações, enviar dados e executar operações em outro sistema.

No desenvolvimento de sistemas, compreender o funcionamento das APIs é importante para criar aplicações que possam evoluir e se integrar a outros serviços. No projeto MOVIX, por exemplo, uma API poderia permitir que outros sistemas consultassem informações sobre trens, rotas e sensores ferroviários.

## 2. O que é uma API e qual é sua finalidade?

API é a sigla para *Application Programming Interface*, que significa Interface de Programação de Aplicações. Ela consiste em um conjunto de regras e recursos que permite que um programa solicite serviços ou informações a outro programa sem precisar conhecer todos os detalhes de sua implementação.

Uma API funciona como uma interface de comunicação entre sistemas. Ela define quais operações podem ser realizadas, quais dados precisam ser enviados e qual resposta será devolvida.

Entre suas principais finalidades estão:

- Permitir a comunicação entre aplicações diferentes.
- Compartilhar informações de maneira padronizada.
- Reutilizar funcionalidades já desenvolvidas.
- Integrar sistemas web, aplicativos móveis e serviços empresariais.
- Automatizar processos e reduzir a necessidade de operações manuais.

Por exemplo, um aplicativo de transporte pode utilizar uma API de mapas para obter informações sobre localização e trajetos. Dessa forma, não precisa desenvolver seu próprio serviço de mapas do zero.

## 3. Como funciona a comunicação entre sistemas por meio de APIs?

A comunicação normalmente ocorre entre um cliente e um servidor. O cliente é a aplicação que solicita uma informação ou operação, enquanto o servidor recebe a solicitação, processa a requisição e retorna uma resposta.

Em uma API web, essa comunicação geralmente acontece utilizando o protocolo HTTP ou sua versão segura, HTTPS.

O funcionamento básico ocorre nas seguintes etapas:

1. O cliente identifica o recurso que deseja acessar.
2. Envia uma requisição HTTP para o endereço da API.
3. O servidor recebe a requisição e verifica os dados enviados.
4. O sistema executa a operação solicitada, caso ela seja válida e autorizada.
5. O servidor retorna um código de status e, quando necessário, os dados da resposta.

Por exemplo, um sistema externo pode solicitar a lista de trens cadastrados no MOVIX. A API recebe a solicitação, consulta os registros disponíveis e devolve os dados em um formato que o sistema solicitante consegue interpretar.

Esse processo permite que duas aplicações desenvolvidas com linguagens ou tecnologias diferentes troquem informações, desde que sigam as regras de comunicação definidas pela API.

## 4. O que são APIs REST?

REST é a sigla para *Representational State Transfer*. Trata-se de um estilo arquitetural utilizado para desenvolver serviços que disponibilizam recursos por meio de uma interface de comunicação.

As APIs REST normalmente utilizam HTTP para permitir que clientes consultem, criem, atualizem e excluam recursos no servidor.

Um recurso representa algo que o sistema gerencia, como um usuário, um trem, uma rota ou um sensor. Cada recurso pode ser identificado por um endereço, chamado URI ou URL, dependendo do contexto.

Algumas características comuns de APIs REST são:

- Utilização dos métodos HTTP para indicar as operações desejadas.
- Identificação dos recursos por meio de URLs.
- Troca de informações em formatos como JSON.
- Separação entre o cliente e o servidor.
- Comunicação sem depender de um estado de sessão armazenado no servidor para interpretar cada requisição, característica conhecida como *stateless*.

Em uma API REST, cada requisição deve fornecer as informações necessárias para ser compreendida e processada. Isso não significa que o sistema não possa ter dados persistidos em banco de dados; significa que o processamento de uma requisição não deve depender de um contexto de comunicação mantido entre requisições anteriores.

É importante destacar que nem toda API web é REST. Existem outras formas de desenvolver interfaces de comunicação, e uma API não precisa utilizar JSON obrigatoriamente.

## 5. Métodos HTTP e suas funções

Os métodos HTTP indicam qual operação o cliente deseja realizar sobre determinado recurso. Eles são especialmente importantes na implementação de operações CRUD (*Create, Read, Update, Delete*), que correspondem a criar, consultar, atualizar e excluir registros.

| Método | Finalidade | Exemplo no MOVIX |
|---|---|---|
| GET | Consultar informações | Listar os trens cadastrados |
| POST | Enviar dados, frequentemente para criar um recurso | Cadastrar um novo trem |
| PUT | Substituir a representação de um recurso | Atualizar todos os dados de uma rota |
| PATCH | Alterar parcialmente um recurso | Modificar somente o status de um trem |
| DELETE | Solicitar a exclusão de um recurso | Excluir um sensor cadastrado |

A diferença entre PUT e PATCH está na intenção da atualização. O PUT é normalmente utilizado para substituir a representação completa do recurso, enquanto o PATCH permite aplicar alterações parciais.

O comportamento exato depende do contrato definido pela API. Por isso, a documentação de cada endpoint deve explicar quais dados são obrigatórios e como a operação funciona.

### 5.1 Exemplos práticos de requisições

Os exemplos a seguir representam uma API hipotética do MOVIX. Os endereços são ilustrativos e não significam que essas rotas já estejam implementadas no projeto.

**GET — Consultar trens**

```http
GET /api/trens HTTP/1.1
Host: exemplo.com
Accept: application/json
```

Essa requisição solicita a lista de trens disponíveis.

**POST — Cadastrar um trem**

```http
POST /api/trens HTTP/1.1
Host: exemplo.com
Content-Type: application/json

{
  "codigo": "TR001",
  "nome": "Trem de Carga 01",
  "linha": "Linha Norte",
  "status": "Operando"
}
```

O cliente envia os dados do trem no corpo da requisição. Se os dados forem válidos e o usuário tiver autorização, a API poderá registrar o novo recurso.

**PUT — Substituir os dados de uma rota**

```http
PUT /api/rotas/3 HTTP/1.1
Host: exemplo.com
Content-Type: application/json

{
  "nome_rota": "Rota Norte Atualizada",
  "origem": "Joinville",
  "destino": "Curitiba",
  "distancia": 130
}
```

Nesse exemplo, o cliente solicita a substituição da representação da rota de identificador 3. Os campos obrigatórios e a forma de tratamento de campos ausentes devem ser definidos pela API.

**PATCH — Alterar somente o status de um trem**

```http
PATCH /api/trens/1 HTTP/1.1
Host: exemplo.com
Content-Type: application/json

{
  "status": "Em manutenção"
}
```

A intenção é modificar apenas o status do trem, mantendo os demais dados.

**DELETE — Excluir um sensor**

```http
DELETE /api/sensores/5 HTTP/1.1
Host: exemplo.com
```

A requisição solicita a exclusão do sensor de identificador 5. Antes de executar a operação, o servidor deve verificar a existência do registro e as permissões do solicitante.

## 6. O que são requisições e respostas HTTP?

Uma requisição HTTP é uma mensagem enviada pelo cliente ao servidor. Ela contém informações que identificam o recurso e a operação desejada, além de possíveis cabeçalhos e um corpo com dados.

Uma resposta HTTP é a mensagem devolvida pelo servidor após o processamento da requisição. Ela contém um código de status, cabeçalhos e, quando aplicável, um corpo com informações ou detalhes sobre o resultado.

Os principais elementos de uma requisição são:

- **Método:** indica a operação desejada, como GET ou POST.
- **URL:** identifica o recurso ou serviço solicitado.
- **Cabeçalhos:** transportam informações adicionais, como o formato dos dados.
- **Corpo:** contém dados enviados pelo cliente, quando necessário.

Os principais elementos de uma resposta são:

- **Código de status:** informa o resultado geral da operação.
- **Cabeçalhos:** fornecem metadados sobre a resposta.
- **Corpo:** pode conter dados JSON, HTML, texto ou outro conteúdo.

### 6.1 Códigos de status HTTP

Os códigos de status ajudam o cliente a compreender o resultado de uma requisição.

| Código | Significado | Situação de exemplo |
|---|---|---|
| 200 OK | Requisição realizada com sucesso | Lista de trens retornada |
| 201 Created | Recurso criado | Novo trem cadastrado |
| 204 No Content | Operação concluída sem corpo de resposta | Exclusão concluída |
| 400 Bad Request | Requisição inválida | Dados malformados |
| 401 Unauthorized | Autenticação ausente ou inválida | Credenciais não aceitas |
| 403 Forbidden | Acesso não permitido | Usuário comum tenta executar uma operação administrativa |
| 404 Not Found | Recurso não encontrado | Trem inexistente |
| 405 Method Not Allowed | Método não permitido para o endpoint | Método não suportado pela rota |
| 500 Internal Server Error | Erro inesperado no servidor | Falha interna durante o processamento |

O código adequado depende da situação. Por exemplo, uma requisição que tenta acessar um recurso inexistente pode receber 404, enquanto uma tentativa de executar uma ação sem a permissão necessária pode receber 403.

### 6.2 Exemplo de resposta HTTP

Após um cadastro bem-sucedido, uma API poderia retornar:

```http
HTTP/1.1 201 Created
Content-Type: application/json
Location: /api/trens/12
```

Corpo da resposta:

```json
{
  "mensagem": "Trem cadastrado com sucesso",
  "id": 12,
  "codigo": "TR001"
}
```

Nesse exemplo, o código 201 indica que um recurso foi criado. O cabeçalho Location informa o endereço do novo recurso, e o corpo apresenta informações sobre o resultado.

## 7. O que são endpoints?

Um endpoint é um ponto de acesso de uma API, normalmente definido pela combinação de um endereço e um método HTTP. Ele permite que uma aplicação execute determinada operação sobre um recurso.

Por exemplo, em uma API hipotética do MOVIX:

- `GET /api/trens`: consulta os trens.
- `GET /api/trens/12`: consulta o trem de identificador 12.
- `POST /api/trens`: solicita a criação de um trem.
- `PATCH /api/trens/12`: solicita uma alteração parcial no trem 12.
- `DELETE /api/trens/12`: solicita a exclusão do trem 12.

Observe que o endereço pode ser o mesmo para diferentes operações. O método HTTP ajuda a definir a ação pretendida.

Uma API bem organizada utiliza nomes claros, regras consistentes e documentação que informa os parâmetros, os dados obrigatórios, os resultados possíveis e as permissões exigidas em cada endpoint.

## 8. O que é JSON e como funciona a troca de dados?

JSON é a sigla para *JavaScript Object Notation*. É um formato textual utilizado para representar e trocar dados entre aplicações.

Ele é bastante utilizado em APIs por apresentar uma estrutura relativamente simples e ser compatível com diferentes linguagens de programação.

O JSON permite representar objetos, listas, textos, números, valores booleanos e valores nulos.

Exemplo de JSON representando um trem:

```json
{
  "id": 12,
  "codigo": "TR001",
  "nome": "Trem de Carga 01",
  "linha": "Linha Norte",
  "velocidade": 65.5,
  "operando": true,
  "observacao": null
}
```

Nesse exemplo:

- `id` representa o identificador do registro.
- `codigo`, `nome` e `linha` são textos.
- `velocidade` é um número.
- `operando` é um valor booleano.
- `observacao` está definida como nula.

Os nomes das propriedades são escritos entre aspas duplas, e os dados são organizados em pares de chave e valor.

### 8.1 Como o JSON é utilizado na comunicação?

O cliente envia dados JSON no corpo da requisição, normalmente informando o cabeçalho `Content-Type: application/json`. O servidor interpreta os dados, valida as informações e executa a operação solicitada.

Depois do processamento, a API pode devolver outro objeto JSON com os dados solicitados ou com uma mensagem sobre o resultado.

JSON não é um banco de dados e não substitui o MySQL. Ele é um formato de representação de dados usado durante a comunicação. A aplicação pode receber um JSON, validar suas informações e, se for apropriado, salvar os dados em um banco relacional.

## 9. Diferenças entre uma API e um sistema web tradicional

Um sistema web tradicional geralmente entrega páginas HTML que o navegador apresenta ao usuário. Em uma aplicação PHP, por exemplo, o servidor pode processar um formulário, consultar o banco de dados e devolver uma página HTML já montada.

Uma API, por sua vez, normalmente disponibiliza dados ou funcionalidades por meio de endpoints para que outras aplicações os utilizem. Em APIs que retornam JSON, o cliente recebe dados estruturados em vez de uma página HTML completa.

| Característica | Sistema web tradicional | API web |
|---|---|---|
| Objetivo comum | Apresentar páginas e interfaces ao usuário | Disponibilizar dados e operações a clientes |
| Resposta típica | HTML | JSON ou outro formato definido |
| Consumidor | Principalmente navegador | Navegador, aplicativo móvel ou outro sistema |
| Interface visual | Geralmente fornecida pelo próprio sistema | Geralmente construída pelo cliente consumidor |
| Exemplo | Página de cadastro de trens | Endpoint que fornece dados dos trens |

Essa diferença não é absoluta. Um sistema web pode oferecer páginas HTML e APIs ao mesmo tempo. Além disso, uma API não precisa ser pública: ela pode ser restrita a aplicações autorizadas.

No MOVIX, as páginas PHP existentes podem continuar responsáveis pela interface visual, enquanto uma API poderia ser desenvolvida futuramente para permitir o acesso controlado aos dados ferroviários por outros sistemas.

## 10. Como uma aplicação PHP pode consumir uma API?

Uma aplicação PHP pode consumir uma API enviando requisições HTTP para um serviço externo e interpretando as respostas recebidas.

Uma forma de fazer isso é utilizar a extensão cURL do PHP, que permite configurar e executar requisições HTTP e HTTPS.

O exemplo a seguir consulta uma API pública de demonstração que retorna informações em JSON.

### 10.1 Exemplo em PHP utilizando cURL

```php
<?php

$url = "https://jsonplaceholder.typicode.com/posts/1";

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPGET, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);

$resposta = curl_exec($curl);

if ($resposta === false) {
    echo "Erro na comunicação com a API.";
    curl_close($curl);
    exit;
}

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

if ($status !== 200) {
    echo "A API retornou o status HTTP: " . $status;
    exit;
}

$dados = json_decode($resposta, true);

if (!is_array($dados)) {
    echo "Não foi possível interpretar a resposta.";
    exit;
}

echo "Título: " . htmlspecialchars(
    $dados["title"],
    ENT_QUOTES,
    "UTF-8"
);
```

### 10.2 Explicação do código

1. A variável `$url` contém o endereço do endpoint que será consultado.
2. `curl_init()` prepara a requisição.
3. `curl_setopt()` configura o cURL para retornar a resposta como texto, usar GET e limitar o tempo de espera.
4. `curl_exec()` executa a requisição.
5. A verificação de erro identifica falhas de comunicação.
6. `curl_getinfo()` permite consultar o código de status HTTP retornado.
7. `json_decode()` transforma o JSON recebido em um array associativo do PHP.
8. `htmlspecialchars()` ajuda a exibir o título de maneira segura em uma página HTML.

O endereço utilizado pertence a um serviço de demonstração e não possui relação direta com o banco de dados do MOVIX. Para executar o exemplo, o ambiente PHP precisa ter a extensão cURL habilitada e acesso ao serviço externo.

Em uma aplicação real, também é importante validar os campos recebidos, tratar respostas inesperadas, respeitar limites de uso da API e não exibir informações técnicas sensíveis em mensagens de erro.

## 11. Como uma API pode disponibilizar informações para outros sistemas?

Uma aplicação pode disponibilizar dados criando endpoints que recebem requisições, aplicam regras de negócio, consultam ou modificam os registros autorizados e retornam respostas padronizadas.

Por exemplo, uma API ferroviária poderia oferecer os seguintes serviços:

- Consultar os trens cadastrados.
- Consultar os dados de uma rota.
- Informar o status operacional de um trem.
- Receber informações de sensores.
- Consultar alertas de manutenção.
- Permitir o cadastro de novos registros para clientes autorizados.

Para desenvolver essa estrutura, o servidor precisa definir os endpoints, os métodos HTTP aceitos, o formato dos dados, as validações e as regras de acesso.

Em PHP, uma implementação simples pode identificar o método da requisição, executar a operação correspondente e retornar JSON com `json_encode()`.

Exemplo ilustrativo de uma resposta produzida pelo PHP:

```php
<?php

header("Content-Type: application/json; charset=utf-8");

$trens = [
    [
        "id" => 1,
        "codigo" => "TR001",
        "status" => "Operando"
    ],
    [
        "id" => 2,
        "codigo" => "TR002",
        "status" => "Em manutenção"
    ]
];

echo json_encode(
    $trens,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);
```

Esse trecho demonstra a geração de uma resposta JSON. Sozinho, ele não constitui uma API completa: ainda seriam necessários o tratamento das requisições, a integração com o banco de dados quando aplicável, a validação dos parâmetros e o controle de acesso.

O uso de APIs permite que aplicações diferentes consumam as informações disponibilizadas pelo servidor sem precisarem acessar diretamente o banco de dados umas das outras.

## 12. Autenticação, autorização e segurança no consumo de APIs

A segurança é essencial porque uma API pode disponibilizar dados pessoais, informações operacionais ou funcionalidades que alteram registros importantes.

É necessário diferenciar dois conceitos:

**Autenticação:** verifica a identidade do usuário ou sistema que está realizando a requisição.

**Autorização:** determina quais recursos e operações essa identidade pode acessar.

Por exemplo, no MOVIX, um usuário pode conseguir realizar o login corretamente, mas não possuir permissão para excluir funcionários. Nesse caso, a autenticação foi bem-sucedida, porém a operação não deve ser autorizada.

Entre as principais medidas de segurança estão:

- Utilizar HTTPS para proteger os dados durante a transmissão.
- Verificar as permissões no backend em cada endpoint protegido.
- Validar os dados recebidos antes de processá-los.
- Utilizar consultas preparadas ao acessar o banco de dados.
- Não armazenar senhas em texto puro.
- Utilizar mecanismos apropriados de autenticação, como sessões protegidas ou tokens, conforme a arquitetura.
- Não colocar senhas, chaves de API ou tokens sensíveis nas URLs.
- Restringir os métodos HTTP e as operações disponíveis.
- Retornar mensagens de erro que não exponham informações internas do servidor.
- Registrar eventos relevantes para auditoria quando necessário.

No PHP, as funções `password_hash()` e `password_verify()` podem ser utilizadas para armazenar e verificar senhas com segurança. Para APIs, a autenticação pode utilizar mecanismos adicionais, como tokens de acesso, dependendo do contexto e dos requisitos do sistema.

Também é importante não confiar apenas no frontend. Ocultar um botão de exclusão não impede que alguém tente enviar uma requisição diretamente ao servidor. A autorização precisa ser verificada no backend antes de executar a operação.

No caso do MOVIX, uma futura API administrativa deve verificar se o usuário possui o perfil adequado antes de permitir o cadastro, a alteração ou a exclusão de informações protegidas.

## 13. Exemplos de utilização de APIs em sistemas empresariais

As APIs são utilizadas em diferentes setores para conectar aplicações e automatizar processos.

### 13.1 Comércio eletrônico

Uma loja virtual pode utilizar APIs para consultar preços, calcular fretes, processar pagamentos e atualizar o status dos pedidos. Cada serviço disponibiliza operações específicas para que a loja possa integrar suas funcionalidades.

### 13.2 Sistemas financeiros

Aplicações empresariais podem utilizar APIs autorizadas para consultar transações, integrar pagamentos e compartilhar informações financeiras entre serviços. O acesso deve respeitar as permissões e os requisitos de segurança aplicáveis.

### 13.3 Logística e transporte

Sistemas de logística podem integrar APIs de mapas, rastreamento e gestão de entregas para acompanhar trajetos, atualizar localizações e compartilhar informações operacionais.

### 13.4 Gestão empresarial

Um sistema de recursos humanos pode disponibilizar informações autorizadas sobre funcionários para outro sistema empresarial. Essa integração evita a necessidade de cadastrar os mesmos dados manualmente em várias aplicações.

### 13.5 Monitoramento industrial

Uma aplicação pode receber dados de sensores por meio de uma API, registrar medições e disponibilizar alertas para um painel de monitoramento. Esse tipo de integração pode contribuir para a identificação de falhas e para o acompanhamento das operações.

### 13.6 Aplicação no projeto MOVIX

No contexto do MOVIX, uma API poderia conectar o sistema ferroviário a outras aplicações de monitoramento e gestão.

Algumas possibilidades seriam:

- Disponibilizar o status operacional dos trens para um painel externo.
- Permitir a consulta de rotas e destinos por um sistema de logística.
- Receber medições de sensores ferroviários.
- Compartilhar alertas de manutenção com uma plataforma de acompanhamento.
- Permitir que um aplicativo autorizado consulte relatórios operacionais.

Esses exemplos representam possibilidades de evolução do projeto. A existência dessas funcionalidades dependeria de sua implementação, da definição dos endpoints e das regras de integração.

## 14. Importância das APIs para a evolução dos sistemas

As APIs contribuem para a evolução dos sistemas porque permitem separar responsabilidades e compartilhar funcionalidades de forma padronizada.

Quando uma aplicação disponibiliza uma API bem definida, outros sistemas podem utilizar seus serviços sem precisar conhecer todos os detalhes de sua implementação interna.

Entre os benefícios estão:

- **Integração:** facilita a comunicação entre aplicações diferentes.
- **Reutilização:** permite utilizar serviços existentes em novos projetos.
- **Escalabilidade:** possibilita desenvolver e ampliar partes do sistema de acordo com a necessidade.
- **Automação:** reduz tarefas manuais e a repetição de operações.
- **Manutenção:** uma interface bem documentada facilita a compreensão das operações disponíveis.
- **Flexibilidade:** permite que diferentes clientes consumam os mesmos dados e serviços.

Entretanto, a utilização de APIs também exige planejamento. É necessário definir contratos de comunicação, documentar os endpoints, validar os dados e proteger as operações. Além disso, a disponibilidade de um serviço externo pode afetar a aplicação que depende dele.

No MOVIX, a adoção futura de APIs poderia ampliar a integração do sistema ferroviário com outros serviços, desde que os requisitos técnicos e de segurança fossem atendidos.

## 15. Conclusão

As APIs são recursos importantes no desenvolvimento de sistemas modernos, pois permitem que aplicações troquem informações e compartilhem funcionalidades de maneira organizada. Por meio de requisições HTTP, endpoints e formatos como JSON, é possível criar interfaces que conectam sistemas desenvolvidos com diferentes tecnologias.

Durante a pesquisa, foi possível compreender a função dos métodos GET, POST, PUT, PATCH e DELETE, as diferenças entre requisições e respostas, os códigos de status HTTP e a utilização do PHP para consumir e produzir dados em JSON.

Também foi possível observar que a integração entre sistemas deve ser acompanhada de medidas de segurança. A autenticação identifica quem realiza a requisição, enquanto a autorização define quais operações podem ser executadas. Por isso, as verificações precisam ocorrer no backend, e não somente na interface visual.

Para o projeto MOVIX, as APIs representam uma possibilidade de evolução, permitindo que informações sobre trens, rotas, sensores e alertas sejam compartilhadas com outros sistemas autorizados. Assim, o estudo desse tema amplia a compreensão da equipe sobre integração, organização de software e desenvolvimento de aplicações preparadas para futuras necessidades.

## 16. Referências

MOZILLA DEVELOPER NETWORK (MDN). **Métodos de requisição HTTP**. Disponível em: https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Reference/Methods. Acesso em: 20 out. 2026.

MOZILLA DEVELOPER NETWORK (MDN). **Visão geral de cliente e servidor**. Disponível em: https://developer.mozilla.org/en-US/docs/Learn_web_development/Extensions/Server-side/First_steps/Client-Server_overview. Acesso em: 20 out. 2026.

PHP. **Exemplos básicos do cURL**. Manual do PHP. Disponível em: https://www.php.net/manual/pt_BR/curl.examples-basic.php. Acesso em: 20 out. 2026.

PHP. **json_encode — Retorna a representação JSON de um valor**. Manual do PHP. Disponível em: https://www.php.net/manual/en/function.json-encode.php. Acesso em: 20 out. 2026.

PHP. **password_hash — Cria um hash de senha**. Manual do PHP. Disponível em: https://www.php.net/manual/pt_BR/function.password-hash.php. Acesso em: 20 out. 2026.

OWASP FOUNDATION. **REST Security Cheat Sheet**. Disponível em: https://cheatsheetseries.owasp.org/cheatsheets/REST_Security_Cheat_Sheet.html. Acesso em: 20 out. 2026.

OWASP FOUNDATION. **Authorization Cheat Sheet**. Disponível em: https://cheatsheetseries.owasp.org/cheatsheets/Authorization_Cheat_Sheet.html. Acesso em: 20 out. 2026.
