<?php
require_once "config.php";
//Query to fetch facility data from the database
$sql = "SELECT * FROM facilities";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLV: RESERVA</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <!-- Component Start -->
        <div class="flex flex-col items-center w-16 h-full overflow-hidden text-blue-200 bg-plv-blue rounded-r-lg">
            <a class="flex items-center justify-center mt-3" href="#">
                <img class="w-8 h-8" src="img/PLV Logo.png" alt="Logo">
            </a>
            <div class="flex flex-col items-center mt-3 border-t border-gray-700">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="adminDashboard.html" title="Dashboard">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="accManagement.php" title="Account Management">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>               
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="facilityMngmnt.php" title="Facility Management">
                    <img src="img/building-icon.png" alt="Facility Management Icon" class="w-6 h-6">
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="roomMngmnt.html" title="Room Management">
                    <img src="img/icons8-open-door-24.png" alt="Room Management Icon" class="w-6 h-6">
                </a>
                
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="adminCalendar.html" title="Events Calendar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>                      
                </a>
            </div>
            <div class="flex flex-col items-center mt-2 border-t border-gray-700">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#" title="Account Settings">
                    <svg class="w-6 h-6 stroke-current"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </a>
                <a class="relative flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                      </svg>
                      
                    <span class="absolute top-0 left-0 w-2 h-2 mt-2 ml-2 bg-plv-highlight rounded-full"></span>
                </a>
            </div>
                <!-- Trigger the custom confirmation dialog -->
                <a class="flex items-center justify-center w-16 h-16 mt-auto bg-persian-blue hover:bg-plv-highlight" onclick="showCustomDialog()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                </a>
        </div>
        <!-- Component End  -->
        <!-- Content area -->
        <div class="flex flex-col flex-1">
            <!-- Header -->
            <header class="bg-white shadow-lg">
                <!-- Header content -->
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Facility Management</h2>
                    <!-- Add any header content here -->
                </div>
            </header>
            <!-- Main content area -->
            <main class="p-4 border-2 border-red-600 h-screen">
                <div class="flex items-center justify-between p-1 rounded-md">
                    <div class="flex items-center">
                        <input type="text" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400" placeholder="Search...">
                        <button class="ml-2 px-4 py-2 bg-plv-blue text-white rounded-md flex items-center justify-center hover:bg-plv-highlight focus:outline-none focus:ring focus:ring-plv-highlight">
                            <img src="img/icons8-search-50.png" alt="Search Icon" class="w-4 h-4 mr-2">
                            Search
                        </button>
                    </div>
                    <div>
                        <button onclick="showFacilityForm()" class="ml-auto px-4 py-2 bg-plv-blue text-white rounded-md flex items-center justify-center hover:bg-plv-highlight focus:outline-none focus:ring focus:ring-plv-highlight">
                            <img src="img/icons8-plus-24.png" alt="Add Account Icon" class="w-4 h-4 mr-2">
                            Add Facility
                        </button>
                    </div>
                </div>               
            
                <!--Facility List -->
                <div class="mt-2">
                    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10">
                        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Facility List</h2>
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Facility Name</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Building</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="facilityList" class="bg-white divide-y divide-gray-200">
                                <?php
                                // Output user data dynamically
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='px-3 py-4 whitespace-nowrap'>" . $row["facility_name"]  ."</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["building"] . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["status"] . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>
                                                <div class='flex items-center'>
                                                    <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editFacility(" . $row["id"] . ")'>
                                                        Edit
                                                    </button>
                                                    <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteFacility(" . $row["id"] . ")'>
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No data found</td></tr>";
                                }
                                ?>    
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>            
        </div>
    </div>
    <!--Facility Form-->
    <div id="facility-form" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white py-4 px-6 rounded-md">
            <!-- Modal Header with Close Button -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold">Add Facility</h2>
                <button onclick="closeFacilityForm()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form method="POST" action="create_facility.php" >
                <div class="mb-4">
                    <div class="">
                        <label for="facilityName" class="block text-gray-700 font-semibold mb-2">Facility Name:</label>
                        <input type="text" id="facilityName" name="facilityName" required
                            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div class="flex mb-4 gap-2">
                    <div class="w-1/2">
                        <label for="building" class="block text-gray-700 font-semibold mb-2">Building:</label>
                        <select id="building" name="building" required
                            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500">
                            <option value="SC/MAIN">SC/MAIN</option>
                            <option value="CABA">CABA</option>
                            <option value="CAS">CAS</option>
                            <option value="CEIT">CEIT</option>
                            <option value="COED">COED</option>
                            <option value="CPA">CPA</option>
                        </select>
                    </div>
                    <div class="w-1/2">
                        <label for="status" class="block text-gray-700 font-semibold mb-2">Status:</label>
                        <select id="status" name="status" required
                            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500">
                            <option value="Available">Available</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="descri" class="block text-gray-700 font-semibold mb-2">Description:</label>
                    <textarea id="descri" name="descri" rows="4" required
                        class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500"></textarea>
                </div>
                <div class="mt-6">
                    <button type="submit" onclick="SubmitFacilityForm()"
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Submit</button>
                </div>
            </form>
        </div>
    </div>
                <!-- Success Modal -->
                <div id="successModal" class="fixed z-10 inset-0 bg-black bg-opacity-50 overflow-y-auto hidden">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Background Overlay -->
                        <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
                        <!-- Modal Content -->
                        <div class="bg-white rounded-lg p-8 max-w-sm mx-auto relative">
                            <!-- Green Check Icon -->
                            <img src="img/undraw_completing_re_i7ap.svg" alt="Success Image" class="mx-auto mb-4 w-16 h-20">
                            <!-- Modal Header -->
                            <h2 class="text-lg font-semibold mb-4">Facility Added Successfully!</h2>
                            <!-- Close Button -->
                            <button onclick="closeSuccessModal()" class="absolute top-0 right-0 mt-2 mr-2 focus:outline-none">
                                <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
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
    <script src="scripts/logout.js"></script>
    <script src="scripts/functions.js"></script>
            <!-- JavaScript for edit and delete actions -->
            <script> 
                document.addEventListener("DOMContentLoaded", function () {
                    // Check if the success parameter is present in the URL
                    var urlParams = new URLSearchParams(window.location.search);
                    var success = urlParams.has('success') && urlParams.get('success') === 'true';
    
                    if (success) {
                        var successModal = document.getElementById('successModal');
                        // Show modal
                        successModal.classList.remove('hidden');
                    }
                });
    
                function closeSuccessModal() {
                    var successModal = document.getElementById('successModal');
                    // Hide modal
                    successModal.classList.add('hidden');
                    
                    // Reset success parameter to false
                    var urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('success', 'false');
                    var newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.replaceState({}, '', newUrl);
                }
            </script>
</body>
</html>