Project installation
1) Project basic setup
Using either <kbd>php artisan serve<kbd> or Apache/Nginx
2) Rename .env.example to .env and setup the database accordingly
3) If you are using Apache or similar programs, you might want to uncomment the <kbd>MIX_url</kbd> line at <kbd>config/app.php</kbd> and rewrite the route accordingly
4) When the database is ready to use, use the <kbd>php artisan migrate</kbd> to run migrations
5) Configure the seeder and input the data for user at <kbd>database/seeders/DatabaseSeeder.php</kbd> and run the seeder with <kbd>php artisan db:seed</kbd>
6) To start scraping data run the <kbd>php artisan scrape</kbd>, this will start a process that would take some time.
7) To update the data run the <kbd>php artisan updatelist {page}</kbd>to update the points on the listings displayed on that page.
I've implemented this approach to updating data, where the Crawler scrapes the page of the exact post listings, instead of the front-page, due to number of reasons.
One of which being is ever-changing pace of the website, that constantly receives new posts, and week-old post listings could receive no update.
So instead of mass updating the existing listings with data from Front-Page, it updates one by one.
8) The core website is accessed fully after login.

Known issues:
1) Sometimes the target website either limits or entirely blocks requests due to excessive amount of requests from your IP adress, which is fixed by using VPN

What I would have done if I had more time and resources for this task:
1) More error handling for more cases.
2) I would've written more elaborate and syntax-correct comments for the code.
3) I would've implemented Register/Email confirmation functionality, if that would be a part of the task.
But I've only implemented just the Login to satisfy the requirements.
4) More complex css styling