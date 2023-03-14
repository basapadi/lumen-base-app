## Lumen Base App 
 This lumen base project can use for production project but **not recommended**. Maybe project structure has been added,removed or modified. If you want request new feature on this project you can create new issue on this repository.

#### Author
> Bachtiar Panjaitan <bachtiarpanjaitan0@gmail.com>

#### Requirements
- PHP 7.4+
- Laravel Lumen 8.3+

#### Installed Packages
- doctrine/dbal
- intervention/image
- league/fractal
- nesbot/carbon
- spatie/laravel-fractal
- tymon/jwt-auth

#### Available Migrations
- User Migration

#### Available Data Seeds
- User data seed

#### Available Routes
- User Login using JWT Auth
- User Logout

#### Installed Middlewares
- Authentication Middleware

## Documentation

#### DbTrait.php
DbTrait.php use for data pagination
> Available methods
- _setDefaultLimit(limit)_
  set default limit pagination data
- _limiter(model , defaultLimit = 10)_
    set limit record for model
    - **@param** Illuminate\Database\Eloquent\Builder model
    - **@param** int defaultLimit
    - **@return** Illuminate\Database\Eloquent\Builder

> Example use

```
use App\Traits\DbTrait;

class BlogService {
    use DbTrait;

    /** function to get data blogs */
    ...
        $blogs = Blog::where('title', 'like','%lumen%');
        $this->limiter($blogs);
        $results = $blogs->get();
    ...
}

```
