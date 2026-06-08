import './bootstrap';
import '../../vendor/masmerise/livewire-toaster/resources/js';
import Plyr from 'plyr';
import Quill from 'quill';

window.Plyr = Plyr;
window.Quill = Quill;

function initLessonPlyr() {
    if (typeof window.Plyr === 'undefined') { return; }
    document.querySelectorAll('video.js-lesson-video:not([data-plyr-inited])').forEach(function (video) {
        try {
            new window.Plyr(video, {
                controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'fullscreen'],
                loadSprite: false,
                iconUrl: false
            });
            video.setAttribute('data-plyr-inited', '1');
        } catch (e) {
            console.error('Plyr init failed:', e, video);
        }
    });
}

document.addEventListener('DOMContentLoaded', initLessonPlyr);
document.addEventListener('livewire:navigated', initLessonPlyr);

if (window.Livewire) {
    window.Livewire.hook('morph.updated', function () {
        setTimeout(initLessonPlyr, 0);
    });
} else {
    document.addEventListener('livewire:init', function () {
        window.Livewire.hook('morph.updated', function () {
            setTimeout(initLessonPlyr, 0);
        });
    });
}
