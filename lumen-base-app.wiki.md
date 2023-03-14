## Documentation

##### App\Traits\DbTrait.php
DbTrait.php use for data pagination
> Available methods
- _setDefaultLimit(limit)_
  - set default limit pagination data
- _limiter(model , defaultLimit = 10)_
    - set limit record for model
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