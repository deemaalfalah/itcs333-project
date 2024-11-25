<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <link rel="stylesheet" href="styles/book_room.css">
</head>
<body>
    <div class="container">
        <h1>Room Booking</h1>
        
        <!-- Booking Type Selection -->
        <div class="booking-type">
            <label for="booking-type">Select Booking Type:</label>
            <select id="booking-type" onchange="toggleBookingFields()">
                <option value="single">Single Booking</option>
                <option value="daily">Daily Booking</option>
            </select>
        </div>

        <!-- Single Booking Form -->
        <div id="single-booking" class="booking-form">
            <h2>Single Booking</h2>
            <label for="single-date">Select Date:</label>
            <input type="date" id="single-date" onchange="updateTimeIntervals()">
            <br>

            <label for="time-slot">Select Time Slot:</label>
            <select id="time-slot">
                <!-- Time slots will be populated by JavaScript based on the selected date -->
            </select>
            <br>

            <label for="equipment">Select Equipment:</label>
            <select id="equipment">
                <option value="lab">Lab</option>
                <option value="datashow">Data Show</option>
                <option value="smartboard">Smartboard</option>
            </select>
        </div>

        <!-- Daily Booking Form -->
        <div id="daily-booking" class="booking-form" style="display: none;">
            <h2>Daily Booking</h2>

            <!-- Semester Dropdown -->
            <label for="semester">Semester:</label>
            <select id="semester" onchange="updateSemesterDates()">
                <option value="first_semester">First Semester</option>
                <option value="second_semester">Second Semester</option>
                <option value="summer_semester">Summer Semester</option>
            </select>
            <br>

            <!-- Start and End Date -->
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" readonly>
            <br>

            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" readonly>
            <br>

            <!-- Days Dropdown -->
            <label for="days">Choose Days:</label>
            <select id="days" onchange="updateDailyTimeIntervals()">
                <option value="MW">MW</option>
                <option value="UTH">UTH</option>
            </select>
            <br>

            <!-- Time Slot Dropdown -->
            <label for="time-slot">Select Time Slot:</label>
            <select id="time-slot" >
                <!-- Time slots will be populated dynamically -->
            </select>
            <br>

            <!-- Equipment Selection -->
            <label for="daily-equipment">Select Equipment:</label>
            <select id="daily-equipment">
                <option value="lab">Lab</option>
                <option value="datashow">Data Show</option>
                <option value="smartboard">Smartboard</option>
            </select>
        </div>
    </div>

    <script>
    // Toggle between Single and Daily Booking
    function toggleBookingFields() {
        const bookingType = document.getElementById('booking-type').value;
        const singleBooking = document.getElementById('single-booking');
        const dailyBooking = document.getElementById('daily-booking');

        if (bookingType === 'single') {
            singleBooking.style.display = 'block';
            dailyBooking.style.display = 'none';
        } else {
            singleBooking.style.display = 'none';
            dailyBooking.style.display = 'block';
        }
    }

    // Update time intervals for daily booking based on the selected days (MW or UTH)
    function updateDailyTimeIntervals() {
        const daysSelect = document.getElementById('days').value;  // MW or UTH
        const timeSlot = document.getElementById('time-slot');      // Time slot dropdown
        let timeIntervals = [];

        // Time intervals logic for MW (Monday-Wednesday) and UTH (Sunday-Tuesday-Thursday)
        if (daysSelect === 'MW') {
            timeIntervals = [
                '8:00 AM - 9:15 AM', 
                '9:30 AM - 10:45 AM', 
                '11:00 AM - 12:15 PM', 
                '12:30 PM - 1:45 PM'
            ];
        } else if (daysSelect === 'UTH') {
            timeIntervals = [
                '8:00 AM - 8:50 AM', 
                '9:00 AM - 9:50 AM', 
                '10:00 AM - 10:50 AM', 
                '11:00 AM - 11:50 AM'
            ];
        }

        // Clear existing options
        timeSlot.innerHTML = '';  // Clear the dropdown before adding new options

        // Add the new options to the dropdown
        timeIntervals.forEach(time => {
            const option = document.createElement('option');
            option.value = time;
            option.textContent = time;
            timeSlot.appendChild(option);
        });

        // Optional: Select the first option as default
        if (timeIntervals.length > 0) {
            timeSlot.selectedIndex = 0;  // Select the first time slot by default
        }
    }

    // Update the start and end dates based on the selected semester
    function updateSemesterDates() {
        const semesterSelect = document.getElementById('semester').value;
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');

        let startDate, endDate;

        if (semesterSelect === 'first_semester') {
            startDate = '2024-09-08'; // 8 September 2024
            endDate = '2024-12-19';   // 19 December 2024
        } else if (semesterSelect === 'second_semester') {
            startDate = '2025-02-05'; // 5 February 2025
            endDate = '2025-05-18';   // 18 May 2025
        } else if (semesterSelect === 'summer_semester') {
            startDate = '2025-07-01'; // 1 July 2025
            endDate = '2025-08-24';   // 24 August 2025
        }

        // Set the start and end date inputs
        startDateInput.value = startDate;
        endDateInput.value = endDate;
    }

    // Initialize page with Single Booking selected by default
    document.addEventListener('DOMContentLoaded', function () {
        toggleBookingFields();
        updateSemesterDates(); // Set initial semester dates
    });
</script>


</body>
</html>
