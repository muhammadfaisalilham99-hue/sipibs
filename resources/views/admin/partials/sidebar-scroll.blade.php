<script>
(function() {
    var sidebar = document.querySelector('.sidebar');
    var storageKey = 'adminSidebarScroll';
    var saved = localStorage.getItem(storageKey);
    if (saved !== null) {
        sidebar.scrollTop = parseInt(saved, 10);
    }
    sidebar.addEventListener('scroll', function() {
        localStorage.setItem(storageKey, sidebar.scrollTop);
    });
})();
</script>
