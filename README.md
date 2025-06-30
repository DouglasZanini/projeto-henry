Projeto Laravel - Projeto avaliativo da aula de programação do professor Henry

Este é um projeto desenvolvido com o framework Laravel. Abaixo estão as instruções para instalar e executar o projeto localmente.

Requisitos
Antes de começar, verifique se você tem os seguintes softwares instalados:

PHP >= 8.1

Composer

Node.js e NPM

PostgreSQL


Extensões do PHP para Laravel (pdo_pgsql, mbstring, tokenizer, etc.)

Instalação do Composer e Laravel
Instalando o Composer:

Linux/macOS:
Abra o terminal e execute:

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

Windows:
Baixe o instalador em: https://getcomposer.org/Composer-Setup.exe

Verifique se a instalação funcionou:
composer --version


Instalando o Laravel (opcional, para novos projetos):
composer global require laravel/installer

Clonando e Rodando o Projeto
Clone o repositório:
git clone https://github.com/DouglasZanini/projeto-henry.git
cd projeto-henry

Instale as dependências do PHP:
composer install

Instale as dependências do JavaScript:
npm install

Copie o arquivo de exemplo de ambiente:
cp .env.example .env

Gere a chave da aplicação:
php artisan key:generate

Configure o arquivo .env com os dados do seu banco PostgreSQL. Exemplo:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=aluno
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

Rode as migrations (e seeds, se existirem):
php artisan migrate

Rodando o Projeto
Para iniciar o servidor de desenvolvimento do Laravel:
php artisan serve

A aplicação estará disponível em: http://localhost:8000

Compilando os Arquivos JavaScript e CSS
Este projeto utiliza o Vite para compilar os arquivos front-end.

Durante o desenvolvimento (modo "watch"):
npm run dev

Para gerar os arquivos de produção:
npm run prod
