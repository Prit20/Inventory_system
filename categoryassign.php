<style>
/* Make the 2 rightmost columns sticky */
#categoryTable th:nth-last-child(2),
#categoryTable td:nth-last-child(2),
#categoryTable th:last-child,
#categoryTable td:last-child {
  position: sticky;
  right: 80px; /* Adjust based on your table width */
  background:rgb(232, 233, 235); /* dark background for header, match your theme */
  z-index: 2; /* Make sure header cells appear on top */
}

#categoryTable th:last-child,
#categoryTable td:last-child {
  right: 0; /* Last column stick to the right */
  background:rgb(232, 233, 235);
}
</style>

<!-- <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100"> -->
<?php include '../includes/sidebar.php'; ?>

<!-- <div class="ml-64 flex flex-col h-screen"> -->
  <?php include '../includes/header.php'; ?>

  <!-- <main class="flex-1 p-6 overflow-y-auto"> -->
  <div class="main-content p-4 transition-all duration-300 dark:bg-gray-900 dark:text-gray-100">

    <h2 class="mb-4 text-2xl font-semibold">Category Assign</h2>
    <div class="table-responsive">
      <table id="categoryTable" class="table table-bordered table-hover nowrap" style="width:100%">
        <thead class="table-dark">
          <tr>
            <th>Sr No</th>
            <th>Location</th>
            <th>PO No</th>
            <th>Invoice No</th>
            <th>Party Name</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Basic Rate</th>
            <th>Remarks</th>
            <th>Category Assign</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
           <?php include '../includes/footer.php'; ?>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


<script>
$(document).ready(function() {
  const table = $('#categoryTable').DataTable({
    "ajax": "../backend/db_categoryassign.php",
    "columns": [
      { "data": "sr_no" },
      { "data": "location" },
      { "data": "po_no" },
      { "data": "invoice_no" },
      { "data": "party_name" },
      { "data": "item" },
      { "data": "qty" },
      { "data": "basic_rate" },
      { "data": "p_remark" },
      {
        "data": null,
        "render": function(data, type, row, meta) {
          return `
            <select class="form-select form-select-sm category-dropdown" style="border-radius: 5px; border: 1px solid black; color: black;" data-sr="${row.sr_no}">
              <option value="">Select Category</option>
            </select>
          `;
        }
      },
      {
        "data": null,
        "render": function(data, type, row, meta) {
          return `<button 
            class="save-category-btn" 
            style="
              background-color: #007bff; 
              color: white; 
              border: none; 
              border-radius: 5px; 
              padding: 4px 12px; 
              font-size: 0.875rem; 
              cursor: pointer;
            " 
            data-sr="${row.sr_no}">
            Save
          </button>`;
        }
      }
    ],
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'pdf'],
    responsive: true,
    scrollX: true,
    initComplete: function(settings, json) {
      loadCategoryDropdowns();
    }
  });

  // Populate dropdowns
  function loadCategoryDropdowns() {
    $.ajax({
      url: '../backend/db_categoryassign.php',
      method: 'POST',
      data: { action: 'getcategories' },
      dataType: 'json',
      success: function(response) {
        $('.category-dropdown').each(function() {
          let dropdown = $(this);
          let currentVal = dropdown.val();
          dropdown.empty().append('<option value="">Select Category</option>');
          response.forEach(category => {
            dropdown.append(`<option value="${category.id}">${category.name}</option>`);
          });
          dropdown.val(currentVal);
        });
      }
    });
  }

  // Save button handler
  $(document).on('click', '.save-category-btn', function() {
    let row1 = $(this).closest('tr');
    let srNo = $(this).data('sr');
    let categoryId = $(`.category-dropdown[data-sr="${srNo}"]`).val();
    let categoryName = $(`.category-dropdown[data-sr="${srNo}"] option:selected`).text();

    if (categoryId === "") {
      alert("Please select a category.");
      return;
    }

    // Get full row data
    let rowData = table.row(row1).data();

    $.ajax({
      url: '../backend/db_categoryassign.php',
      method: 'POST',
      data: {
        action: 'savecategoryassign',
        sr_no: rowData.sr_no,
        location: rowData.location,
        po_no: rowData.po_no,
        invoice_no: rowData.invoice_no,
        party_name: rowData.party_name,
        item: rowData.item,
        qty: rowData.qty,
        basic_rate: rowData.basic_rate,
        remarks: rowData.p_remark,
        category_id: categoryId,
        category_name: categoryName
      },
      success: function(response) {
        if (response.trim() === 'success') {
          // Fade out and remove row
          row1.fadeOut(300, function () {
            table.row(row1).remove().draw(false);
          });
        } else {
          alert("Error saving category assignment.");
        }
      },
      error: function() {
        alert("Error saving category assignment.");
      }
    });
  });
});
</script>