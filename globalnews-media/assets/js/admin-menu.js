(function() {
    var adminMenu = document.getElementById('adminmenu');
    if (!adminMenu) return;

    var items = adminMenu.querySelectorAll('li.wp-has-submenu');
    var currentItem = adminMenu.querySelector('li.wp-has-submenu.wp-menu-open');

    items.forEach(function(li) {
        var link = li.querySelector('a');
        if (!link) return;

        link.addEventListener('click', function(e) {
            var submenu = li.querySelector('.wp-submenu');
            if (!submenu) return;

            var isAlreadyOpen = li.classList.contains('wp-menu-open');

            if (isAlreadyOpen && submenu.querySelector('li a[aria-current="page"]')) {
                return;
            }

            e.preventDefault();

            items.forEach(function(other) {
                if (other !== li) {
                    other.classList.remove('wp-menu-open');
                    other.classList.add('wp-not-current-submenu');
                }
            });

            if (isAlreadyOpen) {
                li.classList.remove('wp-menu-open');
                li.classList.add('wp-not-current-submenu');
            } else {
                li.classList.add('wp-menu-open');
                li.classList.remove('wp-not-current-submenu');
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!adminMenu.contains(e.target)) {
            items.forEach(function(li) {
                if (li !== currentItem) {
                    li.classList.remove('wp-menu-open');
                    li.classList.add('wp-not-current-submenu');
                }
            });
        }
    });
})();
