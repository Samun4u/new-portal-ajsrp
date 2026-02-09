(function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-progress]').forEach(function (wrapper) {
            var value = parseInt(wrapper.getAttribute('data-progress') || '0', 10);
            var fill = wrapper.querySelector('.progress-fill');
            if (fill) {
                fill.style.width = Math.min(Math.max(value, 0), 100) + '%';
            }
        });
    });
})();

