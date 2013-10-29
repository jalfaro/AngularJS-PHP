<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */


// Tome como referencia http://coenraets.org/blog/2011/12/restful-services-with-jquery-php-and-the-slim-framework/

// GET route
$app->get('/books', function () {

    $books = array(
        array(
            'id' => 1, 
            'title' => "libro numero 1", 
            'author' => "Autor del libro numero 1", 
            'description' => "Descripcion del libro numero 1", 
            'year' => 2013
        ),
        array(
            'id' => 2, 
            'title' => "libro numero 2", 
            'author' => "Autor del libro numero 2", 
            'description' => "Descripcion del libro numero 2", 
            'year' => 2012
        ),
        array(
            'id' => 3, 
            'title' => "libro numero 3", 
            'author' => "Autor del libro numero 3", 
            'description' => "Descripcion del libro numero 3", 
            'year' => 2011
        ),
        array(
            'id' => 4, 
            'title' => "libro numero 4", 
            'author' => "Autor del libro numero 4", 
            'description' => "Descripcion del libro numero 4", 
            'year' => 2010
        ),
    );

    header("Content-Type: application/json");
    echo json_encode($books);
});

// GET /{id} route
$app->get('/books/:id', function ($id) {

    // logica para ir a buscar el libro en base al id

    $book = array(
        'id' => $id, 
        'title' => "libro numero $id", 
        'author' => "Autor del libro numero $id", 
        'description' => "Descripcion del libro numero $id", 
        'year' => 2013
    );

    header("Content-Type: application/json");
    echo json_encode($book);
});

// POST route
$app->post('/books', function () use ($app) {

    $request = $app->request();
    $book = json_decode($request->getBody());

    // logica para hacer el insert del libro.

    $book->id = 100;

    header("Content-Type: application/json");
    echo json_encode($book);
});

// PUT route
$app->put('/books/:id', function ($id) use ($app) {
    $request = $app->request();
    $book = json_decode($request->getBody());

    // logica para hacer el update del libro.

    header("Content-Type: application/json");
    echo json_encode($book);
});

// PATCH route
$app->patch('/books/:id', function ($id) {
    echo 'Sin implementar! pero deveria ser un update solo de los campos enviados para el libro ' . $id;
});

// DELETE route
$app->delete('/books/:id', function ($id) {
    // logica para eliminar el libro
});

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
