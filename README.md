Este projeto é composto por:

Front-end: Vue.js e Axios
Back-end: Laravel 12.20.0, PostgreSQL e Docker

-- OBS: Certifique-se de ter o Docker instalado. -- 

Siga os passos a seguir:

1º Vá até a pasta back. Lá haverá um arquivo chamado .env.example. Dentro dele, altere os seguintes campos:

    DB_CONNECTION=sqlite
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=laravel
    # DB_USERNAME=root
    # DB_PASSWORD=

Para:

    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5433
    DB_DATABASE=desafio
    DB_USERNAME=desafio
    DB_PASSWORD=adm

Faça uma cópia desse arquivo e renomeie para .env.

Isso é necessário para que o container do Docker consiga se conectar ao Laravel.

2º Para subir o container do Docker, abra o terminal e digite:

    cd back
    docker compose up -d

Caso deseje parar:

    docker compose down

3º É necessário instalar o Laravel e suas dependências. No terminal, digite:

    cd back
    composer install

Depois da instalação, com o Docker rodando (passo 2), digite:

    php artisan migrate

Para executar as migrations no banco. Ao finalizar, digite:

    php artisan serve

Para iniciar o servidor do back-end.

4º Para acessar e instalar o front-end, abra um novo terminal e digite:

    cd front
    npm i

Esse comando instalará as dependências do Vue.js e Axios.
Após a instalação, no terminal, digite:

    npm run dev

Com isso, o projeto estará rodando nos três pontos principais:

    Banco de Dados PostgreSQL (Docker)

    API REST (Laravel)

    Front-end (Vue.js)