<?php
header('Access-Control-Allow-Origin: http://localhost/php%20rest%20api/');
header('Content-Type: application/json');

include_once('../../config/Database.php');
include_once('../../models/Post.php');

$database = new Database();
$db = $database->connect();

$post = new Post($db);
$result = $post->read();

$count = $result->rowCount();


if ($count > 0) {
    $post_arr = array();

    $post_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $post_item = array(
            'id' => $id,
            'category_id' => $category_id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_name' => $category_name,

        );
        array_push($post_arr['data'], $post_item);
    }
    echo json_encode($post_arr);
}else{
    echo json_encode(
        array(
            "message" => "No Post Found"
        )
    );
}