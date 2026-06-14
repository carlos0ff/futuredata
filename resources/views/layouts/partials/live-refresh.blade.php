<script>
(function () {
    function refresh(el) {
        if (document.hidden) return;
        if (el.contains(document.activeElement) && document.activeElement !== document.body) return;
        if (el.querySelector('[open]')) return;

        var url = el.dataset.liveRefreshUrl || window.location.href;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) { return res.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var fresh = doc.getElementById(el.id);
                if (!fresh) return;
                el.innerHTML = fresh.innerHTML;
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(el);
                }
            })
            .catch(function () {});
    }

    document.querySelectorAll('[data-live-refresh]').forEach(function (el) {
        var seconds = parseInt(el.dataset.liveRefresh, 10) || 30;
        setInterval(function () { refresh(el); }, seconds * 1000);
    });
})();
</script>
