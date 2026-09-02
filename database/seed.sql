-- =====================================================================
-- Carlore's - Dados de exemplo (seed)
-- Senha de TODOS os usuarios abaixo: 123456
-- Hash valido gerado com password_hash('123456', PASSWORD_DEFAULT)
-- =====================================================================

USE carlores;

-- ---------------------------------------------------------------------
-- Usuarios (1 admin + 4 clientes)
-- ---------------------------------------------------------------------
INSERT INTO usuarios (id, nome, email, senha_hash, tipo, ativo) VALUES
(1, 'Administrador Carlore''s', 'admin@carlores.com.br', '$2y$10$HXXmPzDBXCG0X8t3URbs9.S1uuRfLyRduHU0n7A1.0NjBHrNIhK6i', 'admin', 1),
(2, 'Fernanda Albuquerque', 'fernanda.albuquerque@email.com', '$2y$10$HXXmPzDBXCG0X8t3URbs9.S1uuRfLyRduHU0n7A1.0NjBHrNIhK6i', 'cliente', 1),
(3, 'Marcos Vinicius Tavares', 'marcos.tavares@email.com', '$2y$10$HXXmPzDBXCG0X8t3URbs9.S1uuRfLyRduHU0n7A1.0NjBHrNIhK6i', 'cliente', 1),
(4, 'Juliana Prado Menezes', 'juliana.menezes@email.com', '$2y$10$HXXmPzDBXCG0X8t3URbs9.S1uuRfLyRduHU0n7A1.0NjBHrNIhK6i', 'cliente', 1),
(5, 'Ricardo Souza Lima', 'ricardo.lima@email.com', '$2y$10$HXXmPzDBXCG0X8t3URbs9.S1uuRfLyRduHU0n7A1.0NjBHrNIhK6i', 'cliente', 1);

-- ---------------------------------------------------------------------
-- Clientes (perfil ligado a usuarios 2..5) - CPFs validos (digito verificador correto)
-- clientes.id resultante: 1=Fernanda 2=Marcos 3=Juliana 4=Ricardo
-- ---------------------------------------------------------------------
INSERT INTO clientes (id, usuario_id, telefone, cpf, endereco) VALUES
(1, 2, '11987654321', '11144477735', 'Rua das Orquideas, 120 - Sao Paulo/SP'),
(2, 3, '11912345678', '22255588846', 'Av. Paulista, 900, apto 45 - Sao Paulo/SP'),
(3, 4, '21998765432', '33366699957', 'Rua do Catete, 55 - Rio de Janeiro/RJ'),
(4, 5, '31988887777', '44488822258', 'Av. Afonso Pena, 300 - Belo Horizonte/MG');

-- ---------------------------------------------------------------------
-- Saloes (5, um inativo para demonstrar o soft delete)
-- ---------------------------------------------------------------------
INSERT INTO saloes (id, nome, capacidade, descricao, foto, valor_base, ativo) VALUES
(1, 'Salao Imperio Dourado', 300, 'Nosso maior espaco, pe-direito duplo e lustres de cristal. Ideal para casamentos grandiosos.', NULL, 8000.00, 1),
(2, 'Salao Vinho & Cristal', 200, 'Ambiente sofisticado em tons de vinho com terraco integrado para cerimonias ao ar livre.', NULL, 6000.00, 1),
(3, 'Salao Realeza', 150, 'Espaco aconchegante com painel em veludo e iluminacao cenica regulavel.', NULL, 4500.00, 1),
(4, 'Salao Bordo Elegance', 80, 'Salao intimista para eventos menores e corporativos, com som e projecao completos.', NULL, 2800.00, 1),
(5, 'Terraco Carlore''s', 120, 'Espaco a ceu aberto com jardim vertical. Em reforma - previsao de reabertura em 2027.', NULL, 3800.00, 0);

-- ---------------------------------------------------------------------
-- Pacotes fechados
-- ---------------------------------------------------------------------
INSERT INTO pacotes (id, nome, descricao, preco, ativo) VALUES
(1, 'Pacote Bronze', 'Pacote de entrada, com o essencial para um evento bonito e funcional.', 1500.00, 1),
(2, 'Pacote Prata', 'Pacote intermediario, com decoracao tematica e equipe de cerimonial.', 3200.00, 1),
(3, 'Pacote Ouro', 'Pacote completo e premium, para eventos inesqueciveis.', 5800.00, 1);

