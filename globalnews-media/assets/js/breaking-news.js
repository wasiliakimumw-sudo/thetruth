/**
 * GlobalNews Media - Breaking News Ticker
 */

(function() {
    'use strict';

    var ticker = document.querySelector('.breaking-ticker');
    if (!ticker) return;

    var track = document.getElementById('tickerTrack');
    if (!track) return;

    var items = track.querySelectorAll('.ticker-item');
    if (items.length < 2) return;

    var currentIndex = 0;
    var itemWidth = 100;
    var speed = parseInt(ticker.dataset.speed, 10) || 4000;

    var itemHeight = ticker.offsetHeight;

    function moveTicker() {
        currentIndex++;
        if (currentIndex >= items.length) {
            currentIndex = 0;
        }
        var offset = -currentIndex * itemWidth;
        track.style.transform = 'translateX(' + offset + '%)';
    }

    var tickerInterval = setInterval(moveTicker, speed);

    ticker.addEventListener('mouseenter', function() {
        clearInterval(tickerInterval);
    });

    ticker.addEventListener('mouseleave', function() {
        tickerInterval = setInterval(moveTicker, speed);
    });

})();
