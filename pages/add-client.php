<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Client – Work & Tax Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #0b0e14; color: #e9ecef; padding: 20px; }
        .form-container { max-width: 700px; margin: 40px auto; background: #1a1f2b; padding: 30px; border-radius: 12px; }
        .form-label { color: #8892a0; font-weight: 500; }
        .form-control, .form-select {
            background: #0b0e14;
            color: #e9ecef;
            border: 1px solid #2d3748;
        }
        .form-control:focus, .form-select:focus {
            background: #0b0e14;
            color: #e9ecef;
            border-color: #0d6efd;
            box-shadow: none;
        }
        .btn-back { background: #2d3748; border: none; color: #e9ecef; }
        .btn-back:hover { background: #3d4a5f; color: #fff; }
        .alert { border-radius: 8px; }
        .title { font-size: 24px; font-weight: 600; color: #fff; }
        .subtitle { font-size: 14px; color: #8892a0; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="title"><i class="fa fa-user-plus me-2"></i> Add New Client</div>
                <div class="subtitle">Fill in the details below</div>
            </div>
            <a href="/reports/client-work-tracker/pages/dashboard.php" class="btn btn-back">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Alert messages -->
        <div id="alertContainer"></div>

        <!-- Form -->
        <form id="addClientForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="client_name" class="form-label">Client Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="client_name" name="client_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pan" class="form-label">PAN</label>
                    <input type="text" class="form-control" id="pan" name="pan" placeholder="ABCDE1234F">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gstin" class="form-label">GSTIN</label>
                    <input type="text" class="form-control" id="gstin" name="gstin" placeholder="22AAAAA0000A1Z5">
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa fa-save"></i> Save Client
                </button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>
</div>

<script>
    const BASE_URL = '/reports/client-work-tracker';

    document.getElementById('addClientForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        // Basic validation
        if (!data.client_name.trim()) {
            showAlert('Client Name is required.', 'danger');
            return;
        }

        // Disable button and show loading
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';

        try {
            const response = await fetch(`${BASE_URL}/api/save_client.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showAlert('Client added successfully!', 'success');
                this.reset(); // clear form
                // Optionally redirect after a moment
                setTimeout(() => {
                    window.location.href = `${BASE_URL}/pages/dashboard.php`;
                }, 1500);
            } else {
                showAlert(result.message || 'Failed to save client.', 'danger');
            }
        } catch (error) {
            console.error('Save error:', error);
            showAlert('Network error – please try again.', 'danger');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa fa-save"></i> Save Client';
        }
    });

    function showAlert(message, type = 'info') {
        const container = document.getElementById('alertContainer');
        container.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) alert.remove();
        }, 5000);
    }
</script>

<!-- Bootstrap JS for alert dismissal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>