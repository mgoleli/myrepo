<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//listenin tumunu getir
$app->get('/list', function (Request $request, Response $response) {
   $db = new Db();
   try{
    $db = $db->connect();
    $list = $db->query("SELECT u_ad from liste") -> fetchAll(PDO::FETCH_OBJ);
    //print_r($list);
       return $response //json ile getirme
            ->withStatus(200)
            ->withHeader("Content-Type",'application/json')
            ->withJson($list);
    }
   
   catch(PDOException $e){
    return $response->withJson(
        array(
            "error" =>  array(
                "text"  => $e->getMessage(),
                 "code" => $e->getCode()
             )

        )
            );
   }
});
//secileni getir
$app->get('/list/{id}', function (Request $request, Response $response) {

    $db = new Db();
    $id = $request->getAttribute("id");
    try{
     $db = $db->connect();
     $list = $db->query("SELECT u_ad from liste where u_id= $id") -> fetch(PDO::FETCH_OBJ);
     //print_r($list);
        return $response //json ile dondurme
             ->withStatus(200)
             ->withHeader("Content-Type",'application/json')
             ->withJson($list);
     }
    
    catch(PDOException $e){
     return $response->withJson(
         array(
             "error" =>  array(
                 "text"  => $e->getMessage(),
                  "code" => $e->getCode()
              )
 
         )
             );
    }
 });

 //yeni urun ekle
$app->post('/list/add', function (Request $request, Response $response) {

    $u_ad =  $request->getParam('u_ad');

    $statement = "INSERT INTO liste(u_ad) VALUES(:u_ad)";
    
     try{
     $db = new Db();
     $db = $db->connect();
     
     $prepare = $db->prepare($statement);
     $prepare->bindParam(':u_ad', $u_ad);
     $prepare->execute();
     echo '{"notice":{text": "urun eklendi"}';
    }

    catch(PDOException $e){
     
             echo '{"error": {text": '.$e->getMessage().'}';
    }
 });

 //listede urun guncelle
 $app->put('/list/update/{id}', function (Request $request, Response $response) {

    $u_id =  $request->getParam('u_id');
    $u_ad =  $request->getParam('u_ad');

    $statement = "UPDATE liste SET u_ad = :u_ad where u_id = $u_id";
    
     try{
     $db = new Db();
     $db = $db->connect();
     
     $prepare = $db->prepare($statement);
     $prepare->bindParam(':u_ad', $u_ad);
     $prepare->execute();
     echo '{"notice":{text": "urun guncellendi"}';
    }
 catch(PDOException $e){
     
             echo '{"error": {text": '.$e->getMessage().'}';
    }
 });

 //listeden urun silme
 $app->delete('/list/delete/{id}', function (Request $request, Response $response) {

    $u_id =  $request->getParam('u_id');
    
    $statement = "DELETE FROM liste where u_id = $u_id";
    
    try{
     $db = new Db();
     $db = $db->connect();
     
     $prepare = $db->prepare($statement);
     $prepare->execute();
     $db = null;
     echo '{"notice":{text": "urun silindi"}';
     }
 
    catch(PDOException $e){
     
             echo '{"error": {text": '.$e->getMessage().'}';
    }
 });