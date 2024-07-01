<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: index.html");
    exit();
}

// Check if the user has the required role
if ($_SESSION['role'] !== 'Facility Head') {
    // Redirect to a page indicating unauthorized access
    header("Location: index.html");
    exit();
}

// Fetch reservations from the database for the current user
include_once 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user ID from the session data
$user_id = $_SESSION['user_id'];


// Fetch reservations with status "In Review"
$review_reservation_sql = "SELECT * FROM reservations WHERE reservation_status = 'Pending' ORDER BY created_at DESC";
$review_reservation_result = $conn->query($review_reservation_sql);


$reservations = [];
$reservation_sql = "SELECT * FROM reservations WHERE reservation_status = 'Reserved'";
$reservation_result = $conn->query($reservation_sql);

if ($reservation_result->num_rows > 0) {
    while ($row = $reservation_result->fetch_assoc()) {
        $reservation = [
            'title' => $row['facility_name'],
            'start' => $row['reservation_date'] . 'T' . $row['start_time'],
            'end' => $row['reservation_date'] . 'T' . $row['end_time'],
            'backgroundColor' => '#f00', // Color for reserved events
            'userDepartment' => $row['user_department'],
            // Add more details as needed
        ];
        $reservations[] = $reservation;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLV: RESERVA</title>
    <link rel="stylesheet" href="css/style.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($reservations); ?>,
                eventContent: function(info) {
                    console.log(info.event);
                    return info.event.title;
                }
            });
            calendar.render();
        });
    </script>
