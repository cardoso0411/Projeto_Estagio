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

## Fluxos implementados

### Para Alunos
- Cadastro e login
- Listagem de vagas com filtros
- Detalhes da vaga
- Candidatura às vagas
- Área do aluno com resumo de candidaturas (aprovadas, reprovadas, em análise)

### Para Empresas
- Cadastro e login
- Área da empresa com painel de controle
- Cadastro de novas vagas
- Edição e exclusão de vagas próprias
- Dashboard de candidatos por vaga
- Aprovação/reprovação de candidaturas

## Fluxo de candidatura
1. Aluno se cadastra e faz login
2. Navega pelas vagas e clica "Candidatar-se"
3. Candidatura fica com status "Em analise"
4. Empresa vê candidatos na área da empresa
5. Empresa pode aprovar ou reprovar a candidatura
6. Aluno vê o status atualizado na área do aluno

## Próximas melhorias possíveis
- Upload de documentos (termo, relatório parcial/final)
- Relatórios de vagas por curso/cidade
- Notificações por e-mail
- Perfil de empresa com upload de logo
- Sistema de chat entre aluno e empresa
