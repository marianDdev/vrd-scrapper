CREATE DATABASE IF NOT EXISTS vrd_scrapper CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'vrd_user'@'%' IDENTIFIED WITH mysql_native_password BY 'password';
GRANT ALL PRIVILEGES ON vrd_scrapper.* TO 'vrd_user'@'%';
FLUSH PRIVILEGES;
