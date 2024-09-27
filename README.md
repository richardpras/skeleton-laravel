<ul>
<li>composer install -vvv</li>
<li>set your db on .env</li>
<li>php artisan key:generate</li>
<li>php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"</li>
<li>php artisan optimize:clear</li>
<li>php artisan migrate:refresh</li>
<li>php artisan passport:install --uuids --force</li>
<li>php artisan db:seed</li>
</ul>