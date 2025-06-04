<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring All Sensors</title>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-database-compat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --dark-color: #34495e;
            --light-color: #ecf0f1;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--dark-color);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar.active {
            left: -250px;
        }
        
        .sidebar-header {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        
        .sidebar-header p {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
        }
        
        .sidebar-menu li a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        .sidebar-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 25px;
        }
        
        .sidebar-menu li a.active {
            background-color: var(--primary-color);
            border-left: 4px solid white;
        }
        
        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            font-size: 0.7rem;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .main-content.active {
            margin-left: 0;
        }
        
        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.2rem;
            margin-right: 15px;
            cursor: pointer;
            color: var(--dark-color);
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-menu-btn {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .user-menu-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 150px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 0;
            display: none;
            z-index: 100;
        }
        
        .user-menu:hover .user-menu-dropdown {
            display: block;
        }
        
        .user-menu-dropdown a {
            display: block;
            padding: 8px 15px;
            color: #333;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .user-menu-dropdown a:hover {
            background-color: #f5f5f5;
            color: var(--primary-color);
        }
        
        .user-menu-dropdown a i {
            margin-right: 8px;
            width: 20px;
        }
        
        /* Container Styles */
        .container {
            padding: 20px;
        }
        
        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .card h2 {
            margin-bottom: 15px;
            color: var(--dark-color);
            font-size: 1.2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .last-update {
            font-size: 0.8rem;
            color: #777;
            margin-top: 10px;
            text-align: right;
        }
        
        /* Error Panel Styles */
        .error-panel {
            background-color: #ffe6e6;
            border-left: 4px solid var(--danger-color);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .error-title {
            color: var(--danger-color);
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .error-title i {
            margin-right: 8px;
        }
        
        .error-item {
            margin-bottom: 5px;
            padding-left: 15px;
            position: relative;
            font-size: 0.9rem;
        }
        
        .error-item:before {
            content: "•";
            color: var(--danger-color);
            position: absolute;
            left: 0;
        }
        
        /* Node Status Styles */
        .node-status {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .node-id {
            font-weight: bold;
            width: 100px;
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .status-indicator.online {
            background-color: var(--secondary-color);
        }
        
        .status-indicator.offline {
            background-color: var(--danger-color);
        }
        
        .status-indicator.warning {
            background-color: var(--warning-color);
        }
        
        .status-indicator.error {
            background-color: var(--danger-color);
        }
        
        .status-indicator.unknown {
            background-color: #95a5a6;
        }
        
        /* Sensor Grid Styles */
        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .sensor-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }
        
        .sensor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .sensor-card h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
        }
        
        .sensor-card h3 i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .sensor-value {
            font-size: 28px;
            font-weight: bold;
            margin: 15px 0;
            display: flex;
            align-items: flex-end;
        }
        
        .sensor-unit {
            color: #777;
            font-size: 16px;
            margin-left: 5px;
            font-weight: normal;
        }
        
        .sensor-timestamp {
            color: #999;
            font-size: 12px;
        }
        
        .warning {
            color: var(--warning-color);
        }
        
        .danger {
            color: var(--danger-color);
        }
        
        /* Chart Container */
        .chart-container {
            height: 400px;
            position: relative;
        }
        
        /* Debug Section */
        .debug-section {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-family: monospace;
            font-size: 14px;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
        }
        
        .debug-section p {
            margin: 5px 0;
            line-height: 1.4;
        }
        
        .debug-section .info {
            color: #3498db;
        }
        
        .debug-section .success {
            color: #2ecc71;
        }
        
        .debug-section .warning {
            color: #f39c12;
        }
        
        .debug-section .error {
            color: #e74c3c;
        }
        
        /* Button Styles */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .modal-header {
            padding: 15px 20px;
            background-color: var(--dark-color);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            font-size: 1.2rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .profile-info {
            padding: 20px;
        }
        
        .profile-info p {
            margin-bottom: 10px;
        }
        
        .profile-info strong {
            display: inline-block;
            width: 120px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid #eee;
        }
        
        .modal-footer .btn {
            margin-left: 10px;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
            
            .sensor-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
            <p>Monitoring All Sensors</p>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
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
                    <a href="<?= url_to('App\Controllers\Monitoring::dht11') ?>">
                        <i class="fas fa-thermometer-half"></i> DHT11
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-bell"></i> Alerts
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
                <h4>Dashboard Monitoring All Sensors</h4>
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
            <!-- Error Panel -->
            <div class="error-panel" id="error-panel" style="display: none;">
                <div class="error-title">
                    <i class="fas fa-exclamation-triangle"></i> System Issues Detected
                </div>
                <div id="error-list"></div>
            </div>
            
            <!-- Node Status -->
            <div class="card">
                <h2>Node Status</h2>
                <div id="node-status-container"></div>
                <div class="last-update" id="node-update-time">Last checked: -</div>
            </div>
            
            <!-- Sensor Grid -->
            <div class="sensor-grid">
                <!-- Ultrasonic Sensor -->
                <div class="sensor-card">
                    <h3><i class="fas fa-ruler-vertical"></i> Ultrasonic Sensor</h3>
                    <div class="sensor-value" id="ultrasonic-value">- <span class="sensor-unit">cm</span></div>
                    <div class="sensor-timestamp" id="ultrasonic-time">Last reading: -</div>
                    <div class="node-status" id="ultrasonic-status">
                        <span class="status-indicator unknown"></span>
                        <span>Status: Unknown</span>
                    </div>
                </div>
                
                <!-- LDR Sensor -->
                <div class="sensor-card">
                    <h3><i class="fas fa-sun"></i> LDR Sensor</h3>
                    <div class="sensor-value" id="ldr-value">- <span class="sensor-unit">lux</span></div>
                    <div class="sensor-timestamp" id="ldr-time">Last reading: -</div>
                    <div class="node-status" id="ldr-status">
                        <span class="status-indicator unknown"></span>
                        <span>Status: Unknown</span>
                    </div>
                </div>
                
                <!-- DHT11 Temperature -->
                <div class="sensor-card">
                    <h3><i class="fas fa-thermometer-half"></i> Temperature</h3>
                    <div class="sensor-value" id="temp-value">- <span class="sensor-unit">°C</span></div>
                    <div class="sensor-timestamp" id="temp-time">Last reading: -</div>
                    <div class="node-status" id="temp-status">
                        <span class="status-indicator unknown"></span>
                        <span>Status: Unknown</span>
                    </div>
                </div>
                
                <!-- DHT11 Humidity -->
                <div class="sensor-card">
                    <h3><i class="fas fa-tint"></i> Humidity</h3>
                    <div class="sensor-value" id="humidity-value">- <span class="sensor-unit">%</span></div>
                    <div class="sensor-timestamp" id="humidity-time">Last reading: -</div>
                    <div class="node-status" id="humidity-status">
                        <span class="status-indicator unknown"></span>
                        <span>Status: Unknown</span>
                    </div>
                </div>
            </div>
            
            <!-- Combined Chart -->
            <div class="card">
                <h2>Sensor Data Trends</h2>
                <div class="chart-container" style="height: 400px;">
                    <canvas id="combinedChart"></canvas>
                </div>
                <div class="last-update" id="chart-update-time">Last updated: -</div>
            </div>
            
            <!-- Debug Section -->
            <div class="card">
                <h2>System Debug Information</h2>
                <div class="debug-section" id="debug-console">
                    <p>System initialized. Waiting for sensor data...</p>
                </div>
                <button class="btn btn-secondary" id="clear-debug-btn">Clear Debug</button>
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
            <p><strong>Username:</strong> <span id="profile-username">-</span></p>
            <p><strong>Email:</strong> <span id="profile-email">-</span></p>
            <p><strong>Last Login:</strong> <span id="profile-last-login">-</span></p>
            <p><strong>Account Created:</strong> <span id="profile-created">-</span></p>
        </div>
        <div class="modal-footer">
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

        // Node configuration
        const NODE_CONFIG = {
            1: { name: "Node 1", expectedSensors: ["temperature", "humidity"], updateInterval: 30000 },
            2: { name: "Node 2", expectedSensors: ["ldr"], updateInterval: 30000 },
            3: { name: "Node 3", expectedSensors: ["distance"], updateInterval: 30000 },
            4: { name: "Node 4", expectedSensors: ["temperature", "humidity", "distance", "ldr"], updateInterval: 30000 }
        };

        // Sensor thresholds
        const SENSOR_THRESHOLDS = {
            temperature: { min: -10, max: 50, warning: { min: 0, max: 40 } },
            humidity: { min: 0, max: 100, warning: { min: 20, max: 80 } },
            distance: { min: 0, max: 400, warning: { min: 5, max: 200 } },
            ldr: { min: 0, max: 1023, warning: { min: 50, max: 900 } }
        };

        // Variabel untuk menyimpan data
        let sensorData = {
            temperature: [],
            humidity: [],
            distance: [],
            ldr: []
        };
        
        let nodeStatus = {};
        let lastUpdateTimes = {};
        let combinedChart;
        let errors = [];
        let debugMessages = [];

        // Elemen DOM
        const errorPanel = document.getElementById("error-panel");
        const errorList = document.getElementById("error-list");
        const nodeStatusContainer = document.getElementById("node-status-container");
        const debugConsole = document.getElementById("debug-console");
        const clearDebugBtn = document.getElementById("clear-debug-btn");

        // Tambahkan pesan debug
        function addDebugMessage(message, type = "info") {
            const timestamp = new Date().toLocaleTimeString();
            const messageElement = document.createElement("p");
            
            let icon = "";
            switch(type) {
                case "error": icon = "<i class='fas fa-times-circle'></i>"; break;
                case "warning": icon = "<i class='fas fa-exclamation-triangle'></i>"; break;
                case "success": icon = "<i class='fas fa-check-circle'></i>"; break;
                default: icon = "<i class='fas fa-info-circle'></i>";
            }
            
            messageElement.innerHTML = `<span class="${type}">${icon} [${timestamp}] ${message}</span>`;
            debugConsole.appendChild(messageElement);
            debugConsole.scrollTop = debugConsole.scrollHeight;
            
            // Simpan pesan terakhir (max 100 pesan)
            debugMessages.push({timestamp, message, type});
            if (debugMessages.length > 100) {
                debugMessages.shift();
            }
        }

        // Clear debug messages
        function clearDebugMessages() {
            debugConsole.innerHTML = "<p>Debug console cleared.</p>";
            debugMessages = [];
        }

        // Update error panel
        function updateErrorPanel() {
            if (errors.length === 0) {
                errorPanel.style.display = "none";
                return;
            }
            
            errorPanel.style.display = "block";
            errorList.innerHTML = "";
            
            errors.forEach(error => {
                const errorItem = document.createElement("div");
                errorItem.className = "error-item";
                errorItem.textContent = error;
                errorList.appendChild(errorItem);
            });
        }

        // Check for sensor anomalies
        function checkSensorAnomalies() {
            errors = [];
            
            // Check each node
            Object.keys(NODE_CONFIG).forEach(nodeId => {
                const node = NODE_CONFIG[nodeId];
                const status = nodeStatus[nodeId] || { lastUpdate: 0 };
                
                // Check if node is offline
                const timeSinceUpdate = Date.now() - status.lastUpdate;
                if (timeSinceUpdate > node.updateInterval * 1.5) { // 50% tolerance
                    const minutesOffline = Math.floor(timeSinceUpdate / 60000);
                    const errorMsg = `${node.name} (ID: ${nodeId}) has been offline for ${minutesOffline} minutes`;
                    errors.push(errorMsg);
                    addDebugMessage(errorMsg, "error");
                }
                
                // Check for missing sensors
                if (status.sensors) {
                    node.expectedSensors.forEach(sensor => {
                        if (!status.sensors.includes(sensor)) {
                            const errorMsg = `${node.name} (ID: ${nodeId}) is missing ${sensor} data`;
                            errors.push(errorMsg);
                            addDebugMessage(errorMsg, "warning");
                        }
                    });
                }
            });
            
            // Check sensor values against thresholds
            Object.keys(sensorData).forEach(sensorType => {
                if (sensorData[sensorType].length > 0) {
                    const lastValue = sensorData[sensorType][sensorData[sensorType].length - 1].value;
                    const thresholds = SENSOR_THRESHOLDS[sensorType];
                    
                    if (lastValue < thresholds.min || lastValue > thresholds.max) {
                        const errorMsg = `${sensorType} value ${lastValue} is outside operational range (${thresholds.min}-${thresholds.max})`;
                        errors.push(errorMsg);
                        addDebugMessage(errorMsg, "error");
                    } else if (lastValue < thresholds.warning.min || lastValue > thresholds.warning.max) {
                        const warningMsg = `${sensorType} value ${lastValue} is outside recommended range (${thresholds.warning.min}-${thresholds.warning.max})`;
                        errors.push(warningMsg);
                        addDebugMessage(warningMsg, "warning");
                    }
                }
            });
            
            updateErrorPanel();
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

        // Update status node
        function updateNodeStatus() {
            nodeStatusContainer.innerHTML = "";
            
            Object.keys(NODE_CONFIG).forEach(nodeId => {
                const node = NODE_CONFIG[nodeId];
                const status = nodeStatus[nodeId] || { lastUpdate: 0 };
                const timeSinceUpdate = Date.now() - status.lastUpdate;
                const isOnline = timeSinceUpdate < node.updateInterval * 1.5; // 50% tolerance
                
                const nodeElement = document.createElement("div");
                nodeElement.className = "node-status";
                
                const nodeIdElement = document.createElement("span");
                nodeIdElement.className = "node-id";
                nodeIdElement.textContent = `${node.name}:`;
                
                const statusIndicator = document.createElement("span");
                statusIndicator.className = `status-indicator ${isOnline ? "online" : "offline"}`;
                
                const statusText = document.createElement("span");
                if (isOnline) {
                    const minutesAgo = Math.floor(timeSinceUpdate / 60000);
                    statusText.textContent = `Online (updated ${minutesAgo > 0 ? `${minutesAgo} min ago` : "just now"})`;
                } else {
                    const minutesOffline = Math.floor(timeSinceUpdate / 60000);
                    statusText.textContent = `Offline (${minutesOffline} min)`;
                }
                
                nodeElement.appendChild(nodeIdElement);
                nodeElement.appendChild(statusIndicator);
                nodeElement.appendChild(statusText);
                nodeStatusContainer.appendChild(nodeElement);
            });
            
            document.getElementById("node-update-time").textContent = `Last checked: ${formatTime(Date.now())}`;
        }

        // Inisialisasi Chart
        function initializeChart() {
            const ctx = document.getElementById('combinedChart').getContext('2d');
            combinedChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Temperature (°C)',
                            data: [],
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Humidity (%)',
                            data: [],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Distance (cm)',
                            data: [],
                            borderColor: '#2ecc71',
                            backgroundColor: 'rgba(46, 204, 113, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y2'
                        },
                        {
                            label: 'Light (Lux)',
                            data: [],
                            borderColor: '#f39c12',
                            backgroundColor: 'rgba(243, 156, 18, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y3'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Temperature (°C)'
                            },
                            min: -10,
                            max: 50
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Humidity (%)'
                            },
                            min: 0,
                            max: 100,
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        y2: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Distance (cm)'
                            },
                            min: 0,
                            max: 400,
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        y3: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Light (Lux)'
                            },
                            min: 0,
                            max: 1023,
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time'
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
                                        label += context.dataset.label.includes('Temperature') ? '°C' : 
                                                 context.dataset.label.includes('Humidity') ? '%' :
                                                 context.dataset.label.includes('Distance') ? 'cm' : '';
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        // Update sensor card
        function updateSensorCard(sensorType, value, timestamp) {
            const valueElement = document.getElementById(`${sensorType}-value`);
            const timeElement = document.getElementById(`${sensorType}-time`);
            const statusElement = document.getElementById(`${sensorType}-status`);
            
            if (valueElement && timeElement && statusElement) {
                // Update value
                valueElement.innerHTML = `${value.toFixed(1)} <span class="sensor-unit">${
                    sensorType === 'temp' ? '°C' : 
                    sensorType === 'humidity' ? '%' : 
                    sensorType === 'ultrasonic' ? 'cm' : 'lux'
                }</span>`;
                
                // Check for warnings
                const thresholds = SENSOR_THRESHOLDS[
                    sensorType === 'temp' ? 'temperature' : 
                    sensorType === 'ultrasonic' ? 'distance' : 
                    sensorType === 'ldr' ? 'ldr' : 'humidity'
                ];
                
                if (value < thresholds.min || value > thresholds.max) {
                    valueElement.classList.add("danger");
                    statusElement.innerHTML = `<span class="status-indicator error"></span> <span>Error: Out of range</span>`;
                } else if (value < thresholds.warning.min || value > thresholds.warning.max) {
                    valueElement.classList.add("warning");
                    statusElement.innerHTML = `<span class="status-indicator warning"></span> <span>Warning: Near limits</span>`;
                } else {
                    valueElement.classList.remove("danger", "warning");
                    statusElement.innerHTML = `<span class="status-indicator online"></span> <span>Normal</span>`;
                }
                
                // Update timestamp
                timeElement.textContent = `Last reading: ${formatTime(timestamp)}`;
            }
        }

        // Update chart with new data
        function updateChart() {
            // Get the last 20 data points for each sensor
            const tempData = sensorData.temperature.slice(-20);
            const humidityData = sensorData.humidity.slice(-20);
            const distanceData = sensorData.distance.slice(-20);
            const ldrData = sensorData.ldr.slice(-20);
            
            // Prepare labels (use timestamps from temperature data as reference)
            const labels = tempData.map(item => formatTimeShort(item.timestamp));
            
            // Update chart data
            combinedChart.data.labels = labels;
            combinedChart.data.datasets[0].data = tempData.map(item => item.value);
            combinedChart.data.datasets[1].data = humidityData.map(item => item.value);
            combinedChart.data.datasets[2].data = distanceData.map(item => item.value);
            combinedChart.data.datasets[3].data = ldrData.map(item => item.value);
            
            combinedChart.update();
            document.getElementById("chart-update-time").textContent =`Last updated: ${formatTime(Date.now())}`;
            }

            // Process incoming sensor data
            function processSensorData(snapshot) {
                const data = snapshot.val();
                
                if (data) {
                    Object.entries(data).forEach(([key, value]) => {
                        const timestamp = value.timestamp || Date.now();
                        const nodeId = value.node_id || "unknown";
                        
                        // Initialize node status if not exists
                        if (!nodeStatus[nodeId]) {
                            nodeStatus[nodeId] = { 
                                lastUpdate: timestamp,
                                sensors: []
                            };
                        }
                        
                        // Update node status
                        nodeStatus[nodeId].lastUpdate = timestamp;
                        
                        // Process each sensor type
                        if (value.temperature !== undefined) {
                            sensorData.temperature.push({
                                value: value.temperature,
                                timestamp: timestamp,
                                nodeId: nodeId
                            });
                            
                            updateSensorCard('temp', value.temperature, timestamp);
                            
                            if (!nodeStatus[nodeId].sensors.includes('temperature')) {
                                nodeStatus[nodeId].sensors.push('temperature');
                            }
                        }
                        
                        if (value.humidity !== undefined) {
                            sensorData.humidity.push({
                                value: value.humidity,
                                timestamp: timestamp,
                                nodeId: nodeId
                            });
                            
                            updateSensorCard('humidity', value.humidity, timestamp);
                            
                            if (!nodeStatus[nodeId].sensors.includes('humidity')) {
                                nodeStatus[nodeId].sensors.push('humidity');
                            }
                        }
                        
                        if (value.distance_cm !== undefined) {
                            sensorData.distance.push({
                                value: value.distance_cm,
                                timestamp: timestamp,
                                nodeId: nodeId
                            });
                            
                            updateSensorCard('ultrasonic', value.distance_cm, timestamp);
                            
                            if (!nodeStatus[nodeId].sensors.includes('distance')) {
                                nodeStatus[nodeId].sensors.push('distance');
                            }
                        }
                        
                        if (value.ldr_value !== undefined) {
                            sensorData.ldr.push({
                                value: value.ldr_value,
                                timestamp: timestamp,
                                nodeId: nodeId
                            });
                            
                            updateSensorCard('ldr', value.ldr_value, timestamp);
                            
                            if (!nodeStatus[nodeId].sensors.includes('ldr')) {
                                nodeStatus[nodeId].sensors.push('ldr');
                            }
                        }
                        
                        // Add debug message
                        addDebugMessage(`Received data from Node ${nodeId} at ${formatTime(timestamp)}`, "info");
                    });
                    
                    // Update chart and status
                    updateChart();
                    updateNodeStatus();
                    checkSensorAnomalies();
                }
            }

            // Inisialisasi
            function init() {
                initializeChart();
                addDebugMessage("System initialized. Connecting to Firebase...");
                
                // Set user info
                document.getElementById("user-avatar").textContent = currentUser.username.charAt(0).toUpperCase();
                document.getElementById("username-display").textContent = currentUser.username;
                
                // Event listeners
                document.getElementById("toggle-sidebar").addEventListener('click', () => {
                    document.getElementById("sidebar").classList.toggle('active');
                    document.getElementById("main-content").classList.toggle('active');
                });
                
                
                document.getElementById("view-profile-btn").addEventListener('click', (e) => {
                    e.preventDefault(); 
                    document.getElementById("profile-username").textContent = currentUser.username;
                    document.getElementById("profile-email").textContent = currentUser.email;
                    document.getElementById("profile-created").textContent = currentUser.created_at.split(' ')[0]; // Hanya tanggal saja
                    document.getElementById("profile-modal").style.display = 'flex'; 
                });
                                
                document.getElementById("logout-btn").addEventListener('click', () => {
                    alert('You have been logged out');
                    window.location.href = '/login';
                });
                
                document.getElementById("close-modal").addEventListener('click', () => {
                    document.getElementById("profile-modal").style.display = 'none';
                });
                
                document.getElementById("close-modal-btn").addEventListener('click', () => {
                    document.getElementById("profile-modal").style.display = 'none';
                });
                
                clearDebugBtn.addEventListener('click', clearDebugMessages);
                
                // Close modal ketika klik di luar modal
                window.addEventListener('click', (e) => {
                    if (e.target === document.getElementById("profile-modal")) {
                        document.getElementById("profile-modal").style.display = 'none';
                    }
                });
                
                // Mendeteksi status koneksi Firebase
                database.ref(".info/connected").on("value", (snapshot) => {
                    const connected = snapshot.val() === true;
                    addDebugMessage(connected ? "Connected to Firebase Realtime Database" : "Disconnected from Firebase", 
                                connected ? "success" : "error");
                });

                // Mendapatkan data dari Firebase
                database.ref("sensor_data").orderByChild("timestamp").limitToLast(20).on("value", processSensorData);
                
                // Periodically check node status
                setInterval(() => {
                    updateNodeStatus();
                    checkSensorAnomalies();
                }, 30000); // Check every 30 seconds
            }

            // Jalankan inisialisasi saat DOM siap
            document.addEventListener('DOMContentLoaded', init);
        </script>
    </body>
    </html>