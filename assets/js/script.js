// Custom Scripts

document.addEventListener('DOMContentLoaded', function () {
    console.log('SMS Loaded');

    // Enable Bootstrap Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Sidebar Toggle Logic
    const toggleBtn = document.getElementById('sidebarToggle');
    const body = document.body;

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            // Check viewport width
            if (window.innerWidth <= 768) {
                // Mobile: toggle 'show' class on sidebar specifically 
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('show');
                }
            } else {
                // Desktop: toggle 'sidebar-collapsed' on body
                body.classList.toggle('sidebar-collapsed');
            }
        });
    }
});

// Simple print function helper if needed separate
function printReport() {
    window.print();
}
