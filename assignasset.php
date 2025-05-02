<?php
include '../config/db.php';
// $conn = connectDB('Inventory_System');
?>

<?php include '../includes/sidebar.php'; ?>
<?php include '../includes/header.php'; ?>
<div class="main-content p-4 transition-all duration-300 dark:bg-gray-900 dark:text-gray-100">

    <!-- User Assignment Details -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
        <h2 class="text-2xl font-bold mb-4">User Assignment Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- <div>
                <label class="block mb-1 text-sm font-medium">Assign to User</label>
                <select id="userSelect" class="w-full p-2 border rounded focus:outline-none focus:ring">
                      <label>Assign to User</label>
                        <select id="employee_id" class="form-control" style="width: 100%"></select>

                    <option value="">Select User</option>
                    <?php
                    // $userQuery = "SELECT employee_id, user_name FROM [user]";
                    // $userResult = sqlsrv_query($connWorkBook, $userQuery);
                    // while($row = sqlsrv_fetch_array($userResult, SQLSRV_FETCH_ASSOC)){
                    //     // echo "<option value='".$row['employee_id']."' data-username='".$row['user_name']."'>".$row['user_name']."</option>";

                    //     echo "<option value='".$row['employee_id']."'>".$row['user_name']."</option>";
                    // }
                    ?>

            </div> -->
            <!-- User Searchable -->
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Assign to User</label>
                <input type="text" id="user-search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    placeholder="Search user..." autocomplete="off">
                <ul id="user-dropdown"
                    class="absolute w-[26%] z-10 mt-1 bg-white border border-gray-300 rounded-md shadow-lg dark:bg-gray-800 dark:border-gray-600 hidden">
                    <!-- Dynamic dropdown items will appear here -->
                </ul>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Plant Name</label>
                <select id="plantName" class="w-full p-2 border rounded focus:outline-none focus:ring">
                    <option value="">Select Plant</option>
                    <option value="Halol">Halol</option>
                    <option value="696">696</option>
                    <option value="Vadodara">Vadodara</option>
                    <option value="Maswad">Maswad</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Assignment Date</label>
                <input type="date" id="assignment_date" class="w-full p-2 border rounded" max="<?= date('Y-m-d') ?>"
                    value="<?= date('Y-m-d') ?>" />
                <!-- <input type="date" id="assignDate" class="w-full p-2 border rounded"
                    value="<?php echo date('Y-m-d'); ?>" readonly> -->
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Employee ID</label>
                <input type="text" id="employeeId" class="w-full p-2 border rounded bg-gray-100" readonly>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Designation</label>
                <input type="text" id="designation" class="w-full p-2 border rounded bg-gray-100" readonly>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Department</label>
                <input type="text" id="department" class="w-full p-2 border rounded bg-gray-100" readonly>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Description</label>
                <input type="text" id="description" class="w-full p-2 border rounded bg-gray-100">
            </div>
        </div>
    </div>

    <!-- Asset Assignment Details -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
        <h2 class="text-2xl font-bold mb-4">Asset Assignment Details</h2>

        <div id="assetAssignmentSection" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block mb-1 text-sm font-medium">Device Category</label>
                <select class="deviceCategory w-full p-2 border rounded focus:outline-none focus:ring">
                    <option value="">Select Category</option>
                    <?php
                    $catQuery = "SELECT DISTINCT Category_Name FROM SerialNumberAssignment";
                    $catResult = sqlsrv_query($connInventory, $catQuery);
                    while($row = sqlsrv_fetch_array($catResult, SQLSRV_FETCH_ASSOC)){
                        echo "<option value='".$row['Category_Name']."'>".$row['Category_Name']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Company Name</label>
                <select class="companyName w-full p-2 border rounded focus:outline-none focus:ring" disabled>
                    <option value="">Select Company</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Serial Number</label>
                <select class="serialNumber w-full p-2 border rounded focus:outline-none focus:ring" disabled>
                    <option value="">Select Serial</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Remark</label>
                <input type="text" class="remark w-full p-2 border rounded" disabled>
            </div>

            <div>
                <button type="button"
                    class="addRow bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" disabled>
                    Add
                </button>
            </div>
        </div>

        <div id="addedAssets" class="mt-6 space-y-4"></div>

    </div>

    <!-- Save and Cancel -->
    <div class="flex justify-end gap-4">
        <button id="saveAssignment" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
            Save
        </button>
        <a href="stock.php" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
            Cancel
        </a>
    </div>

    <!-- User Asset History -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8 mt-8">
        <h2 class="text-2xl font-bold mb-4">User Asset History</h2>

        <div class="overflow-x-auto">
            <table id="userAssetHistory" class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="px-4 py-2">Assignment ID</th>
                        <th class="px-4 py-2">User ID</th>
                        <th class="px-4 py-2">Device Category</th>
                        <th class="px-4 py-2">Company Name</th>
                        <th class="px-4 py-2">Serial Number</th>
                        <th class="px-4 py-2">Assigned Date</th>
                        <th class="px-4 py-2">Return Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody id="userAssetHistoryBody">
                    <tr>
                        <td colspan="8" class="text-center py-4">Select a user to view asset history.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


</div>
<?php include('../includes/footer.php'); ?>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#user-search").on("keyup", function() {
        const searchTerm = $(this).val().trim();

        // If search term is empty, hide dropdown
        if (!searchTerm) {
            $("#user-dropdown").empty().hide();
            return;
        }

        // Fetch matching users via AJAX
        $.ajax({
            url: "../backend/db_assignasset.php", // Replace with your backend endpoint
            method: "POST",
            data: {
                search: searchTerm
            },
            success: function(response) {
                const users = JSON.parse(response);
                // console.log(users);
                let dropdownItems = ``;

                if (users.length > 0) {
                    users.forEach((user) => {
                        dropdownItems += `
                                    <li 
                                        class="px-4 py-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600" 
                                        data-user="${user.user_name}" 
                                        data-employee-id="${user.employee_id}" 
                                        data-department="${user.department}" 
                                        data-designation="${user.designation}"
                                    >
                                        ${user.user_name} (${user.employee_id}) - ${user.department}
                                    </li>
                                `;
                    });
                } else {
                    dropdownItems = `
                                <li class="px-4 py-2 text-gray-500 dark:text-gray-400">No users found</li>
                            `;
                }

                $("#user-dropdown").html(dropdownItems).show();
            },
            error: function() {
                $("#user-dropdown").html(`
                            <li class="px-4 py-2 text-red-500">Error fetching users</li>
                        `).show();
            },
        });
    });


    // Handle dropdown item click
    $(document).on("click", "#user-dropdown li", function() {
         var userId = $(this).data("employee-id");

        const selectedUser = $(this).data("user");
        const employeeId = $(this).data("employee-id");
        const department = $(this).data("department");
        const designation = $(this).data("designation");
        console.log(employeeId);

        // fetchAndDisplayAssets(employeeId); // Replace 3067 with the actual employee ID


        // Set values in input fields
        $("#user-search").val(selectedUser);
        $("#employeeId").val(employeeId);
        $("#designation").val(designation);
        $("#department").val(department);


    // Enable fields
    $(".companyName").prop("disabled", false);
    $(".serialNumber").prop("disabled", false);
    $(".remark").prop("disabled", false);
    $(".addRow").prop("disabled", false);

    // Load user asset history
    // fetchUserAssetHistory(employeeId);
      // 🔥 New: Fetch User Asset History
      $.ajax({
                        url: '../backend/db_assignasset.php',
                        method: 'POST',
                        data: {
                            action: 'fetch_user_assets',
                            userId: userId
                        },
                        success: function(historyResponse) {
                            $('#userAssetHistoryBody').html(
                                historyResponse);
                        }
                    });
        // Hide dropdown
        $("#user-dropdown").hide();
    });

    // Hide dropdown if clicked outside
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#user-search, #user-dropdown").length) {
            $("#user-dropdown").hide();
        }
    });

   
    // Auto fill employee info
    $('#userSelect').change(function() {
        var userId = $(this).val();
        if (userId != '') {
            $.ajax({
                url: '../backend/db_assignasset.php',
                method: 'POST',
                data: {
                    action: 'fetch_user',
                    userId: userId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#employeeId').val(data.employee_id);
                    $('#designation').val(data.designation);
                    $('#department').val(data.department);
                    $('#description').val(data.description);

                    // Enable asset assignment fields
                    $('.companyName, .serialNumber, .remark, .addRow')
                        .prop('disabled', false);

                    // 🔥 New: Fetch User Asset History
                    $.ajax({
                        url: '../backend/db_assignasset.php',
                        method: 'POST',
                        data: {
                            action: 'fetch_user_assets',
                            userId: userId
                        },
                        success: function(historyResponse) {
                            $('#userAssetHistoryBody').html(
                                historyResponse);
                        }
                    });
                }
            });
        }
    });

    // Load Company names based on category
    $(document).on('change', '.deviceCategory', function() {
        var category = $(this).val();
        var companyDropdown = $(this).closest('#assetAssignmentSection').find('.companyName');
        $.ajax({
            url: '../backend/db_assignasset.php',
            method: 'POST',
            data: {
                action: 'fetch_company',
                category: category
            },
            success: function(response) {
                companyDropdown.html(response);
            }
        });
    });

    // Load Serial numbers based on company
    $(document).on('change', '.companyName', function() {
        var company = $(this).val();
        var serialDropdown = $(this).closest('#assetAssignmentSection').find('.serialNumber');
        $.ajax({
            url: '../backend/db_assignasset.php',
            method: 'POST',
            data: {
                action: 'fetch_serial',
                company: company
            },
            success: function(response) {
                serialDropdown.html(response);
            }
        });
    });
    $(document).on("click", ".addRow", function () {
    const rowHtml = `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 asset-row">
            <div>
                <select class="deviceCategory w-full p-2 border rounded">
                    <option value="">Select Category</option>
                    <?php
                    $catQuery = "SELECT DISTINCT Category_Name FROM SerialNumberAssignment";
                    $catResult = sqlsrv_query($connInventory, $catQuery);
                    while($row = sqlsrv_fetch_array($catResult, SQLSRV_FETCH_ASSOC)){
                        echo "<option value='".$row['Category_Name']."'>".$row['Category_Name']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <select class="companyName w-full p-2 border rounded">
                    <option value="">Select Company</option>
                </select>
            </div>
            <div>
                <select class="serialNumber w-full p-2 border rounded">
                    <option value="">Select Serial</option>
                </select>
            </div>
            <div>
                <input type="text" class="remark w-full p-2 border rounded" placeholder="Enter remark">
            </div>
            <div>
                <button type="button" class="removeRow bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">
                    Remove
                </button>
            </div>
        </div>`;
    
    $("#addedAssets").append(rowHtml);
});
$(document).on("click", ".removeRow", function () {
    $(this).closest(".asset-row").remove();
});

   
document.getElementById('saveAssignment').addEventListener('click', function (e) {
    e.preventDefault();

    const userSearch = document.getElementById('user-search').value.trim();
    const plantName = document.getElementById('plantName').value.trim();
    const assignmentDate = document.getElementById('assignment_date').value;
    const employeeId = document.getElementById('employeeId').value.trim();
    const designation = document.getElementById('designation').value.trim();
    const department = document.getElementById('department').value.trim();
    const description = document.getElementById('description').value.trim();

    const assets = [];
    // Log to check if rows exist
    console.log(document.querySelectorAll('#addedAssets .asset-row'));
    document.querySelectorAll('#addedAssets .asset-row').forEach(row => {
        const deviceCategory = row.querySelector('.deviceCategory').value;
const companyName = row.querySelector('.companyName').value;
const serialNumber = row.querySelector('.serialNumber').value;
const remark = row.querySelector('.remark').value;

   // Check for empty input values
   console.log('Device Category:', deviceCategory);
        console.log('Company Name:', companyName);
        console.log('Serial Number:', serialNumber);
        console.log('Remark:', remark);
        // Only add to array if there is valid data
        if (deviceCategory && companyName && serialNumber && remark) {
            assets.push({
                deviceCategory,
                companyName,
                serialNumber,
                remark
            });
        }
    });
 console.log(assets); return false;
    $.ajax({
    url: '../backend/db_assignasset.php',
    method: 'POST',
    data: {
        action: 'save_assignment',
        user_name: userSearch,
        plant: plantName,
        assignment_date: assignmentDate,
        employee_id: employeeId,
        designation: designation,
        department: department,
        description: description,
        assets: JSON.stringify(assets)
    },
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // <- this!
    processData: true, // <- this ensures form-style key=value pairs
        success: function (response) {
            try {
                const res = JSON.parse(response);
                console.log('Parsed response:', res);
                if (res.status === 'success') {
                    alert(res.message);
                } else {
                    alert('Failed to save: ' + res.message);
                }
            } catch (err) {
                console.error('JSON parse error:', err);
                alert('Unexpected server response');
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.log('Response Text:', xhr.responseText);
            alert('AJAX request failed.');
        }
    });
});




    function showToast(message, type) {
        var toast = $('<div></div>').text(message)
            .addClass('fixed bottom-5 right-5 p-4 rounded shadow-lg text-white z-50')
            .css({
                'background-color': type === 'success' ? '#16a34a' : '#dc2626', // green or red
                'opacity': '0',
                'transition': 'opacity 0.5s'
            });

        $('body').append(toast);
        setTimeout(function() {
            toast.css('opacity', '1');
        }, 100); // Fade In
        setTimeout(function() {
            toast.css('opacity', '0');
            setTimeout(function() {
                toast.remove();
            }, 500);
        }, 2500); // Auto-hide after 2.5s
    }

    // $('#saveAssignment').click(function() {
    //     const payload = {
    //         action: 'save_asset_assignment',
    //         userId: $('#employee_id').val(),
    //         assignDate: $('#assignment_date').val(),
    //         assets: []
    //     };

    //     $('#addedAssets .grid').each(function() {
    //         payload.assets.push({
    //             category: $(this).find('[name="device_category[]"]').val(),
    //             company: $(this).find('[name="company_name[]"]').val(),
    //             serial: $(this).find('[name="serial_number[]"]').val(),
    //             remark: $(this).find('[name="remark[]"]').val()
    //         });
    //     });

    //     $.post('../backend/db_assignasset.php', payload, function(response) {
    //         alert(response); // Or use a nicer toast/message
    //         location.reload(); // Optional
    //     });
    // });
});
</script>