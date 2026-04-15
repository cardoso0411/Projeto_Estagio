# Projeto_Estagio
Projeto academico de portal de estagios da Fatec Itapira.

## Estrutura atual
- `index.php`: entrada principal do projeto
- `render/`: paginas do portal em PHP
- `actions/`: processamento de login, cadastro, logout e candidatura
- `config/`: conexao com banco e sessao
- `database/estagio.sql`: estrutura inicial do banco `estagio`

## Banco de dados
1. Crie ou use o banco `estagio`
2. Importe o arquivo `database/estagio.sql`
3. Ajuste usuario e senha em `config/database.php` se necessario

## Fluxos prontos
- cadastro de usuario
- login com sessao
- listagem de vagas vinda do banco
- detalhe da vaga
- candidatura simples
- area do aluno com candidaturas
