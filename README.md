# Guia d'Inici per a l'Aplicació

## Instal·lació
1. Clonar el repositori:  
   `git clone git@github.com:2DAM-202425-PROJECTE/Repo-AplicacioSaaS-P2G2.git`  
   `cd Repo-AplicacioSaaS-P2G2`
   
2. Instal·lar les dependències de Composer i JavaScript:  
   `composer install`
   `npm install`
   
4. Crea un fitxer .env a partir del fitxer d'exemple:  
   `cp .env.example .env`
   
5. Genera una nova clau d'aplicació:  
   `php artisan key:generate`

## Migracions
Executa les migracions i els seeders:  
`php artisan migrate:fresh --seed`

## Iniciar l'Aplicació

Compilar els recursos front-end:  
`npm run dev`  
Iniciar el servidor de desenvolupament:  
`php artisan serve`  

Alternativament, executar les dues comandes juntes:  
`npm run start`  


## Desplegament
Per desplegar l'aplicació en un entorn de producció, assegura't de:
- Configurar correctament el fitxer .env per a producció.
- Executar les migracions i seeders en el servidor de producció.
- Compilar els recursos front-end per a producció.
