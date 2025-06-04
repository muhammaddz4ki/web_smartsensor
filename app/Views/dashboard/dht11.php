<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring Sensor DHT11</title>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-database-compat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/monitoring.css') ?>">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
            <p>Monitoring Sensor DHT11</p>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
 <a href="<?= url_to('App\Controllers\Monitoring::allsensor') ?>">
                  <i class="fas fa-sensor"></i> Semua Sensor Data
              </a>
                </li>

                                <li>
                    <a href="<?= url_to('App\Controllers\Monitoring::ultrasonik') ?>">
                        <i class="fas fa-ruler-vertical"></i> Ultrasonic
                    </a>
                </li>
                <li>
                    <a href="<?= url_to('App\Controllers\Monitoring::ldr') ?>">
                        <i class="fas fa-sun"></i> LDR
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fas fa-bell"></i> Alerts
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <p>Version 1.0.0</p>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Navbar -->
        <div class="navbar">
            <div class="navbar-left">
                <button class="toggle-sidebar" id="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Dashboard Monitoring</h4>
            </div>
            
            <div class="user-menu">
                <button class="user-menu-btn">
                    <div class="user-avatar" id="user-avatar">A</div>
                    <span id="username-display">Admin</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="user-menu-dropdown">
                    <a href="#" id="view-profile-btn">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="#" id="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="container">
            <div class="dashboard-grid">
                <div class="card">
                    <h2>Grafik Suhu dan Kelembapan vs Waktu</h2>
                    <div class="chart-container">
                        <canvas id="dhtChart"></canvas>
                    </div>
                    <div class="last-update" id="chart-update-time">Terakhir diperbarui: -</div>
                </div>
                
                <div class="card">
                    <h2>Status Sensor</h2>
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span class="status-indicator online" id="status-indicator"></span>
                        <span id="status-text">Menyambungkan...</span>
                    </div>
                    <div>
                        <p><strong>Suhu Terakhir:</strong> <span id="last-temperature">-</span> °C</p>
                        <p><strong>Kelembapan Terakhir:</strong> <span id="last-humidity">-</span> %</p>
                        <p><strong>Waktu Pembacaan:</strong> <span id="last-timestamp">-</span></p>
                        <p><strong>Interval Pembacaan:</strong> <span id="reading-interval">-</span> detik</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h2>Data Historis (20 Pembacaan Terakhir)</h2>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Suhu (°C)</th>
                                <th>Kelembapan (%)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="data-body"></tbody>
                    </table>
                </div>
                <div class="last-update" id="table-update-time">Terakhir diperbarui: -</div>
            </div>
        </div>
    </div>
    
    <!-- Profile Modal -->
    <div class="modal" id="profile-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>User Profile</h3>
                <button class="close-modal" id="close-modal">&times;</button>
            </div>
            <div class="profile-info">
                <p><strong>Username:</strong> <span id="profile-username">admin</span></p>
                <p><strong>Email:</strong> <span id="profile-email">admin@example.com</span></p>
                <p><strong>Account Created:</strong> <span id="profile-created">2023-01-01</span></p>
            </div>
            <div>
                <button class="btn btn-primary" id="change-password-btn">Change Password</button>
                <button class="btn btn-secondary" id="close-modal-btn">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Konfigurasi Firebase
        const firebaseConfig = {
            apiKey: "AIzaSyBHNiAA0efUZx9_Df7o3HRceK8RqnxGE6U",
            authDomain: "projeklora.firebaseapp.com",
            databaseURL: "https://projeklora-default-rtdb.firebaseio.com",
            projectId: "projeklora",
            storageBucket: "projeklora.appspot.com",
            messagingSenderId: "YOUR_SENDER_ID",
            appId: "YOUR_APP_ID"
        };

        // Inisialisasi Firebase
        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();

        // Simpan data user yang login
        let currentUser = {
            id: 1,
            username: "admin",
            email: "admin@example.com",
            created_at: "2023-01-01 00:00:00"
        };

        // Elemen DOM
        const dataBody = document.getElementById("data-body");
        const lastTemperatureElement = document.getElementById("last-temperature");
        const lastHumidityElement = document.getElementById("last-humidity");
        const lastTimestampElement = document.getElementById("last-timestamp");
        const statusIndicator = document.getElementById("status-indicator");
        const statusText = document.getElementById("status-text");
        const readingIntervalElement = document.getElementById("reading-interval");
        const chartUpdateTime = document.getElementById("chart-update-time");
        const tableUpdateTime = document.getElementById("table-update-time");
        const toggleSidebar = document.getElementById("toggle-sidebar");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        const userAvatar = document.getElementById("user-avatar");
        const usernameDisplay = document.getElementById("username-display");
        const viewProfileBtn = document.getElementById("view-profile-btn");
        const logoutBtn = document.getElementById("logout-btn");
        const profileModal = document.getElementById("profile-modal");
        const closeModal = document.getElementById("close-modal");
        const closeModalBtn = document.getElementById("close-modal-btn");
        const profileUsername = document.getElementById("profile-username");
        const profileEmail = document.getElementById("profile-email");
        const profileCreated = document.getElementById("profile-created");

        // Variabel untuk Chart
        let dhtChart;
        let previousTimestamp = null;

        // Inisialisasi Chart
        function initializeChart() {
            const ctx = document.getElementById('dhtChart').getContext('2d');
            dhtChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Suhu (°C)',
                            data: [],
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Kelembapan (%)',
                            data: [],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Suhu (°C)'
                            },
                            min: 0,
                            max: 50
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Kelembapan (%)'
                            },
                            min: 0,
                            max: 100,
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Waktu'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                        label += context.dataset.label.includes('Suhu') ? '°C' : '%';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Format waktu
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString();
        }

        // Format waktu singkat untuk chart
        function formatTimeShort(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString();
        }

        // Update status koneksi
        function updateConnectionStatus(connected) {
            if (connected) {
                statusIndicator.className = 'status-indicator online';
                statusText.textContent = 'Terhubung ke Database Realtime';
            } else {
                statusIndicator.className = 'status-indicator offline';
                statusText.textContent = 'Koneksi terputus - Mencoba menyambungkan kembali...';
            }
        }

        // Update waktu terakhir
        function updateLastUpdateTime() {
            const now = new Date();
            const timeString = now.toLocaleString();
            chartUpdateTime.textContent = `Terakhir diperbarui: ${timeString}`;
            tableUpdateTime.textContent = `Terakhir diperbarui: ${timeString}`;
        }

        // Toggle sidebar di mobile
        function toggleSidebarFn() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');
        }

        // Tampilkan profile modal
        function showProfileModal() {
            // Update data profil
            profileUsername.textContent = currentUser.username;
            profileEmail.textContent = currentUser.email;
            profileCreated.textContent = currentUser.created_at;
            
            // Tampilkan modal
            profileModal.style.display = 'block';
        }

        // Tutup profile modal
        function closeProfileModal() {
            profileModal.style.display = 'none';
        }

        // Logout function
        function logout() {
            // Di sini Anda bisa menambahkan logika logout seperti membersihkan session
            alert('Anda telah logout');
            // Redirect ke halaman login
            window.location.href = 'login.html';
        }

        // Inisialisasi
        function init() {
            initializeChart();
            
            // Set user info
            userAvatar.textContent = currentUser.username.charAt(0).toUpperCase();
            usernameDisplay.textContent = currentUser.username;
            
            // Event listeners
            toggleSidebar.addEventListener('click', toggleSidebarFn);
            viewProfileBtn.addEventListener('click', showProfileModal);
            logoutBtn.addEventListener('click', logout);
            closeModal.addEventListener('click', closeProfileModal);
            closeModalBtn.addEventListener('click', closeProfileModal);
            
            // Close modal ketika klik di luar modal
            window.addEventListener('click', (e) => {
                if (e.target === profileModal) {
                    closeProfileModal();
                }
            });
            
            // Mendeteksi status koneksi
            database.ref(".info/connected").on("value", (snapshot) => {
                updateConnectionStatus(snapshot.val() === true);
            });

            // Mendapatkan data dari Firebase
            database.ref("sensor_data").orderByChild("timestamp").limitToLast(20).on("value", (snapshot) => {
                dataBody.innerHTML = "";
                const data = snapshot.val();
                
                if (data) {
                    // Filter data untuk node_id tertentu dan urutkan berdasarkan timestamp
                    const rows = Object.entries(data)
                        .filter(([key, value]) => value.node_id === 4) // Sesuaikan dengan node_id DHT11 Anda
                        .sort((a, b) => a[1].timestamp - b[1].timestamp);
                    
                    // Siapkan data untuk chart
                    const chartLabels = [];
                    const temperatureData = [];
                    const humidityData = [];
                    
                    // Proses setiap baris data
                    rows.forEach(([key, value]) => {
                        const dateString = formatTime(value.timestamp);
                        const temperature = value.temperature || 0; // Sesuaikan dengan field suhu di Firebase
                        const humidity = value.humidity || 0; // Sesuaikan dengan field kelembapan di Firebase
                        
                        // Tambahkan ke tabel
                        const row = `
                            <tr>
                                <td>${dateString}</td>
                                <td>${temperature.toFixed(1)}</td>
                                <td>${humidity.toFixed(1)}</td>
                                <td><span class="status-indicator online"></span> Valid</td>
                            </tr>
                        `;
                        dataBody.innerHTML += row;
                        
                        // Tambahkan ke data chart
                        chartLabels.push(formatTimeShort(value.timestamp));
                        temperatureData.push(temperature);
                        humidityData.push(humidity);
                        
                        // Simpan data terbaru untuk tampilan status
                        lastTemperatureElement.textContent = temperature.toFixed(1);
                        lastHumidityElement.textContent = humidity.toFixed(1);
                        lastTimestampElement.textContent = dateString;
                        
                        // Hitung interval pembacaan
                        if (previousTimestamp) {
                            const interval = (value.timestamp - previousTimestamp) / 1000;
                            readingIntervalElement.textContent = interval.toFixed(2);
                        }
                        previousTimestamp = value.timestamp;
                    });
                    
                    // Update chart
                    dhtChart.data.labels = chartLabels;
                    dhtChart.data.datasets[0].data = temperatureData;
                    dhtChart.data.datasets[1].data = humidityData;
                    dhtChart.update();
                    
                    // Update waktu terakhir
                    updateLastUpdateTime();
                }
            });
        }

        // Jalankan inisialisasi saat DOM siap
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>