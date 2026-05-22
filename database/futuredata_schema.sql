-- =============================================================================
--  FutureData – Modelagem Completa do Banco de Dados
--  Banco:    MySQL 8.4
--  Gerado:   2026-05-17
--  Charset:  utf8mb4 / utf8mb4_unicode_ci
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE DATABASE IF NOT EXISTS `futuredata`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `futuredata`;

-- =============================================================================
--  BLOCO 1 – AUTENTICAÇÃO E SESSÃO
-- =============================================================================

-- -----------------------------------------------------------------------------
--  users
--  Usuários internos do sistema (gerentes, técnicos).
-- -----------------------------------------------------------------------------
CREATE TABLE `users` (
    `id`                BIGINT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `name`              VARCHAR(255)        NOT NULL,
    `email`             VARCHAR(255)        NOT NULL,
    `role`              ENUM('gerente','tecnico') NOT NULL DEFAULT 'tecnico',
    `email_verified_at` TIMESTAMP           NULL DEFAULT NULL,
    `password`          VARCHAR(255)        NOT NULL,
    `remember_token`    VARCHAR(100)        NULL DEFAULT NULL,
    `created_at`        TIMESTAMP           NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP           NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuários internos – gerentes e técnicos';

-- -----------------------------------------------------------------------------
--  password_reset_tokens
--  Tokens temporários para recuperação de senha.
-- -----------------------------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NULL DEFAULT NULL,

    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tokens de redefinição de senha';

-- -----------------------------------------------------------------------------
--  sessions
--  Sessões de usuário armazenadas em banco (driver database).
-- -----------------------------------------------------------------------------
CREATE TABLE `sessions` (
    `id`            VARCHAR(255)    NOT NULL,
    `user_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address`    VARCHAR(45)     NULL DEFAULT NULL,
    `user_agent`    TEXT            NULL DEFAULT NULL,
    `payload`       LONGTEXT        NOT NULL,
    `last_activity` INT             NOT NULL,

    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index`       (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Sessões de autenticação';

-- =============================================================================
--  BLOCO 2 – CONTROLE DE ACESSO (RBAC)
-- =============================================================================

-- -----------------------------------------------------------------------------
--  roles
--  Perfis de acesso (gerente, técnico, atendente…).
-- -----------------------------------------------------------------------------
CREATE TABLE `roles` (
    `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255)     NOT NULL,
    `slug`        VARCHAR(255)     NOT NULL,
    `description` VARCHAR(255)     NULL DEFAULT NULL,
    `color`       VARCHAR(30)      NOT NULL DEFAULT 'slate',
    `level`       TINYINT UNSIGNED NOT NULL DEFAULT 99
                  COMMENT 'Hierarquia: menor = mais privilegiado',
    `created_at`  TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP        NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Perfis de acesso (RBAC)';

-- -----------------------------------------------------------------------------
--  permissions
--  Permissões granulares agrupadas por módulo.
-- -----------------------------------------------------------------------------
CREATE TABLE `permissions` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255)    NOT NULL,
    `slug`        VARCHAR(255)    NOT NULL,
    `description` VARCHAR(255)    NULL DEFAULT NULL,
    `group`       VARCHAR(60)     NOT NULL DEFAULT 'geral'
                  COMMENT 'Módulo ao qual a permissão pertence',
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `permissions_slug_unique` (`slug`),
    KEY `permissions_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Permissões granulares por módulo';

-- -----------------------------------------------------------------------------
--  role_permission  [pivot]
--  Quais permissões cada role possui.
-- -----------------------------------------------------------------------------
CREATE TABLE `role_permission` (
    `role_id`       BIGINT UNSIGNED NOT NULL,
    `permission_id` BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (`role_id`, `permission_id`),
    CONSTRAINT `fk_rp_role`
        FOREIGN KEY (`role_id`)       REFERENCES `roles`       (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_rp_permission`
        FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pivot: roles × permissões';

