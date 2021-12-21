# Introduction

Fast, flexible and extendable request router for PHP. 

## Installation

Via composer:

<code>
composer install merophp/router
</code>

## Basic Usage

Route definitions will be stored in route collectors. The route providers job is 
to provide routes by a requested HTTP method from its route collection. The router 
get the routes from a provider and look for a matching route. He also executes route 
handlers.

<pre><code>require_once 'vendor/autoload.php';

use Merophp\Router\Routes\GetRoute;
use Merophp\Router\Collection\RouteCollection;
use Merophp\Router\Provider\RouteProvider;
use Merophp\Router\Router;

$collection = new RouteCollection;
$collection->addMultiple([
    new GetRoute('/api/v1/foo', function(){
        return 'Hello foo';
    }),
    new GetRoute('/api/v1/bar', function(){
        return 'Hello bar';
    }),
]);

$provider = new RouteProvider($collection);
$router = new Router($provider);

$myRoute = $router->match('GET', '/api/v1/foo');

$result = $router->dispatch($myRoute);
//Will print 'Hello bar'...
echo $result;
</code></pre>

### Route Definitions

Route definitions consists of three parts: the HTTP methods, 
a pattern and a handler.

<pre><code>use Merophp\Router\Routes\Route;

new Route(['GET','POST'], '/api/v1/foo', function(){
    echo 'I will be executed';
});
</code></pre>

One route definition for all HTTP methods is also possible with a wildcard:

<pre><code>new Route(['*'], '/api/v1/foo', function(){
    echo 'I am the handler';
});
</code></pre>

For the case that a route definition has only one HTTP method, 
you can also use following classes:

* Merophp\Router\Routes\GetRoute
* Merophp\Router\Routes\PostRoute
* Merophp\Router\Routes\PutRoute
* Merophp\Router\Routes\DeleteRoute
* Merophp\Router\Routes\PatchRoute
* Merophp\Router\Routes\OptionsRoute
* Merophp\Router\Routes\HeadRoute

<pre><code>new GetRoute('/api/v1/foo', function(){
    echo 'I am the handler for GET:/api/v1/foo';
});
new PostRoute('/api/v1/foo', function(){
    echo 'I am the handler for POST:/api/v1/foo';
});
</code></pre>

#### The Pattern

A routes pattern will be compared to a given URI path. 
The dispatcher will find the best fitting route definition 
for a given HTTP method and a URI path. You can use wildcards 
and placeholders in your route pattern. Values for placeholders 
will be passed to the routes handler.

<pre><code>new GetRoute('*', function(){
    echo 'I will be executed if no other definition fits';
});
new GetRoute('/api/v1/*', function(){

});
new GetRoute('/api/v1/events/{eventid}', function($eventid){
    echo $eventid;
});
new DeleteRoute('/api/v1/events/{eventid}', function($eventid){
    echo $eventid.' deleted';
});
</code></pre>


#### The Handler

Any callable can be used as a route handler.

#### Scopes

Scopes are brackets for routes and made to prevent you from 
writing the same pattern parts for different routes multiple times.

<pre><code>use Merophp\Router\Routes\Scope;
use Merophp\Router\Routes\GetRoute;
use Merophp\Router\Routes\DeleteRoute;

new Scope('/api/v1/events/{eventid}', [
    new GetRoute('', function(){}),
    new DeleteRoute('', function(){})
]);
</code></pre>

The pattern of the scope will be used as prefix for the patterns of the routes.



