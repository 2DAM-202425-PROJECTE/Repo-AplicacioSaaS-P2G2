# Guia d'Inici per a l'Aplicació

## Instal·lació

1.  Clonar el repositori:

    `
    git clone git@github.com:2DAM-202425-PROJECTE/Repo-AplicacioSaaS-P2G2.git
    cd Repo-AplicacioSaaS-P2G2/g2app
    `

3.  Instal·lar dependències:

    `
    composer install`
    ` npm install
    `

5.  Crear fitxer \`.env\`:

    `
    cp .env.example .env
    `

6.  Generar clau d'aplicació:

    `
    php artisan key:generate
    `

## Migracions

Executar migracions i seeders:

`
php artisan migrate:fresh --seed
`

## Iniciar l'Aplicació

Compilar i iniciar:

`npm run dev`
`php artisan serve`

O, alternativament:

`
npm run start
`

## Credencials

*   Usuaris per defecte (creat amb el seed): `empresa@email.com` i `usuari@email.com`
*   Contrasenya per a tots els usuaris: `password`
*   Alternativament, consultar els correus a la base de dades.
