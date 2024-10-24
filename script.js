const searchBtn = document.getElementById('search-btn');
const searchInput = document.getElementById('search');
const resultsDiv = document.getElementById('results');

searchBtn.addEventListener('click', () => {
    const query = searchInput.value;
    if (query) {
        searchSpotify(query, 0).then(displayResults);
    }
});

async function searchSpotify(query, offset) {
    const encodedQuery = encodeURIComponent(query);
    const encodedOffset = encodeURIComponent(offset);
    const response = await fetch(`spotify_requests.php?query=${encodedQuery}&offset=${encodedOffset}`, {
      method: 'GET'
    });
    var data = await response.json()
    console.log(data);
    return data;
}

function displayResults(data) {
    resultsDiv.innerHTML = '';
    console.log(data);
    const tracks = data['tracks']['items'];
    tracks.forEach(track => {
        trackDiv = getTrackAsHTML(track.id, track.name, track.artists.map(artist => artist.name).join(', '), track.album.images[0].url);
        resultsDiv.appendChild(trackDiv);
    });
}

function getTrackAsHTML(track_id, track_name, track_artist, track_img) {
    const trackDiv = document.createElement('div');
        trackDiv.classList.add('track');
        trackDiv.innerHTML = `
            <span class='spotify-link'><a href='https://open.spotify.com/track/${track_id}' target='_blank'><img src='./full-logo-framed.svg'></a></span>
            <img class="img add_track" src="${track_img}" alt="${track_name}">
            <form id="add-track-${track_id}" action="add_track.php" method="post">
                <input type="hidden" name="track_id" value="${track_id}">
                <input type="hidden" name="track_name" value="${track_name}">
                <input type="hidden" name="track_artist" value="${track_artist}">
                <input type="hidden" name="track_img" value="${track_img}">
            </form>
            <div class="info add_track">
                <h3 class="name">${track_name}</h3>
                <p class="artist">${track_artist}</p>
            </div>
        `;
        add_track = trackDiv.querySelectorAll('.add_track');
        add_track.forEach(element => {
            element.addEventListener('click', () => {
                document.getElementById(`add-track-${track_id}`).submit();
            });
        });
        return trackDiv;
}