INSERT INTO pacote_itens (pacote_id, descricao_item, ordem) VALUES
(1, 'Decoracao simples de mesas e ambiente', 1),
(1, 'Som ambiente profissional', 2),
(1, 'Iluminacao basica do salao', 3),
(1, 'Equipe de limpeza pos-evento', 4),
(2, 'Decoracao tematica de mesas e ambiente', 1),
(2, 'Som e iluminacao profissional', 2),
(2, 'Mestre de cerimonias', 3),
(2, 'Estacionamento com manobrista', 4),
(2, 'Equipe de limpeza pos-evento', 5),
(3, 'Decoracao premium com flores nobres', 1),
(3, 'Som e iluminacao profissional cenica', 2),
(3, 'Mestre de cerimonias', 3),
(3, 'Welcome drink de boas-vindas', 4),
(3, 'Estacionamento com manobrista', 5),
(3, 'Registro fotografico do evento', 6),
(3, 'Equipe de limpeza completa pos-evento', 7);

-- ---------------------------------------------------------------------
-- Reservas (10, cobrindo os 3 status, datas passadas/hoje/futuras)
-- cliente_id: 1=Fernanda 2=Marcos 3=Juliana 4=Ricardo | salao_id: 1=Imperio 2=Vinho&Cristal 3=Realeza 4=Bordo
-- valor_total e bloqueio_data sao colunas GERADAS - nunca inserir nelas
-- ---------------------------------------------------------------------
INSERT INTO reservas (id, cliente_id, salao_id, pacote_id, data_evento, turno, tipo_evento, numero_convidados, status, valor_salao, valor_pacote, desconto, observacoes) VALUES
(1, 1, 1, 3, '2026-07-18', 'noite', 'Casamento', 280, 'confirmada', 8000.00, 5800.00, 500.00, 'Cliente solicitou mesa de doces extra.'),
(2, 2, 2, 2, '2026-08-05', 'tarde', 'Aniversario de 50 anos', 150, 'confirmada', 6000.00, 3200.00, 0.00, NULL),
(3, 3, 3, 1, '2026-08-20', 'manha', 'Cha de bebe', 60, 'cancelada', 4500.00, 1500.00, 0.00, 'Cancelado a pedido da cliente em 20/07/2026.'),
(4, 4, 3, 2, '2026-08-20', 'tarde', 'Aniversario de 15 anos', 100, 'solicitada', 4500.00, 3200.00, 0.00, 'Aguardando confirmacao e pagamento do sinal.'),
(5, 1, 4, 1, '2026-09-01', 'noite', 'Formatura', 75, 'confirmada', 2800.00, 1500.00, 0.00, NULL),
(6, 2, 1, 3, '2026-09-20', 'noite', 'Casamento', 300, 'confirmada', 8000.00, 5800.00, 800.00, 'Desconto especial - cliente indicado por padrinho.'),
(7, 3, 2, 2, '2026-10-10', 'tarde', 'Aniversario de 15 anos', 180, 'solicitada', 6000.00, 3200.00, 0.00, NULL),
(8, 4, 3, 1, '2026-11-05', 'manha', 'Batizado', 50, 'confirmada', 4500.00, 1500.00, 200.00, NULL),
(9, 1, 2, 3, '2026-12-24', 'noite', 'Festa Corporativa', 190, 'solicitada', 6000.00, 5800.00, 0.00, 'Orcamento para confraternizacao de fim de ano.'),
(10, 2, 4, 1, '2026-06-10', 'tarde', 'Noivado', 70, 'cancelada', 2800.00, 1500.00, 0.00, 'Casal desmarcou o noivado.');

-- ---------------------------------------------------------------------
-- Pagamentos (cobrindo reservas quitadas, parciais e pendentes)
-- ---------------------------------------------------------------------
INSERT INTO pagamentos (reserva_id, valor, data_pagamento, forma_pagamento, tipo, observacoes) VALUES
(1, 5000.00, '2026-05-01', 'pix', 'sinal', NULL),
(1, 8300.00, '2026-07-10', 'transferencia', 'quitacao', 'Quitacao final antes do evento.'),
(2, 3000.00, '2026-06-01', 'cartao_credito', 'sinal', NULL),
(2, 3000.00, '2026-07-01', 'pix', 'parcela', NULL),
(5, 4300.00, '2026-07-15', 'dinheiro', 'quitacao', 'Pago a vista.'),
(6, 4000.00, '2026-08-01', 'pix', 'sinal', NULL),
(8, 2000.00, '2026-08-25', 'boleto', 'sinal', NULL);
