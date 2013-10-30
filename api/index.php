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
	$sql = "select * FROM libro";
	try {
	        $db = getConnection();
	        $stmt = $db->query($sql);  
	        $proyectos = $stmt->fetchAll(PDO::FETCH_OBJ);
	        $db = null;
	       // echo '{"subproyecto": ' . json_encode($proyectos) . '}';
	    } catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	    }
    $books = $proyectos;
    header("Content-Type: application/json");
    echo json_encode($books) ;
});

// GET /{id} route
$app->get('/books/:id', function ($id) {

    // logica para ir a buscar el libro en base al id
	$sql = "select * FROM libro WHERE id =" . $id;
	$book=array();
	try {
	        $db = getConnection();
	        $stmt = $db->query($sql);  
	        $book = $stmt->fetchAll(PDO::FETCH_OBJ);
	        $db = null;
	    } catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	    }

    header("Content-Type: application/json");
    echo json_encode($book[0]);
});

// POST route
$app->post('/books', function () use ($app) {
	

    $request = $app->request();
    $book = json_decode($request->getBody());
	header("Content-Type: application/json");
	try {
	        $db = getConnection();
	      	$insert = $db->prepare("INSERT INTO libro(title,author,year,description) VALUES (?,?,?,?)");
			$insert->execute(array($book->title,$book->author,$book->year,$book->description));
			$db = null;
			echo '{"status":0,"text":"bien"}';
	    } catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() ." - ".$element->author.'}}'; 
	    }
});

// PUT route
$app->put('/books', function () use ($app) {
    $request = $app->request();
    $book = json_decode($request->getBody());
 	header("Content-Type: application/json");
	try {
    	$db = getConnection();
  		$update = $db->prepare("UPDATE libro SET title= ?,author= ?,year=?,description=? WHERE id = ?");
		$update->execute(array($book->title,$book->author,$book->year,$book->description,$book->id));
		$db = null;
		echo '{"status":0,"text":"bien"}';
	} catch(PDOException $e) {
    	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

// PATCH route
$app->patch('/books/:id', function ($id) {
    echo 'Sin implementar! pero deveria ser un update solo de los campos enviados para el libro ' . $id;
});

// DELETE route
$app->delete('/books/:id', function ($id) {
   	header("Content-Type: application/json");
	try {
    	$db = getConnection();
  		$delete = $db->prepare("DELETE FROM libro  WHERE id = ?");
		$delete->execute(array($id));
		$db = null;
		echo '{"status":0,"text":"bien"}';
	} catch(PDOException $e) {
    	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
/**
 * Function to configure the database server connection
**/
function getConnection() {
    $dbhost="<server>";
    $dbuser="<dbuser>";
    $dbpass="<password>";
    $dbname="Biblioteca";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh -> exec("set names utf8");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
