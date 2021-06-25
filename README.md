## ImageOptimizer - (LDSG) Local Development Setup Guide

-   Clone project:

          CD into project directory

-   Install composer:

          composer install

-   Create .env and Configure Database:

          DB_CONNECTION=mysql
          DB_HOST=127.0.0.1
          DB_PORT=3306
          DB_DATABASE=YOUR DATABASE NAME
          DB_USERNAME=YOUR USERNAME
          DB_PASSWORD=YOUR PASSWORD

-   Link storage folder to public folder to make images accessible publicly:

          php artisan storage:link

-   Run migration:

          php artisan migrate

-   Run the server:

          php artisan serve

-   Endpoints of RestAPI:

          Get all images(Method GET): http://12.0.0.1:8000/api/images
          Add a new image(Method POST): http://12.0.0.1:8000/api/images
          Update a give image name(Method PUT): http://12.0.0.1:8000/api/images
          Get single image(Method GET): http://12.0.0.1:8000/api/images/{id}
          Generate image link(Method GET): http://127.0.0.1:8000/api/generate/{imageId}
          Re Generate image link(Method GET): http://127.0.0.1:8000/api/regenerate/{imageId}
          Share image link(Method GET): http://127.0.0.1:8000/api/share/{linkId}
          Statistics(Method GET): http://127.0.0.1:8000/api/statistics
          Search image name(Method POST): http://127.0.0.1:8000/api/search
