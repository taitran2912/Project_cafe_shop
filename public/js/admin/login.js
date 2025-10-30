// // Login Page
// const loginForm = document.getElementById('loginForm');
// if (loginForm) {
//     loginForm.addEventListener('submit', function(e) {
//         e.preventDefault();
        
//         const email = document.getElementById('email').value;
//         const password = document.getElementById('password').value;
        
//         // Demo credentials
//         if (email === 'admin@cafe.com' && password === 'password123') {
//             // Save login state
//             localStorage.setItem('isLoggedIn', 'true');
//             localStorage.setItem('userEmail', email);
            
//             // Redirect to admin
//             window.location.href = 'admin.html';
//         } else {
//             alert('Email hoặc mật khẩu không chính xác!\n\nDemo: admin@cafe.com / password123');
//         }
//     });
// }

// Admin Dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    // if (window.location.pathname.includes('admin.html')) {
    //     if (!localStorage.getItem('isLoggedIn')) {
    //         window.location.href = 'index.html';
    //         return;
    //     }
    // }

    // Tab Navigation
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Get tab name
            const tabName = this.getAttribute('data-tab');
            
            // Update page title
            document.getElementById('pageTitle').textContent = this.textContent.trim();
            
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName);
            if (selectedTab) {
                selectedTab.classList.add('active');
            }
        });
    });

    // Logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            localStorage.removeItem('isLoggedIn');
            localStorage.removeItem('userEmail');
            window.location.href = 'index.html';
        });
    }
});