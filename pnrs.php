<?php
// pnrs.php - PNR Status Check Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNR Status | TravelBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #f97316;
            --primary-light: #fb923c;
            --secondary: #ff5e00;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f7fafc;
            --white: #ffffff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: var(--white);
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .back-btn {
            background: none;
            border: none;
            color: var(--white);
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        /* Main Content Styles */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            flex: 1;
        }
        
        .card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        h1 {
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* PNR Form Styles */
        .pnr-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .pnr-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .btn {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: var(--white);
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Results Section */
        .results {
            display: none;
            margin-top: 30px;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .status-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .status-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        
        .status-text {
            font-weight: 600;
            font-size: 18px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .detail-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 5px;
        }
        
        .detail-label {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-weight: 600;
        }
        
        /* Passengers Table */
        .passengers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .passengers-table th {
            background: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-size: 14px;
            color: var(--text-light);
        }
        
        .passengers-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .passenger-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        
        /* Loading and Error States */
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-message {
            color: var(--danger);
            padding: 15px;
            background: #fee2e2;
            border-radius: 5px;
            margin-top: 20px;
            display: none;
        }
        
        /* Footer Styles */
        footer {
            background-color: var(--secondary);
            color: var(--white);
            padding: 30px 0;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .copyright {
            margin-top: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="logo">travelbuddy</div>
            <button class="back-btn" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <h1>
                <i class="fas fa-ticket-alt"></i>
                PNR Status Check
            </h1>
            <p>Enter your 10-digit PNR number to check your train ticket status</p>
            
            <div class="pnr-form">
                <input type="text" id="pnrNumber" class="pnr-input" placeholder="e.g. 8524877966" maxlength="10" inputmode="numeric">
                <button id="checkPnrBtn" class="btn">
                    <i class="fas fa-search"></i> Check Status
                </button>
            </div>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Fetching PNR details...</p>
            </div>
            
            <div class="error-message" id="errorMessage"></div>
            
            <div class="results" id="results">
                <div class="status-header">
                    <i class="fas fa-circle status-icon" id="statusIcon"></i>
                    <span class="status-text" id="statusText">Status</span>
                    <span class="status-badge" id="statusBadge"></span>
                </div>
                
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Train Number</div>
                        <div class="detail-value" id="trainNumber">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Train Name</div>
                        <div class="detail-value" id="trainName">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Journey Date</div>
                        <div class="detail-value" id="journeyDate">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Class</div>
                        <div class="detail-value" id="journeyClass">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">From Station</div>
                        <div class="detail-value" id="fromStation">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">To Station</div>
                        <div class="detail-value" id="toStation">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Chart Status</div>
                        <div class="detail-value" id="chartStatus">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Booking Date</div>
                        <div class="detail-value" id="bookingDate">-</div>
                    </div>
                </div>
                
                <h3><i class="fas fa-users"></i> Passenger Details</h3>
                <table class="passengers-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Booking Status</th>
                            <th>Current Status</th>
                            <th>Coach/Seat</th>
                        </tr>
                    </thead>
                    <tbody id="passengerTableBody">
                        <!-- Passenger rows will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="logo" style="font-size: 28px; margin-bottom: 15px;">travel<span style="color: white;">buddy</span></div>
            <p>Your smart travel companion for every journey</p>
            <div class="copyright">
                &copy; 2025 TravelBuddy. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // API Configuration
        const RAPIDAPI_KEY = '3d86c2d6aemshdc9a7b41679cbaep166b94jsnf3b7c3893b4f';
        const RAPIDAPI_HOST = 'irctc-indian-railway-pnr-status.p.rapidapi.com';
        const API_URL = 'https://irctc-indian-railway-pnr-status.p.rapidapi.com/getPNRStatus/';
        
        // DOM Elements
        const pnrInput = document.getElementById('pnrNumber');
        const checkBtn = document.getElementById('checkPnrBtn');
        const loadingElement = document.getElementById('loading');
        const errorElement = document.getElementById('errorMessage');
        const resultsElement = document.getElementById('results');
        const passengerTableBody = document.getElementById('passengerTableBody');
        
        // Status configuration
        const statusConfig = {
            'CNF': { 
                color: '#10b981', 
                badge: { text: 'CONFIRMED', bg: '#d1fae5', textColor: '#065f46' } 
            },
            'RAC': { 
                color: '#f59e0b', 
                badge: { text: 'RAC', bg: '#fef3c7', textColor: '#92400e' } 
            },
            'WL': { 
                color: '#f97316', 
                badge: { text: 'WAITLIST', bg: '#ffedd5', textColor: '#9a3412' } 
            },
            'CAN': { 
                color: '#ef4444', 
                badge: { text: 'CANCELLED', bg: '#fee2e2', textColor: '#991b1b' } 
            },
            'PQWL': { 
                color: '#f59e0b', 
                badge: { text: 'POOL Q WL', bg: '#fef3c7', textColor: '#92400e' } 
            },
            'default': { 
                color: '#3b82f6', 
                badge: { text: '', bg: '', textColor: '' } 
            }
        };
        
        // Check PNR Status
        async function checkPnrStatus() {
            const pnrNumber = pnrInput.value.trim();
            
            // Validate PNR
            if (!pnrNumber || pnrNumber.length !== 10 || !/^\d+$/.test(pnrNumber)) {
                showError('Please enter a valid 10-digit PNR number');
                return;
            }
            
            // Reset UI
            hideError();
            resultsElement.style.display = 'none';
            loadingElement.style.display = 'block';
            checkBtn.disabled = true;
            
            try {
                const response = await fetch(`${API_URL}${pnrNumber}`, {
                    method: 'GET',
                    headers: {
                        'x-rapidapi-host': RAPIDAPI_HOST,
                        'x-rapidapi-key': RAPIDAPI_KEY
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`API request failed with status ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to fetch PNR details');
                }
                
                displayPnrDetails(data.data);
            } catch (error) {
                console.error('Error fetching PNR status:', error);
                showError(error.message || 'Failed to fetch PNR details. Please try again.');
            } finally {
                loadingElement.style.display = 'none';
                checkBtn.disabled = false;
            }
        }
        
        // Display PNR Details
        function displayPnrDetails(data) {
            // Update basic info
            document.getElementById('trainNumber').textContent = data.trainNumber;
            document.getElementById('trainName').textContent = data.trainName;
            document.getElementById('journeyDate').textContent = formatDate(data.dateOfJourney);
            document.getElementById('fromStation').textContent = data.sourceStation;
            document.getElementById('toStation').textContent = data.destinationStation;
            document.getElementById('journeyClass').textContent = data.journeyClass;
            document.getElementById('chartStatus').textContent = data.chartStatus;
            document.getElementById('bookingDate').textContent = formatDate(data.bookingDate);
            
            // Set status
            const firstPassenger = data.passengerList[0];
            const currentStatus = firstPassenger?.currentStatus || firstPassenger?.bookingStatus || '';
            const statusInfo = statusConfig[currentStatus] || statusConfig.default;
            
            // Update status display
            document.getElementById('statusIcon').style.color = statusInfo.color;
            document.getElementById('statusText').textContent = getStatusText(firstPassenger);
            
            const statusBadge = document.getElementById('statusBadge');
            statusBadge.textContent = statusInfo.badge.text;
            statusBadge.style.backgroundColor = statusInfo.badge.bg;
            statusBadge.style.color = statusInfo.badge.textColor;
            
            // Update passenger table
            passengerTableBody.innerHTML = '';
            data.passengerList.forEach(passenger => {
                const row = document.createElement('tr');
                
                const status = passenger.currentStatus || passenger.bookingStatus;
                const statusInfo = statusConfig[status] || statusConfig.default;
                
                row.innerHTML = `
                    <td>${passenger.passengerSerialNumber}</td>
                    <td>${passenger.bookingStatusDetails || '-'}</td>
                    <td>
                        <span class="passenger-status" style="background-color: ${statusInfo.badge.bg}; color: ${statusInfo.badge.textColor}">
                            ${passenger.currentStatusDetails || passenger.bookingStatusDetails || '-'}
                        </span>
                    </td>
                    <td>${passenger.currentCoachId || passenger.bookingCoachId || '-'}/${passenger.currentBerthNo || passenger.bookingBerthNo || '-'}</td>
                `;
                
                passengerTableBody.appendChild(row);
            });
            
            // Show results
            resultsElement.style.display = 'block';
        }
        
        // Helper function to get status text
        function getStatusText(passenger) {
            if (!passenger) return 'Status not available';
            
            if (passenger.currentStatus === 'CAN') {
                return 'Cancelled';
            }
            
            if (passenger.currentStatus === 'CNF') {
                return `Confirmed (${passenger.currentCoachId || passenger.bookingCoachId} - ${passenger.currentBerthNo || passenger.bookingBerthNo})`;
            }
            
            return passenger.currentStatusDetails || passenger.bookingStatusDetails || 'Status not available';
        }
        
        // Helper function to format date
        function formatDate(dateString) {
            if (!dateString) return '-';
            
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Error handling
        function showError(message) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        
        function hideError() {
            errorElement.style.display = 'none';
        }
        
        // Event Listeners
        checkBtn.addEventListener('click', checkPnrStatus);
        
        pnrInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                checkPnrStatus();
            }
        });
    </script>
</body>
</html>