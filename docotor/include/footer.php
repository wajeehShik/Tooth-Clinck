
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');

        function toggleSidebar() {
            const isOpen = !sidebar.classList.contains('translate-x-full');
            
            if (isOpen) {
                sidebar.classList.add('translate-x-full');
                sidebarOverlay.classList.add('hidden');
                sidebarOverlay.classList.remove('opacity-100');
            } else {
                sidebar.classList.remove('translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => sidebarOverlay.classList.add('opacity-100'), 10);
            }
        }

        openSidebarBtn.addEventListener('click', toggleSidebar);
        closeSidebarBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
<?php
ob_end_flush();?>