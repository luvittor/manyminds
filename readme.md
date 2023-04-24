# Projeto “ManyMinds”

Sistema de gerenciamento de usuários, colaboradores, fornecedores, produtos e pedidos.

Desenvolvido com CodeIgniter 3.

Algumas funções do sistema estão disponíveis via API REST.

## Testar o site online

https://vittoretti.com/portfolio/projects/manyminds/

usuário: admin

senha: 123456

## Testar a API REST online

Importe esse [arquivo](https://vittoretti.com/portfolio/download.php?file=manyminds-production-postman) no Postman app para testar a API REST.

### Aviso sobre a API

O PHP não recupera parâmetros de verbos diferentes de GET e POST, então o verbo POST foi usado no lugar dos verbos PUT e DELETE.

Usando o verbo POST é possível usar o “form_validation” do próprio CodeIgniter 3, poupando muitas linhas de código e podendo reutilizar o mesmo sistema de validação de formulário do site.

## Instalar o projeto localmente

- tenha um servidor WAMP ou LAMP instalado, por exemplo o XAMPP;
- baixe este repositorio pelo método que preferir;
- crie um banco de dados no MySQL/MariaDB e importe o arquivo SQL da raiz do repositório;
- publique esse projeto no seu servidor Apache;
- configure os arquivos:
  - config.php
    - base_url - com o endereço remoto do seu projeto
  - datatabase.php
    - com as credenciais do banco de dados
- acesse o endereço remoto e use as credenciais:
  - usuário: admin
  - senha: 123456
- use o arquivo do postman na base do projeto para testar a API REST.

## Referências

Para criar a API REST com autenticação JWT por cima do CodeIgniter 3 estes 2 repositórios foram minhas referências:

- https://github.com/Virtuallified/REST-Api_JWT_CodeIgniter3
- https://github.com/chriskacerguis/codeigniter-restserver
