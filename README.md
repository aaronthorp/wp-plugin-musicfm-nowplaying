# MUSICFM NOW PLAYING PLUGIN

> This plugin provides now playing information for the MusicFM website.


## Usage Example

The plugin will update all div tags with the below `mfm_now_playing_` prefix for `artist` and `title`

```
<div class="mfm_now_playing_artist">MusicFM</div>
<div class="mfm_now_playing_title">Live!</div>
```

[images/playingimg1.png]

Once the plugin is activated, redirect the track listing update to the `/api/mfm/nowplaying` endpoint from automation software with track information, plugin will update track information as it comes available.

The database will store the following information for the track information:
 - Artist
 - Title
 - Track Type (e.g. Music/Live Show/Station ID etc.)
 - Timestamp

## Author :pencil:

* **[Aaron Thorp](https://aaronthorp.com)**

## License :page_facing_up:

[![License](http://img.shields.io/:license-gpl2-blue.svg?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)

- **[GPL 2.0 license](https://www.gnu.org/licenses/gpl-2.0.html)**
