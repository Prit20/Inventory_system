</div> <!-- End of main content -->
</body>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const header = document.querySelector('header');
    const mainContent = document.querySelector('.main-content');
    const darkToggle = document.getElementById('darkToggle');

    // Toggle Sidebar
    menuToggle.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            sidebar.style.width = '0';
            header.style.marginLeft = '0';
            mainContent.style.marginLeft = '0';
        } else {
            sidebar.style.width = '15rem'; // 64 * 0.25 rem = 16rem
            header.style.marginLeft = '15rem';
            mainContent.style.marginLeft = '15rem';
        }
    });

    // Dark Mode Toggle
    if (darkToggle) {
        darkToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
        });
    }

    // Submenu Toggle
    document.querySelectorAll('.submenu-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.target);
            if (target) {
                target.classList.toggle('hidden');
            }
        });
    });

    // Sidebar Active Link
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', (e) => {
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            e.currentTarget.classList.add('active');
        });
    });

    // Initial sidebar state
    sidebar.style.width = '15rem'; 
    header.style.marginLeft = '15rem';
    mainContent.style.marginLeft = '15rem';
});
</script>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

</html>
