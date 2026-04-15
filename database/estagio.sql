CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    tipo ENUM('aluno', 'empresa') NOT NULL DEFAULT 'aluno',
    curso VARCHAR(120) DEFAULT NULL,
    cidade VARCHAR(120) DEFAULT NULL,
    ra VARCHAR(40) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vagas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    empresa VARCHAR(120) NOT NULL,
    curso VARCHAR(120) NOT NULL,
    cidade VARCHAR(120) NOT NULL,
    bolsa DECIMAL(10, 2) NOT NULL,
    carga_horaria VARCHAR(60) NOT NULL,
    modalidade VARCHAR(60) NOT NULL,
    descricao TEXT NOT NULL,
    requisitos TEXT NOT NULL,
    beneficios TEXT DEFAULT NULL,
    status ENUM('aberta', 'encerrada') NOT NULL DEFAULT 'aberta',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS candidaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    vaga_id INT NOT NULL,
    status VARCHAR(60) NOT NULL DEFAULT 'Em analise',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_candidaturas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_candidaturas_vaga FOREIGN KEY (vaga_id) REFERENCES vagas(id) ON DELETE CASCADE,
    CONSTRAINT uk_candidatura UNIQUE (usuario_id, vaga_id)
);

INSERT INTO vagas (titulo, empresa, curso, cidade, bolsa, carga_horaria, modalidade, descricao, requisitos, beneficios)
SELECT * FROM (
    SELECT
        'Estagio em Desenvolvimento Web',
        'Tech Itapira',
        'ADS',
        'Itapira - SP',
        950.00,
        '6 horas',
        'Hibrido',
        'Apoio no desenvolvimento de paginas web e manutencao de conteudo institucional.',
        'HTML, CSS, nocao de JavaScript e boa comunicacao.',
        'Vale transporte e possibilidade de efetivacao'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM vagas WHERE titulo = 'Estagio em Desenvolvimento Web');

INSERT INTO vagas (titulo, empresa, curso, cidade, bolsa, carga_horaria, modalidade, descricao, requisitos, beneficios)
SELECT * FROM (
    SELECT
        'Estagio em Suporte Tecnico',
        'Conecta Sistemas',
        'ADS',
        'Itapira - SP',
        900.00,
        '6 horas',
        'Presencial',
        'Atendimento interno, suporte a usuarios e organizacao basica de equipamentos.',
        'Boa comunicacao, organizacao e conhecimento basico de hardware.',
        'Vale transporte'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM vagas WHERE titulo = 'Estagio em Suporte Tecnico');

INSERT INTO vagas (titulo, empresa, curso, cidade, bolsa, carga_horaria, modalidade, descricao, requisitos, beneficios)
SELECT * FROM (
    SELECT
        'Estagio em Processos Administrativos',
        'Solucoes Prime',
        'Gestao Empresarial',
        'Mogi Guacu - SP',
        800.00,
        '5 horas',
        'Presencial',
        'Apoio ao controle de documentos e organizacao de processos administrativos.',
        'Pacote Office, comunicacao e atencao aos detalhes.',
        'Bolsa e ajuda de custo'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM vagas WHERE titulo = 'Estagio em Processos Administrativos');
