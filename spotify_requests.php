<?php
error_reporting(0);
function searchSpotify($query, $offset, $token) {
    $url = "https://api.spotify.com/v1/search?q={$query}&type=track&market=DE&limit=50&offset={$offset}";
    $headers = [
        "Authorization: Bearer {$token}"
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo json_encode(['error' => curl_error($ch)]);
        return null;
    } 
    
    curl_close($ch);
    return json_decode($response, true);
    
}

if (isset($_GET['query']) && isset($_GET['offset'])) {
    $token = 'SPOTIFY_API_TOKEN';
    $query = urlencode($_GET['query']);
    $offset = $_GET['offset'];
    
    $result = searchSpotify($query, $offset, $token);
    
    if ($result !== null) {
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No data received.']);
    }
} else {
    echo json_encode(['error' => 'Missing query or offset parameter.']);
}
?>
