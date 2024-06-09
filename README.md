<p align="center">
<img src="https://bravi.com.br/app/themes/bravi/assets/img/logo-bravi.png" alt="Logo Bravi" width="200" />
</p>

# API Bravi
Esta é uma API desenvolvida em Laravel que oferece diversas funcionalidades referentes a uma agenda de contatos.

### Funcionalidades disponíveis
- Cadastro, edição, atualização e remoção de pessoas
- Cadastro, edição, atualização e remoção de contatos
- Validação da ordem dos parênteses em uma string de entrada

### Regras de negócio aplicadas
- Ao cadastrar uma nova pessoa na agenda, um contato deve ser informado.
- Não é possível manter pessoas na agenda sem ao menos um contato cadastrado.
- Ao remover o último contato da pessoa, essa pessoa também é removida do sistema.
- A quantidade de contatos por pessoa é ilimitada.

### Rodando o projeto localmente
1. Clone este repositório usando o comando `git clone` no seu terminal.
2. Depois de clonar o projeto, entre na pasta e execute o comando `docker-compose up -d` para subir os containers.
3. Em seguida, acesse o container do php através do comando `docker exec -it php /bin/bash` e execute `composer install` no terminal.
4. Certifique-se de não ter nenhum outro serviço rodando na porta 80 da sua máquina local.
5. Copie o arquivo `.env-example` da pasta raíz do projeto e renomeie para `.env`.
6. Gere a chave da aplicação Laravel com o comando `php artisan key:generate`.
7. O banco de dados MySQL já está configurado! É necessário apenas rodar as migrations com o comando `php artisan migrate`.
8. Existe um arquivo na raíz do projeto com o nome de `endpoints.json` que contém todas as rotas da aplicação. Você pode importá-lo no Insomnia para realizar requisições aos endpoints da aplicação.
9. Estamos prontos! A API vai estar disponível no endereço [http://localhost/api](http://localhost/api).

### Suportes balanceados
Para validar a ordem dos parênteses em uma string, execute o comando `php artisan validate:brackets "sua-string-de-parênteses"` no terminal. Isso verificará se a ordem dos parênteses na string fornecida é válida.
