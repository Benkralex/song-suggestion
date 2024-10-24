<?php
error_reporting(0);
if (!isset($_POST["password"])) {
    echo json_encode(["error" => "Missing password parameter."]);
    exit;
}

$password = $_POST["password"];
$correctPassword = "K0817Jh"§a124k&623$nd";

if ($password !== $correctPassword) {
    echo json_encode(["error" => "Incorrect password."]);
    exit;
}
if (isset($_POST["track_id"])) {
    $track_id = $_POST["track_id"];
    $json = file_get_contents("tracks.json");
    $data = json_decode($json, true);
    if (!is_array($data)) {
        $data = [];
    }
    $newData = [];
    $deleted = false;
    foreach ($data as $d) {
        if ($d["track_id"] == $track_id) {
            $deleted = true;
        } else {
            $newData[] = $d;
        }
    }
    file_put_contents("tracks.json", json_encode($newData));
    echo json_encode(["success" => $deleted]);
} else {
    echo json_encode(["error" => "Missing track_id parameter."]);
}