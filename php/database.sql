-- ============================================================
-- Client Work & Tax Tracker - Database Schema
-- mybook1.in | Leo Computers
-- ============================================================

CREATE DATABASE IF NOT EXISTS `client_work_tracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `client_work_tracker`;

-- ----------------------------
-- Table: clients
-- ----------------------------
CREATE TABLE IF NOT EXISTS `clients` (
  `id`           VARCHAR(50)  NOT NULL,
  `name`         VARCHAR(255) NOT NULL,
  `company`      VARCHAR(255) DEFAULT '',
  `pan`          VARCHAR(10)  DEFAULT '',
  `it_password`  VARCHAR(255) DEFAULT '',
  `gstin`        VARCHAR(15)  DEFAULT '',
  `phone`        VARCHAR(15)  DEFAULT '',
  `email`        VARCHAR(255) DEFAULT '',
  `city`         VARCHAR(100) DEFAULT 'India',
  `category`     VARCHAR(50)  DEFAULT 'Individual',
  `notes`        TEXT,
  `created_at`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table: work_tasks
-- ----------------------------
CREATE TABLE IF NOT EXISTS `work_tasks` (
  `id`                  VARCHAR(50)    NOT NULL,
  `client_id`           VARCHAR(50)    DEFAULT NULL,
  `client_name`         VARCHAR(255)   DEFAULT '',
  `service_type`        VARCHAR(50)    DEFAULT 'ITR',
  `itr_form`            VARCHAR(30)    DEFAULT 'ITR-4',
  `title`               VARCHAR(255)   NOT NULL,
  `period`              VARCHAR(50)    DEFAULT '',
  `turnover`            VARCHAR(100)   DEFAULT '',
  `due_date`            DATE           DEFAULT NULL,
  `filing_date`         DATE           DEFAULT NULL,
  `arn`                 VARCHAR(100)   DEFAULT '',
  `verification_status` VARCHAR(100)   DEFAULT '',
  `status`              VARCHAR(50)    DEFAULT 'Pending Docs',
  `priority`            VARCHAR(20)    DEFAULT 'Medium',
  `assigned_to`         VARCHAR(100)   DEFAULT 'Self',
  `fee_amount`          DECIMAL(10,2)  DEFAULT 0.00,
  `payment_status`      VARCHAR(30)    DEFAULT 'Pending',
  `description`         TEXT,
  `created_at`          TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_status` (`status`),
  KEY `idx_service_type` (`service_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table: task_logs
-- ----------------------------
CREATE TABLE IF NOT EXISTS `task_logs` (
  `id`         INT AUTO_INCREMENT NOT NULL,
  `task_id`    VARCHAR(50)  NOT NULL,
  `log_date`   DATE         NOT NULL,
  `note`       TEXT         NOT NULL,
  `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
