<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Client Work & Tax Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #0b0e14; color: #e9ecef; padding: 20px; }
        .top-box { background: #1a1f2b; padding: 20px; border-radius: 12px; }
        .title { font-size: 24px; font-weight: 600; color: #fff; }
        .subtitle { font-size: 14px; color: #8892a0; }
        .stat-card { background: #1a1f2b; padding: 20px; border-radius: 12px; position: relative; overflow: hidden; }
        .stat-title { font-size: 14px; color: #8892a0; }
        .stat-value { font-size: 28px; font-weight: 700; }
        .stat-icon { position: absolute; right: 15px; top: 15px; font-size: 24px; color: #2d3748; }
        .search-box { background: #1a1f2b; padding: 15px; border-radius: 12px; margin: 20px 0; }
        .table-dark { background: #1a1f2b; border-radius: 12px; overflow: hidden; }
        .table-dark th { border-bottom: 1px solid #2d3748; color: #8892a0; font-weight: 500; }
        .btn-primary { background: #0d6efd; border: none; }
        hr { border-color: #2d3748; }
        .table td, .table th { padding: 12px 15px; vertical-align: middle; }
        .form-control, .form-select { background: #0b0e14; color: #e9ecef; border: 1px solid #2d3748; }
        .form-control:focus, .form-select:focus { background: #0b0e14; color: #e9ecef; border-color: #0d6efd; box-shadow: none; }
        .modal-content { background: #1a1f2b; color: #e9ecef; }
        .modal-header { border-bottom: 1px solid #2d3748; }
        .modal-footer { border-top: 1px solid #2d3748; }
    </style>
</head>
<body>
<div class="container-fluid">

    <!-- Header -->
    <div class="top-box">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="title"><i class="fa fa-folder-open me-2"></i> Client Work & Tax Tracker</div>
                <div class="subtitle">Daily Operations for ITR, GST, Website & Online Services</div>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clientModal">
                    <i class="fa fa-user-plus"></i> Add Client
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mt-4">
        <div class="col-lg-2 col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-title">Total Clients</div>
                <div class="stat-value" id="totalClients">0</div>
                <div class="stat-icon"><i class="fa fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-title">Pending ITR</div>
                <div class="stat-value" id="pendingITR">0</div>
                <div class="stat-icon"><i class="fa fa-file-invoice"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-title">GST Returns</div>
                <div class="stat-value" id="gstReturns">0</div>
                <div class="stat-icon"><i class="fa fa-money-bill"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-title">Website</div>
                <div class="stat-value" id="websiteTasks">0</div>
                <div class="stat-icon"><i class="fa fa-globe"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-title">Overdue</div>
                <div class="stat-value" id="overdueTasks">0</div>
                <div class="stat-icon"><i class="fa fa-triangle-exclamation"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-title">Completed</div>
                <div class="stat-value" id="completedTasks">0</div>
                <div class="stat-icon"><i class="fa fa-circle-check"></i></div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="search-box">
        <div class="row">
            <div class="col-md-9">
                <input class="form-control" id="searchInput" placeholder="Search Client Name, PAN, GSTIN...">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <hr>

    <!-- Client Directory -->
    <h4 class="mb-3"><i class="fa fa-users me-2"></i>Client Directory</h4>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>PAN</th>
                    <th>GSTIN</th>
                    <th>Mobile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="clientTable">
                <tr><td colspan="6" class="text-center">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ============ MODAL ============ -->
<div class="modal fade" id="clientModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="clientForm">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Add New Client</h4>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Client Name *</label>
                            <input type="text" name="client_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Company</label>
                            <input type="text" name="company" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>PAN</label>
                            <input type="text" name="pan" class="form-control text-uppercase">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Aadhaar</label>
                            <input type="text" name="aadhaar" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>GSTIN</label>
                            <input type="text" name="gstin" class="form-control text-uppercase">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-select">
                                <option>Individual</option>
                                <option>Proprietorship</option>
                                <option>Partnership</option>
                                <option>Company</option>
                                <option>Trust</option>
                                <option>Society</option>
                                <option>NGO</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Income Tax Username</label>
                            <input type="text" name="it_username" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Income Tax Password</label>
                            <input type="text" name="it_password" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>GST Username</label>
                            <input type="text" name="gst_username" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>GST Password</label>
                            <input type="text" name="gst_password" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>City</label>
                            <input type="text" name="city" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>State</label>
                            <input type="text" name="state" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>PIN Code</label>
                            <input type="text" name="pincode" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // === ABSOLUTE BASE URL ===
    const BASE = window.location.origin + '/reports/client-work-tracker';

    async function loadClients() {
        try {
            const resp = await fetch(BASE + '/api/get_clients.php');
            const result = await resp.json();

            if (!result.success) {
                document.getElementById('clientTable').innerHTML =
                    `<tr><td colspan="6" class="text-center text-danger">${result.message || 'API error'}</td></tr>`;
                return;
            }

            const tbody = document.getElementById('clientTable');
            tbody.innerHTML = '';
            document.getElementById('totalClients').innerHTML = result.clients.length;

            if (!result.clients.length) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center">No clients yet</td></tr>`;
                return;
            }

            result.clients.forEach((c, i) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${i+1}</td>
                        <td>${c.client_name}</td>
                        <td>${c.pan || ''}</td>
                        <td>${c.gstin || ''}</td>
                        <td>${c.mobile || ''}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${c.id}"><i class="fa fa-edit"></i> Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${c.id}"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                `;
            });

            attachDeleteEvents();
            attachEditEvents();

        } catch (e) {
            document.getElementById('clientTable').innerHTML =
                `<tr><td colspan="6" class="text-center text-danger">Network error – check console</td></tr>`;
            console.error('loadClients error:', e);
        }
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = async function() {
                if (!confirm('Delete this client?')) return;
                try {
                    const resp = await fetch(BASE + '/api/delete_client.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({id: this.dataset.id})
                    });
                    const data = await resp.json();
                    if (data.success) loadClients();
                    else alert(data.message || 'Delete failed');
                } catch (e) {
                    alert('Network error during delete');
                }
            };
        });
    }

    function attachEditEvents() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = function() {
                window.location = BASE + '/pages/edit-client.php?id=' + this.dataset.id;
            };
        });
    }

    document.getElementById('clientForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';

        try {
            const resp = await fetch(BASE + '/api/save_client.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const result = await resp.json();
            if (result.success) {
                alert('✅ Client saved!');
                this.reset();
                document.querySelector('[data-bs-dismiss="modal"]').click();
                loadClients();
            } else {
                alert('❌ ' + (result.message || 'Save failed'));
            }
        } catch (e) {
            alert('❌ Network error – check console');
            console.error(e);
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-save"></i> Save Client';
    });

    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#clientTable tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    document.addEventListener('DOMContentLoaded', loadClients);
</script>
</body>
</html>