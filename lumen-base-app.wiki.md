## Documentation

##### App\Traits\DbTrait
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

##### App\Traits\StaticResponseTrait
StaticResponseTrait.php use for generalization response code, actually extend function from [App\Libraries\ApiResponse](#####App\Libraries\ApiResponse)

> Available methods
- _fakeResponse(status = 200, message, data)_
    - To make fake response for testing
    - **@param** *status* code
    - **@param** *message* message response
    - **@param** *data* data response
    - **@return** array
- _response301(appendText, appendTextdir)_
    - To make response 301 (move permanently)
    - **@param** *appendText* append text direction to response
    - **@param** *appendTextDir* text
    - **@return** array
- _response400(appendText, appendTextdir)_
    - To make response 400 (bad request)
    - **@param** *appendText* append text direction to response
    - **@param** *appendTextDir* text
    - **@return** array
- _response401()_
    - To make response 401 (error Unauthorized)
    - **@param** array
- _response404(appendText, appendTextdir)_
    - To make response 404 (error not found)
    - **@param** *appendText* append text direction to response
    - **@param** *appendTextDir* text
    - **@return** array
- _response500(appendText, data)_
    - To make response 404 (server error)
    - **@param** *appendText*append text to response
    - **@return** array

##### App\Libraries\ApiResponse
> Available methods
- _make(status, message = 'OK', data)_
    - Make array data into json response
    - **@param**  *status* status code
    - **@param**  *message* message
    - **@param**  *data* data
    - **@return** array
- _setIncludeData(data)_
    - Append additional data to response
    - **@param** *data* array data
    - **@return** array
- _setStatusCode(httpStatusCode, withHttpMessage = true)_
    - Set status code response
    - **@param**  *data* array data
- _setMessage(httpMessage)_
    - Set message response
    - **@param** *httpMessage* string message
- _setAppendMessage(appendMsg, direction = 'list')_
    - set http status message by http status code
    - **@param** *appendMsg* string message
- _setAppendMessageDir(direction)_
    - set http status message with direction by http status code
    - **@param** *direction* string message