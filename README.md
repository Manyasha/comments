# Comments List

## How to install

from a project baseroot run

 ```
 composer install
 npm install
 ``` 
change mysql settings in **.env** file by your configuration 

 ```
 php artisan migrate
 ```      

you can generate some test data automatically, for this run 
 ```
 php artisan tinker
 factory('App\Comment', 5)->create();
 ```
 
## How to build
```
 npm run dev
 php artisan serve
 ```        
open **http://127.0.0.1:8000** at the any browser you like    