</head>
<body>
    <div class="flex h-screen bg-gray-100">

        <div id="sidebar-container">
            <?php include 'sidebar.php'; ?>
        </div>
        
        <div class="flex flex-col flex-1">
            <!-- Header -->
            <header class="bg-white shadow-lg">
                <!-- Header content -->
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Facility Reservations</h2>
                    <!-- Add any header content here -->
                </div>
            </header>
            <!-- Main content area -->
            <main class="flex-1 p-4 overflow-y-auto">
                <div class="flex h-full flex-row items-center space-x-4">
                    <div class="flex h-full w-8/12 flex-col space-y-2">
                        <div class="flex justify-between items-center">
                            <input type="text" id="search" placeholder="Search..." class="border rounded-md py-2 px-4" onkeyup="filterReservations()">
                        </div>
                    <div id="eventsList" class="bg-white shadow overflow-y-auto sm:rounded-lg flex-1">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="reservationTableBody">
                                <?php
                                // Display reservations
                                if ($review_reservation_result->num_rows > 0) {
                                    // Reset pointer to the beginning of the result set
                                    mysqli_data_seek($review_reservation_result, 0);
                                    
                                    while ($row = $review_reservation_result->fetch_assoc()) {
                                        // Add unique IDs to each row
                                        $reservationId = $row["id"];
                                        echo '<tr class="reservation-item" data-reservation-id="' . $reservationId . '">';
                                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($row["facility_name"]) . '</td>';
                                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($row["reservation_date"]) . '</td>';
                                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($row["start_time"]) . '</td>';
                                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($row["end_time"]) . '</td>';
                                        echo '<td class="px-6 py-4 whitespace-nowrap italic">' . htmlspecialchars($row["reservation_status"]) . '</td>';
                                        echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($row["purpose"]) . '</td>';
                                        
                                        // Conditionally show accept and decline buttons only for "In Review" reservations
                                        if ($row["reservation_status"] === "Pending") {
                                            echo '<td class="px-6 py-4 whitespace-nowrap">';
                                            echo '<div class="flex">';
                                            echo '<button onclick="declineReservation(' . $reservationId . ')" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">Decline</button>';
                                            echo '<button onclick="acceptReservation(' . $reservationId . ')" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-green-600">Accept</button>';
                                            echo '</div>';
                                            echo '</td>';
                                        } else {
                                            echo '<td class="px-6 py-4 whitespace-nowrap"></td>';
                                        }
                                        
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="px-6 py-4 text-center">No reservations found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        
                    </div>
                    <!-- Add pagination controls above the table -->
                    <div class="flex justify-center items-center space-x-2 mt-4">
                        <button onclick="prevPage()" class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">&lt;&lt;</button>
                        <span id="pagination" class="flex items-center space-x-2"></span>
                        <button onclick="nextPage()" class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">&gt;&gt;</button>
                    </div>
                    </div>

                    <div class="h-full border-l border-gray-300"></div>

                    <div class="flex flex-col h-full w-1/3 space-y-4">
                        <div class="h-1/2">
                            <div id='calendar' class="h-full p-1 text-xs bg-white border border-gray-200 rounded-lg shadow-lg"></div>
                        </div>
                        <!-- Events/Reserved Dates -->
                        <div class="flex flex-col space-y-4 overflow-y-auto">
                            <div>
                                <h2 class="font-semibold">Events/Reserved Dates</h2>
                            </div>
                            <div id="eventsList" class="bg-white shadow overflow-y-auto sm:rounded-lg flex-1">
                                <ul id="eventsListUl" class="divide-y divide-gray-200 flex flex-col">
                                    <?php
                                    // Display reservations with status "In Review"
                                    if ($review_reservation_result->num_rows > 0) {
                                        while ($row = $review_reservation_result->fetch_assoc()) {
                                            // Add unique IDs to each list item
                                            $reservationId = $row["id"];
                                            echo '<li class="p-4 border-gray-200 border-b reservation-item" data-reservation-id="' . $reservationId . '">';
                                            echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
                                            echo '<h3 class="text-gray-600 mb-2">' . htmlspecialchars($row["user_department"]) . '</h3>';
                                            echo '<p class="text-gray-600 mb-2">Reservation Date: ' . htmlspecialchars($row["reservation_date"]) . '</p>';
                                            echo '<p class="text-gray-600 mb-2">Start Time: ' . htmlspecialchars($row["start_time"]) . ' - End Time: ' . htmlspecialchars($row["end_time"]) . '</p>';
                                            echo '<p class="italic">' . htmlspecialchars($row["reservation_status"]) . '</p>';
                                            // Add accept and decline buttons
                                            echo '<div class="flex justify-between mt-2">';
                                            echo '<button onclick="declineReservation(' . $reservationId . ')" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">Decline</button>';
                                            echo '<button onclick="acceptReservation(' . $reservationId . ')" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-green-600">Accept</button>';
                                            echo '</div>';
                                            echo '</li>';
                                        }
                                    } else {
                                        echo '<li>No reservations found</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- HTML for custom confirmation dialog -->
    <div id="custom-dialog" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
            <img class="w-36 mb-4" src="img/undraw_warning_re_eoyh.svg" alt="">
            <p class="text-lg text-slate-700 font-semibold mb-4">Are you sure you want to logout?</p>
            <div class="flex justify-center mt-5">
                <button onclick="cancelLogout()" class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                <button onclick="confirmLogout()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-500">Logout</button>
            </div>
        </div>
    </div>
    <!-- Modal markup -->
<div id="reservationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
        <div id="modalContent" class="font-bold text-xl text-blue-600 z-10">
            <!-- Reservation details will be dynamically added here -->
        </div>
        <div class="flex justify-center mt-5">
            <button onclick="closeModal()" class="mr-4 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>

<!-- Reservation Modal -->
<div id="reservationsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md">
        <div class="font-bold text-xl mb-4">Reservation Details</div>
        <div id="modalContent" class="text-gray-800">
            <p><strong>Facility Name:</strong> <span id="facilityName"></span></p>
            <p><strong>Reservation Date:</strong> <span id="reservationDate"></span></p>
            <p><strong>Start Time:</strong> <span id="startTime"></span></p>
            <p><strong>End Time:</strong> <span id="endTime"></span></p>
            <!-- Add more details as needed -->
        </div>
        <div class="flex justify-center mt-5">
            <button onclick="closeModal()" class="mr-4 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>

<!-- Modal for rejection reason-->
<div id="rejectionReasonForm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-md shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold">Enter reason for rejection:</h2>
            </div>
            <div>
                <form id="reservationForm" class="space-y-4">
                    <div class="flex flex-col space-y-2">
                        <textarea id="rejectionReason" name="rejectionReason" rows="3" class="border border-gray-300 rounded-md p-2" required></textarea>
                    </div>
                </form>
                <button onclick="hideSuccessModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Cancel</button>
                <button id="confirmRejectionButton" class="px-4 py-2 mt-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Okay</button>
            </div>
    </div>
</div>
<!-- HTML for custom confirmation dialog -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
        <p id="confirmationMessage" class="text-lg text-slate-700 font-semibold mb-4"></p>
        <div class="flex justify-center mt-5">
            <button onclick="cancelAction()" class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
            <button onclick="confirmAction()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Confirm</button>
        </div>
    </div>
</div>

<!-- HTML for success modal -->
<div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
        <p id="successMessage" class="text-lg text-slate-700 font-semibold mb-4"></p>
        <div class="flex justify-center mt-5">
            <button onclick="hideSuccessModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">OK</button>
        </div>
    </div>
</div>

<!-- HTML for error message modal -->
<div id="errorMessageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
        <p id="errorMessageContent" class="text-lg text-red-700 font-semibold mb-4"></p>
        <div class="flex justify-center mt-5">
            <button onclick="hideErrorMessage()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">OK</button>
        </div>
    </div>
</div>


    <script src="scripts/logout.js"></script>
    <script src="scripts/functions.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: <?php echo json_encode($reservations); ?>,
        eventDidMount: function(info) {
            // Manipulate the event element's style here
            info.el.style.position = 'absolute';
            info.el.style.left = '0';
            info.el.style.right = '0';
            info.el.style.top = '0';
            info.el.style.bottom = '0';
        },
        eventContent: function(info) {
            return {
                html: `
                    <div style="position: relative; z-index: 1;">
                        ${info.event.title}<br>
                        ${info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true, seconds: false })}
                        ${info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true, seconds: false })}
                    </div>
                `
            };
        }
    });

    // Event listener for clicking on FullCalendar events
    calendar.on('eventClick', function(info) {
        console.log('Clicked event:', info.event);
        // Call the showModal function and pass the event details
        showModal(info.event);
    });

    calendar.render();
});


