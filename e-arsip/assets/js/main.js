$(document).ready(function() {
    // Hide Loader
    setTimeout(function() {
        $('#loader-overlay').fadeOut('slow');
    }, 500);

    // Sidebar Toggle for Mobile/Desktop
    $('#sidebarToggle').on('click', function(e) {
        e.preventDefault();
        if ($(window).width() <= 768) {
            $('.sidebar').toggleClass('show');
        } else {
            $('.sidebar').toggleClass('collapsed');
        }
        
        // Trigger resize untuk memperbaiki tabel DataTables yang "panjang sebelah"
        setTimeout(function() {
            $(window).trigger('resize');
        }, 310);
    });

    // Dark Mode Toggle
    const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);
        document.documentElement.setAttribute('data-bs-theme', currentTheme);
        if (currentTheme === 'dark') {
            $('#themeToggle i').removeClass('fa-moon').addClass('fa-sun');
        }
    }

    $('#themeToggle').on('click', function(e) {
        e.preventDefault();
        let theme = document.documentElement.getAttribute('data-theme');
        let icon = $(this).find('i');
        
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'light');
            document.documentElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
            icon.removeClass('fa-sun').addClass('fa-moon');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            icon.removeClass('fa-moon').addClass('fa-sun');
        }
    });

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Global DataTable settings (if any table exists)
    if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            },
            responsive: true,
            pageLength: 10
        });
    }
});
