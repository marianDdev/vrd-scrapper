## Veridion Scraper

Rest-API implementation for Veridion's scraping system.

Developed with Laravel 11, PHP 8.2, Mysql 8, Redis and Algolia.

### Approach:

The application is containerized using Docker and docker-compose for facilitating scalable configurations.   
Among the configured services, there are Redis for caching and for queue connection and Worker with 10 replicas that will help me better distribute the load, execute parallel processing and have shorter pending time for jobs to be processed on each queue.
Each replica has allocated 100mb memory so that in total, the processing will not take more tha 1Gb RAM memory.
I am using Mysql for storing data, I created a table called **companies** and a seeder based on the **sample-websites-company-names.csv** file.

I am using Algolia as search engine because and for this I have installed a package called Laravel Scout Extended specifically made by Algolia team for Laravel projects.

I have created an API that takes **sample-websites.csv** file as input, parses the file, and then for each domain I dispatch a separate job. 
Once each job finishes the processing and extracts the data points, I dispatch en event that lets a certain listener know that the processing is finished.
The listener then calls a service that based on the domain name updates the right record from the companies table with the extracted data. 

The main design patterns used by me are dependency injection, factory and observer.
I tried to follow best practices and SOLID principle by keeping all the logic and computations in services which implement interfaces. I have only injected interfaces so that none of the objects will depend on concrete classes.

- Run `git clone https://github.com/marianDdev/vrd-scrapper.git` for cloning the project locally 
- Run `cp .env.example .env` and replace dummy values with your own 
- Run `docker-compose up -d --build` to create and start all the containers
  - Beside app, nginx and mysql services, in the dcoker-compose.yml file are also configured:
    - REDIS service, used for caching and as queue connection
    - Worker service which creates 10 replicas, each replica having a queue for stocking pending jobs  
- Run `docker exec -it vrd_scrapper sh` (or bash) to enter into the app container
- Run `php artisan key:generate`
- Run `php artisan migrate && php artisan db:seed` to create companies table and seed it with the data provided in the **sample-websites-company-names.csv** file 
- Follow the POSTMAN documentation for the next steps:
  - [Click here to view the API documentation](https://documenter.getpostman.com/view/13777591/2sA3BkcD1R)
  - Execute a POST request to the scraping API (`/scrape`) with **sample-websites.csv** file as input
    - This action will start the batch jobs processing. Watch the **job_batches** table and wait about 3 -4 minutes for the 997 domains to be processed.
    - In this step each record from the companies table should be already updated with the extracted data points.
- Register on Algolia and create an index called companies_index.
  - Run `php artisan scout:sync` and choose to update the Algolia dashboard with your local settings, thanks to the local config file **config/scout-companies-index.php**. 
  - Run `php artisan scout:import` - this step will import in Algolia all the records from the mysql database. 
- For testing the search feature, execute a GET request to the search API (`/companies`)
  - make the request with a parameter called `keyword`
  - filter your search with the available filter attributes: `name`, `website`, `facebook` or `phone_number`
- Execute a GET request to the statistics API (`/statistics`)
