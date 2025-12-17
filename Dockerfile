#20 1.905   - Installing psy/psysh (v0.12.9): Extracting archive
#20 1.905   - Installing laravel/tinker (v2.10.1): Extracting archive
#20 1.906   - Installing paragonie/sodium_compat (v2.1.0): Extracting archive
#20 1.906   - Installing pusher/pusher-php-server (7.2.7): Extracting archive
#20 1.952   0/84 [>---------------------------]   0%
#20 2.329  10/84 [===>------------------------]  11%
#20 3.555  20/84 [======>---------------------]  23%
#20 3.659  28/84 [=========>------------------]  33%
#20 3.771  40/84 [=============>--------------]  47%
#20 3.884  50/84 [================>-----------]  59%
#20 4.005  59/84 [===================>--------]  70%
#20 4.159  68/84 [======================>-----]  80%
#20 4.273  76/84 [=========================>--]  90%
#20 4.536  84/84 [============================] 100%
#20 4.609 Generating optimized autoload files
#20 6.596 > Illuminate\Foundation\ComposerScripts::postAutoloadDump
#20 6.613 > @php artisan package:discover --ansi
#20 6.988 
#20 7.000 In Pusher.php line 63:
#20 7.000                                                                                
#20 7.000   Pusher\Pusher::__construct(): Argument #1 ($auth_key) must be of type strin  
#20 7.000   g, null given, called in /var/www/html/vendor/laravel/framework/src/Illumin  
#20 7.000   ate/Broadcasting/BroadcastManager.php on line 350                            
#20 7.000                                                                                
#20 7.000 
#20 7.006 Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
#20 ERROR: process "/bin/sh -c composer install --no-dev --optimize-autoloader" did not complete successfully: exit code: 1
------
 > [stage-0 11/14] RUN composer install --no-dev --optimize-autoloader:
6.613 > @php artisan package:discover --ansi
6.988 
7.000 In Pusher.php line 63:
7.000                                                                                
7.000   Pusher\Pusher::__construct(): Argument #1 ($auth_key) must be of type strin  
7.000   g, null given, called in /var/www/html/vendor/laravel/framework/src/Illumin  
7.000   ate/Broadcasting/BroadcastManager.php on line 350                            
7.000                                                                                
7.000 
7.006 Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
------
Dockerfile:38
--------------------
  36 |     # (We install composer manually here since the official image doesn't have it)
  37 |     COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
  38 | >>> RUN composer install --no-dev --optimize-autoloader
  39 |     
  40 |     # 9. Build Frontend Assets
--------------------
error: failed to solve: process "/bin/sh -c composer install --no-dev --optimize-autoloader" did not complete successfully: exit code: 1
error: exit status 1