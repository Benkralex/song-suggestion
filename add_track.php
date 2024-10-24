<?php
error_reporting(0);
if (isset($_POST["track_id"]) &&
    isset($_POST["track_name"]) &&
    isset($_POST["track_artist"]) &&
    isset($_POST["track_img"])) {
        $track_id = $_POST["track_id"];
        $track_name = $_POST["track_name"];
        $track_artist = $_POST["track_artist"];
        $track_img = $_POST["track_img"];
        $time = time();
        $json = file_get_contents("tracks.json");
        $data = json_decode($json, true);
        if (!is_array($data)) {
            $data = [];
        }
        foreach ($data as $d) {
            if ($d["track_id"] == $track_id) {
            echo "<link rel='stylesheet' href='style.css'>Dieses Lied wurde bereits hinzugefügt.
        <a href='./'><button class='btn'>Startseite</button></a>";
            exit;
            }
        }
        $data[] = array("track_id" => $track_id, "track_name" => $track_name, "track_artist" => $track_artist, "track_img" => $track_img, "time" => $time);
        file_put_contents("tracks.json", json_encode($data));
        echo "
    <link rel='stylesheet' href='style.css'>Lied wurde erfolgreich hinzugefügt.
        <a href='./'><button class='btn'>Startseite</button></a>";
} else {
    header("Location: ./");
}
?>