-- -----------------------------------------------------------------------------
--  user_role  [pivot]
--  Roles atribuídas a cada usuário.
-- -----------------------------------------------------------------------------
CREATE TABLE `user_role` (
    `user_id`     BIGINT UNSIGNED NOT NULL,
    `role_id`     BIGINT UNSIGNED NOT NULL,
    `assigned_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`user_id`, `role_id`),
    CONSTRAINT `fk_ur_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ur_role`
        FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pivot: usuários × roles';

-- =============================================================================
--  BLOCO 3 – CLIENTES E EQUIPAMENTOS
-- =============================================================================

-- -----------------------------------------------------------------------------
--  clientes
--  Pessoas físicas ou jurídicas que trazem equipamentos para reparo.
-- -----------------------------------------------------------------------------
CREATE TABLE `clientes` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome`        VARCHAR(255)    NOT NULL,
    `email`       VARCHAR(255)    NULL DEFAULT NULL,
    `telefone`    VARCHAR(20)     NULL DEFAULT NULL,
    `cpf_cnpj`    VARCHAR(20)     NULL DEFAULT NULL,
    `endereco`    VARCHAR(255)    NULL DEFAULT NULL,
    `cidade`      VARCHAR(100)    NULL DEFAULT NULL,
    `estado`      VARCHAR(2)      NULL DEFAULT NULL,
    `cep`         VARCHAR(10)     NULL DEFAULT NULL,
    `observacoes` TEXT            NULL DEFAULT NULL,
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`  TIMESTAMP       NULL DEFAULT NULL
                  COMMENT 'Soft delete',

    PRIMARY KEY (`id`),
    UNIQUE KEY `clientes_cpf_cnpj_unique` (`cpf_cnpj`),
    KEY `clientes_nome_index`  (`nome`),
    KEY `clientes_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Clientes (PF ou PJ)';

-- -----------------------------------------------------------------------------
--  equipamentos
--  Aparelhos vinculados a um cliente e associados a ordens de serviço.
-- -----------------------------------------------------------------------------
CREATE TABLE `equipamentos` (
    `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `cliente_id`       BIGINT UNSIGNED NOT NULL,
    `tipo`             VARCHAR(80)     NOT NULL   COMMENT 'Ex.: Notebook, Smartphone',
    `marca`            VARCHAR(80)     NULL DEFAULT NULL,
    `modelo`           VARCHAR(120)    NULL DEFAULT NULL,
    `numero_serie`     VARCHAR(100)    NULL DEFAULT NULL,
    `patrimonio`       VARCHAR(60)     NULL DEFAULT NULL,
    `acessorios`       VARCHAR(255)    NULL DEFAULT NULL,
    `condicao_entrada` VARCHAR(255)    NULL DEFAULT NULL,
    `em_garantia`      TINYINT(1)      NOT NULL DEFAULT 0,
    `observacoes`      TEXT            NULL DEFAULT NULL,
    `created_at`       TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`       TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`       TIMESTAMP       NULL DEFAULT NULL
                       COMMENT 'Soft delete',

    PRIMARY KEY (`id`),
    CONSTRAINT `fk_eq_cliente`
        FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Equipamentos dos clientes';

-- =============================================================================
--  BLOCO 4 – ORDENS DE SERVIÇO
-- =============================================================================

-- -----------------------------------------------------------------------------
--  ordens
--  Núcleo operacional do sistema: registra cada atendimento técnico.
--
--  Campos de acesso público:
--    codigo_publico  – código legível exibido ao cliente (ex.: OS00001)
--    token           – chave aleatória de 7 chars para URL do portal do cliente
-- -----------------------------------------------------------------------------
CREATE TABLE `ordens` (
    `id`               BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `numero`           VARCHAR(20)      NOT NULL   COMMENT 'Código interno sequencial (ex.: OS202600001)',
    `codigo_publico`   VARCHAR(20)      NULL DEFAULT NULL
                       COMMENT 'Código amigável exibido ao cliente (ex.: OS00001)',
    `token`            VARCHAR(10)      NULL DEFAULT NULL
                       COMMENT 'Token aleatório para acesso ao portal do cliente',
    `cliente_id`       BIGINT UNSIGNED  NOT NULL,
    `equipamento_id`   BIGINT UNSIGNED  NULL DEFAULT NULL,
    `tecnico_id`       BIGINT UNSIGNED  NULL DEFAULT NULL,
    `status`           ENUM(
                           'entrada',
                           'analise',
                           'execucao',
                           'aguardando_cliente',
                           'em_teste',
                           'finalizado',
                           'cancelado'
                       ) NOT NULL DEFAULT 'entrada',
    `status_orcamento` ENUM('pendente','aprovado','recusado')
                       NULL DEFAULT NULL
                       COMMENT 'Resposta do cliente ao orçamento via portal',
    `problema_relatado` TEXT            NOT NULL,
    `diagnostico`       TEXT            NULL DEFAULT NULL,
    `solucao`           TEXT            NULL DEFAULT NULL,
    `valor_servico`     DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    `valor_pecas`       DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    `desconto`          DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    `observacoes`       TEXT            NULL DEFAULT NULL,
    `previsao_entrega`  DATE            NULL DEFAULT NULL,
    `finalizado_em`     TIMESTAMP       NULL DEFAULT NULL,
    `created_at`        TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`        TIMESTAMP       NULL DEFAULT NULL
                        COMMENT 'Soft delete',

    PRIMARY KEY (`id`),
    UNIQUE KEY `ordens_numero_unique`        (`numero`),
    UNIQUE KEY `ordens_codigo_publico_unique`(`codigo_publico`),
    UNIQUE KEY `ordens_token_unique`         (`token`),
    KEY `ordens_status_index`      (`status`),
    KEY `ordens_created_at_index`  (`created_at`),

    CONSTRAINT `fk_os_cliente`
        FOREIGN KEY (`cliente_id`)     REFERENCES `clientes`    (`id`),
    CONSTRAINT `fk_os_equipamento`
        FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_os_tecnico`
        FOREIGN KEY (`tecnico_id`)     REFERENCES `users`        (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Ordens de serviço';

-- -----------------------------------------------------------------------------
--  ordem_historicos
--  Linha do tempo imutável de cada mudança de status em uma OS.
-- -----------------------------------------------------------------------------
CREATE TABLE `ordem_historicos` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ordem_id`        BIGINT UNSIGNED NOT NULL,
    `user_id`         BIGINT UNSIGNED NULL DEFAULT NULL
                      COMMENT 'Usuário que realizou a alteração (null = sistema)',
    `status_anterior` VARCHAR(30)     NULL DEFAULT NULL,
    `status_novo`     VARCHAR(30)     NOT NULL,
    `observacao`      TEXT            NULL DEFAULT NULL,
    `created_at`      TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP       NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `oh_ordem_id_index` (`ordem_id`),

    CONSTRAINT `fk_oh_ordem`
        FOREIGN KEY (`ordem_id`) REFERENCES `ordens` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_oh_user`
        FOREIGN KEY (`user_id`)  REFERENCES `users`  (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Histórico de mudanças de status das OS';

-- -----------------------------------------------------------------------------
--  mensagens
--  Troca de mensagens entre técnicos/gerentes e o cliente via portal público.
-- -----------------------------------------------------------------------------
CREATE TABLE `mensagens` (
    `id`         BIGINT UNSIGNED              NOT NULL AUTO_INCREMENT,
    `ordem_id`   BIGINT UNSIGNED              NOT NULL,
    `user_id`    BIGINT UNSIGNED              NULL DEFAULT NULL
                 COMMENT 'Null quando a mensagem é enviada pelo cliente via portal',
    `tipo`       ENUM('tecnico','cliente')    NOT NULL DEFAULT 'tecnico',
    `conteudo`   TEXT                         NOT NULL,
    `lida_em`    TIMESTAMP                    NULL DEFAULT NULL,
    `created_at` TIMESTAMP                    NULL DEFAULT NULL,
    `updated_at` TIMESTAMP                    NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `mensagens_ordem_created_index` (`ordem_id`, `created_at`),

    CONSTRAINT `fk_msg_ordem`
        FOREIGN KEY (`ordem_id`) REFERENCES `ordens` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_msg_user`
        FOREIGN KEY (`user_id`)  REFERENCES `users`  (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Mensagens entre equipe e cliente via portal';

-- =============================================================================
--  BLOCO 5 – NOTIFICAÇÕES
-- =============================================================================

-- -----------------------------------------------------------------------------
--  notifications
--  Notificações in-app geradas por eventos do sistema (padrão Laravel).
--  O campo `data` armazena JSON com: tipo, titulo, mensagem, url, ordem_id.
-- -----------------------------------------------------------------------------
CREATE TABLE `notifications` (
    `id`              CHAR(36)        NOT NULL   COMMENT 'UUID v4',
    `type`            VARCHAR(255)    NOT NULL   COMMENT 'FQCN da classe Notification',
    `notifiable_type` VARCHAR(255)    NOT NULL   COMMENT 'Tipo do model notificável (ex.: App\\Models\\User)',
    `notifiable_id`   BIGINT UNSIGNED NOT NULL,
    `data`            TEXT            NOT NULL   COMMENT 'JSON: {tipo, titulo, mensagem, url, ordem_id, numero}',
    `read_at`         TIMESTAMP       NULL DEFAULT NULL,
    `created_at`      TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP       NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `notifications_notifiable_index` (`notifiable_type`, `notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Notificações in-app (driver: database)';

-- =============================================================================
--  BLOCO 6 – INFRAESTRUTURA LARAVEL
-- =============================================================================

-- -----------------------------------------------------------------------------
--  cache / cache_locks
--  Cache do framework (driver database).
-- -----------------------------------------------------------------------------
CREATE TABLE `cache` (
    `key`        VARCHAR(255) NOT NULL,
    `value`      MEDIUMTEXT   NOT NULL,
    `expiration` BIGINT       NOT NULL,

    PRIMARY KEY (`key`),
    KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Cache do framework';

CREATE TABLE `cache_locks` (
    `key`        VARCHAR(255) NOT NULL,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` BIGINT       NOT NULL,

    PRIMARY KEY (`key`),
    KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Locks de cache distribuído';

-- -----------------------------------------------------------------------------
--  jobs / job_batches / failed_jobs
--  Filas de trabalho assíncronas (driver database).
-- -----------------------------------------------------------------------------
CREATE TABLE `jobs` (
    `id`           BIGINT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `queue`        VARCHAR(255)         NOT NULL,
    `payload`      LONGTEXT             NOT NULL,
    `attempts`     SMALLINT UNSIGNED    NOT NULL,
    `reserved_at`  INT UNSIGNED         NULL DEFAULT NULL,
    `available_at` INT UNSIGNED         NOT NULL,
    `created_at`   INT UNSIGNED         NOT NULL,

    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Fila de jobs assíncronos';

CREATE TABLE `job_batches` (
    `id`              VARCHAR(255) NOT NULL,
    `name`            VARCHAR(255) NOT NULL,
    `total_jobs`      INT          NOT NULL,
    `pending_jobs`    INT          NOT NULL,
    `failed_jobs`     INT          NOT NULL,
    `failed_job_ids`  LONGTEXT     NOT NULL,
    `options`         MEDIUMTEXT   NULL DEFAULT NULL,
    `cancelled_at`    INT          NULL DEFAULT NULL,
    `created_at`      INT          NOT NULL,
    `finished_at`     INT          NULL DEFAULT NULL,

    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Lotes de jobs';

CREATE TABLE `failed_jobs` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid`        VARCHAR(255)    NOT NULL,
    `connection`  TEXT            NOT NULL,
    `queue`       TEXT            NOT NULL,
    `payload`     LONGTEXT        NOT NULL,
    `exception`   LONGTEXT        NOT NULL,
    `failed_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Jobs que falharam';

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
--  RESUMO DO DIAGRAMA DE RELACIONAMENTOS
-- =============================================================================
--
--  users ──────────────────────────────────────────────────────────────────────
--    │  role (coluna)       : gerente | tecnico
--    ├─< user_role          : N:N com roles (RBAC)
--    ├─< ordens.tecnico_id  : 1:N  (técnico responsável)
--    ├─< ordem_historicos   : 1:N  (quem alterou o status)
--    ├─< mensagens          : 1:N  (mensagens internas)
--    └─< notifications      : 1:N  (polimórfico via notifiable)
--
--  roles ───────────────────────────────────────────────────────────────────────
--    ├─< role_permission    : N:N com permissions
--    └─< user_role          : N:N com users
--
--  clientes ────────────────────────────────────────────────────────────────────
--    ├─< equipamentos       : 1:N  (equipamentos do cliente)
--    └─< ordens             : 1:N  (ordens abertas para o cliente)
--
--  equipamentos ────────────────────────────────────────────────────────────────
--    └─< ordens             : 1:N  (equipamento atendido na OS)
--
--  ordens ──────────────────────────────────────────────────────────────────────
--    ├── cliente_id         → clientes
--    ├── equipamento_id     → equipamentos  (SET NULL ao deletar)
--    ├── tecnico_id         → users         (SET NULL ao deletar)
--    ├─< ordem_historicos   : 1:N  (linha do tempo de status)
--    └─< mensagens          : 1:N  (comunicação com o cliente)
--
--  Campos de acesso público na OS:
--    codigo_publico         : código amigável (ex.: OS00042)
--    token                  : chave de 7 chars para URL /r/{token}
--    status_orcamento       : pendente | aprovado | recusado
--
-- =============================================================================
