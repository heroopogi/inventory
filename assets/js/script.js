// Function to load medicines into the table
function loadMedicines() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#medicineTable')) {
        $('#medicineTable').DataTable().destroy();
    }

    $('#medicineTable').DataTable({
        ajax: {
            url: 'includes/get_medicines.php',
            dataSrc: function(json) {
                if (json.status === 'success') {
                    return json.data;
                } else {
                    console.error('Error loading medicines:', json.message);
                    return [];
                }
            }
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'generic_name' },
            { data: 'category' },
            { data: 'quantity' },
            { 
                data: 'price',
                render: function(data) {
                    return '₱' + data;
                }
            },
            { data: 'expiry_date' },
            {
                data: 'status',
                render: function(data) {
                    let badgeClass = 'bg-success';
                    if (data === 'Low Stock') badgeClass = 'bg-warning';
                    if (data === 'Out of Stock') badgeClass = 'bg-danger';
                    if (data === 'Expiring Soon') badgeClass = 'bg-danger';
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'id',
                render: function(data) {
                    return `
                        <button onclick="editMedicine(${data})" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteMedicine(${data})" class="btn btn-sm btn-danger ms-1">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        responsive: true,
        order: [[1, 'asc']],
        language: {
            emptyTable: "No medicines found in the inventory",
            zeroRecords: "No matching medicines found",
            loadingRecords: "Loading medicines..."
        },
        processing: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}

// Function to handle form submission for adding medicine
$(document).on('submit', '#addMedicineForm', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitButton = $(this).find('button[type="submit"]');
    const originalText = submitButton.html();
    submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Adding...');
    
    $.ajax({
        url: 'includes/add_medicine.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('Medicine added successfully!');
                window.location.href = 'read.html';
            } else {
                alert('Error: ' + response.message);
                submitButton.prop('disabled', false).html(originalText);
            }
        },
        error: function(xhr, status, error) {
            console.error('XHR Status:', status);
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            alert('An error occurred while adding the medicine. Please check the console for details.');
            submitButton.prop('disabled', false).html(originalText);
        }
    });
});

// Function to handle form submission for updating medicine
$('#updateMedicineForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'includes/update_medicine.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.status === 'success') {
                alert('Medicine updated successfully!');
                window.location.href = 'read.html';
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while updating the medicine.');
        }
    });
});


// Function to handle medicine deletion from delete.html form
$('#deleteMedicineForm').submit(function(e) {
    e.preventDefault();
    if ($('#confirmDelete').val() !== 'DELETE') {
        alert('Please type "DELETE" to confirm.');
        return;
    }
    $.ajax({
        url: 'includes/delete_medicine.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('Medicine deleted successfully!');
                window.location.href = 'read.html';
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while deleting the medicine.');
            console.error(xhr.responseText);
        }
    });
});

// Function to handle medicine deletion from DataTable row
function deleteMedicine(id) {
    if (!confirm('Are you sure you want to delete this medicine? This action cannot be undone.')) {
        return;
    }
    $.ajax({
        url: 'includes/delete_medicine.php',
        method: 'POST',
        data: { medicineId: id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('Medicine deleted successfully!');
                // Reload DataTable
                if ($.fn.DataTable.isDataTable('#medicineTable')) {
                    $('#medicineTable').DataTable().ajax.reload(null, false);
                } else {
                    window.location.reload();
                }
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while deleting the medicine.');
            console.error(xhr.responseText);
        }
    });
}

// Function to load medicine details for editing
function editMedicine(id) {
    window.location.href = `update.html?id=${id}`;
}

// Function to load medicine details when update page loads
function loadMedicineDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    
    if (id) {
        $.ajax({
            url: 'includes/get_medicine.php',
            method: 'GET',
            data: { id: id },
            success: function(response) {
                if (response.status === 'success') {
                    const medicine = response.data;
                    $('#medicineId').val(medicine.id);
                    $('#medicineName').val(medicine.name);
                    $('#genericName').val(medicine.generic_name);
                    $('#category').val(medicine.category);
                    $('#quantity').val(medicine.quantity);
                    $('#price').val(medicine.price);
                    $('#expiryDate').val(medicine.expiry_date);
                    $('#description').val(medicine.description);
                }
            }
        });
    }
}

// Function to export table to Excel
function exportToExcel() {
    const table = $('#medicineTable').DataTable();
    const data = table.data().toArray();
    
    let csv = 'ID,Medicine Name,Generic Name,Category,Quantity,Price,Expiry Date,Status\n';
    
    data.forEach(row => {
        csv += `${row.id},"${row.name}","${row.generic_name}","${row.category}",${row.quantity},${row.price},"${row.expiry_date}","${row.status}"\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'medicine_inventory.csv';
    link.click();
}

// Function to print inventory
function printInventory() {
    window.print();
}

// Initialize DataTable when the read page loads
if (window.location.pathname.includes('read.html')) {
    $(document).ready(function() {
        loadMedicines();
    });
}

// Load medicine details when update page loads
if (window.location.pathname.includes('update.html')) {
    $(document).ready(function() {
        loadMedicineDetails();
    });
}