// Function to show modal with reservation details
function showModal(event) {
    console.log('Showing modal for event:', event);
    const modal = document.getElementById('reservationsModal');
    const modalContent = modal.querySelector('#modalContent');

    // Convert start and end dates to local time
    const startDate = new Date(event.start);
    const endDate = new Date(event.end);

    // Format options for date and time
    const dateOptions = {
        weekday: 'short', 
        year: 'numeric', 
        month: 'numeric', 
        day: 'numeric',
    };

    const timeOptions = {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    };

    modalContent.innerHTML = `
        <p><strong>Facility Name:</strong> ${event.title}</p>
        <p><strong>Reservation Date:</strong> ${startDate.toLocaleDateString(undefined, dateOptions)}</p>
        <p><strong>Start Time:</strong> ${startDate.toLocaleTimeString(undefined, timeOptions)}</p>
        <p><strong>End Time:</strong> ${endDate.toLocaleTimeString(undefined, timeOptions)}</p>
        <!-- Add more details as needed -->
    `;

    modal.classList.remove('hidden');
}


// Function to close the modal
function closeModal() {
    const modal = document.getElementById('reservationsModal');
    modal.classList.add('hidden');
}

// Function to show success modal
function showSuccessModal(message) {
    const successModal = document.getElementById('successModal');
    const successMessage = document.getElementById('successMessage');
    successMessage.innerText = message;
    successModal.classList.remove('hidden');
}

// Function to hide success modal
function hideSuccessModal() {
    const successModal = document.getElementById('successModal');
    successModal.classList.add('hidden');
    location.reload(); // Reload the page
}

// Function to show confirmation modal
function showConfirmation(message, callback) {
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmationMessage = document.getElementById('confirmationMessage');
    confirmationMessage.innerText = message;
    confirmationModal.classList.remove('hidden');
    confirmActionCallback = callback;
}

// Function to hide confirmation modal
function hideConfirmation() {
    const confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.classList.add('hidden');
}

// Function to handle confirmation action
function confirmAction() {
    hideConfirmation();
    if (confirmActionCallback) {
        confirmActionCallback();
    }
}

// Function to handle cancellation of action
function cancelAction() {
    hideConfirmation();
}

let confirmActionCallback;

