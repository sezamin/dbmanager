<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Sezamin\Db\Connection;

try {
    $c = new Connection();
    $c->setConfig(['NAME'=>'lessons', 'PASSWORD'=>'dev']);
    $c->connect();
    $q = $c->query();

//    $q->delete('users', "id >0 ");
//
//    $q->insert('users', [
//        'firstName'=>'Egamberdi',
//        'lastName'=>'Shukurov',
//        'email'=>'shukurove@gmail.com',
//        'description'=>'Тут пишем описание для user',
//        'phone'=>'+998(99)5550000'
//    ]);
//
//    $q->insert('users', [
//        'firstName'=>'Valiqulov',
//        'lastName'=>'Aliqul',
//        'email'=>'aliqul@gmail.com',
//        'description'=>'Тут пишем описание для user',
//        'phone'=>'+998(99)5550000'
//    ]);



    $result = $q->selectRaw("SELECT * FROM users");

    print_r($result);

}catch(Exception $e){
    echo $e->getMessage();
}