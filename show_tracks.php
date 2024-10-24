<?php
error_reporting(0);
$jsonString = file_get_contents('tracks.json');
$tracks = json_decode($jsonString, true);

echo "<link rel='stylesheet' type='text/css' href='style.css'>
<title>Wünsche</title><h1>Wünsche</h1><div>";

if ($tracks === null) {
    echo "Keine Wünsche vorhanden</div>";
    exit;
}

usort($tracks, function($a, $b) {
    return $a['time'] <=> $b['time'];
});

foreach ($tracks as $track) {
    $track_id = $track['track_id'];
    $track_name = $track['track_name'];
    $track_artist = $track['track_artist'];
    $track_img = $track['track_img'];
    $time = date('d.m.Y H:i', $track['time']);
    echo "<div class='track' id='track-$track_id'>";
    echo "<span class='spotify-link'><a href='https://open.spotify.com/track/$track_id' target='_blank'><img src='./full-logo-framed.svg'></a></span>";
    echo "<img class='img' src='$track_img' alt='$track_name'>";
    echo "<div class='info'>";
    echo "<h3 class='name' id='$track_id' style='cursor: pointer;'>$track_name</h3>";
    echo "<p class='artist'>$track_artist</p>";
    echo "</div>";
    echo "</div>";
    echo "<script>
    document.getElementById('$track_id').addEventListener('click', function() {
        var password = getPassword();
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_track.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('track_id=$track_id&password=' + password);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById('track-$track_id').remove();
                    document.cookie = 'password=' + password + '; path=/';
                } else {
                    document.cookie = 'password=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                }
            }
        }
    });

    function getPassword() {
        var pass = '';
        if (document.cookie.indexOf('password=') !== -1) {
            var password = document.cookie.split('password=')[1].split(';')[0];
            pass = password;
        } else {
            var password = document.getElementById('pass').value;
            document.cookie = 'password=' + password + '; path=/';
            pass = password;
        }
        return pass;
    }
    </script>";
}
echo "<br><input type='password' id='pass' placeholder='Password'>
<script>
// Set the password input value to the password stored in the cookie if Content is loaded
document.getElementById('pass').value = getPassword();
</script>
";
?>