// Function to show error message in a modal
function showErrorMessage(message) {
    const errorMessageModal = document.getElementById('errorMessageModal');
    const errorMessageContent = document.getElementById('errorMessageContent');
    
    // Set the error message content
    errorMessageContent.innerText = message;
    
    // Show the error message modal
    errorMessageModal.classList.remove('hidden');
}

// Function to hide the error message modal
function hideErrorMessage() {
    const errorMessageModal = document.getElementById('errorMessageModal');
    errorMessageModal.classList.add('hidden');
}


// Function to handle accepting reservation
function acceptReservation(reservationId) {
    console.log('Accept reservation:', reservationId);
    fetch('check_reservation_overlap.php?id=' + reservationId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            console.error('Error checking reservation overlap:', data.error);
            showErrorMessage('Error checking reservation overlap. Please try again.');
        } else if (data.overlap) {
            showErrorMessage('There is a reservation conflict. Please select another time slot.');
        } else {
            showConfirmation('Are you sure you want to accept this reservation?', function() {
                fetch('http://localhost/PLVCapstone/build/update_reservation_status.php?id=' + reservationId + '&status=Reserved', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error('Error accepting reservation:', data.error);
                        showModal({ title: 'Error accepting reservation' });
                    } else {
                        showSuccessModal('Reservation accepted successfully.');
                    }
                })
                .catch(error => {
                    console.error('Error accepting reservation:', error);
                    showModal({ title: 'Error accepting reservation' });
                });
            });
        }
    })
    .catch(error => {
        console.error('Error checking reservation overlap:', error);
        showErrorMessage('Error checking reservation overlap. Please try again.');
    });
}


function declineReservation(reservationId) {
    console.log("Decline button clicked");
    // Show rejection reason form
    const rejectionReasonForm = document.getElementById('rejectionReasonForm');
    rejectionReasonForm.classList.remove('hidden');

    // Handle confirmation after inputting rejection reason
    const confirmButton = document.getElementById('confirmRejectionButton');
    confirmButton.onclick = function() {
        // Get rejection reason from the form
        const rejectionReason = document.getElementById('rejectionReason').value;

        // Show confirmation message before declining
        showConfirmation('Are you sure you want to decline this reservation?', function() {
            // Send rejection reason and decline reservation
            fetch('update_reservation_status.php?id=' + reservationId + '&status=Declined', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reason: rejectionReason
                })
            })
            .then(response => {
                if (response.ok) {
                    // Reload the page after successful decline
                    location.reload();
                } else {
                    // Handle error
                    showModal({ title: 'Error declining reservation' });
                }
            });
        });
    };
}

// Pagination
let currentPage = 1;
const pageSize = 10; // Number of reservations per page

function renderPagination(totalPages) {
    const paginationElement = document.getElementById('pagination');
    paginationElement.innerHTML = '';

    // Create pagination controls
    if (currentPage > 1) {
        paginationElement.innerHTML += `<button onclick="prevPage()" class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">&lt;&lt;</button>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationElement.innerHTML += `<span class="px-2 py-1 bg-blue-500 text-white rounded-md">${i}</span>`;
        } else {
            paginationElement.innerHTML += `<button onclick="goToPage(${i})" class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">${i}</button>`;
        }
    }

    if (currentPage < totalPages) {
        paginationElement.innerHTML += `<button onclick="nextPage()" class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">&gt;&gt;</button>`;
    }
}

function goToPage(page) {
    currentPage = page;
    filterReservations();
}

function nextPage() {
    currentPage++;
    filterReservations();
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        filterReservations();
    }
}

// Filter
function filterReservations() {
    const filterValue = document.getElementById('search').value.toUpperCase();
    const rows = document.querySelectorAll('.reservation-item');
    const totalPages = Math.ceil(rows.length / pageSize);

    renderPagination(totalPages);

    rows.forEach((row, index) => {
        const facilityName = row.querySelector('td:first-child').textContent.toUpperCase();
        if (facilityName.indexOf(filterValue) > -1) {
            if (index >= (currentPage - 1) * pageSize && index < currentPage * pageSize) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        } else {
            row.style.display = 'none';
        }
    });
}

// Initial pagination rendering
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.reservation-item');
    const totalPages = Math.ceil(rows.length / pageSize);
    renderPagination(totalPages);
});
</script>


</body>
</html>
