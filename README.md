
# FotoVista


1. Klone das Repository auf deinen lokalen Computer:

    git clone https://github.com/sdarby1/fotovista-laravel


2. Wechsle in das Projektverzeichnis


3. Erstelle eine Kopie der .env.example Datei und benenne sie in .env um:

    cp .env.example .env

Achte darauf, die Ports entprechend deiner Front End Anwendung anzupassen


4. Baue und starte die Docker-Container mit Docker Compose:

    docker-compose up --build


5. Führe die Datenbankmigrationen aus, um die erforderlichen Tabellen in der Datenbank zu erstellen:

    docker-compose exec app php artisan migrate


6. Starte den Container:

    docker-compose exec app php artisan test