<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verified Emails and Browsers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }

        /* Modern Card Styles */
        .card-modern {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .card-modern:focus {
            outline: 2px solid #6a11cb;
            outline-offset: 2px;
            transform: translateY(-3px);
        }

        /* Modern Table Styles */
        .table-modern {
            border: 1px solid #dee2e6;
            border-spacing: 0;
            width: 100%;
            border-collapse: collapse;
        }

        .table-modern thead th {
            background-color: #5a0fb5; /* Enhanced contrast */
            color: #ffffff;
            border-bottom: 2px solid #5a0fb5;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.9rem; /* Adjusted for smaller screens */
            padding: 12px;
        }

        .table-modern tbody tr {
            transition: background-color 0.2s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #f1f5ff;
        }

        .table-modern td {
            border-top: 1px solid #dee2e6;
            padding: 10px 16px;
        }

        /* Icons in Table Headers */
        .table-modern thead th i {
            margin-right: 8px;
            font-size: 1rem;
        }

        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
        }

        /* Modern Badge Styles */
        .badge-modern {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: #fff;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .badge-modern.success {
            background: linear-gradient(45deg, #28a745, #85d066);
        }

        .badge-modern.warning {
            background: linear-gradient(45deg, #ffc107, #ffd76f);
        }

        .badge-modern.danger {
            background: linear-gradient(45deg, #dc3545, #f28e94);
        }

        /* Header Section Styles */
        .header-section {
            background: linear-gradient(90deg, #6a11cb 20%, #2575fc 80%);
            border-radius: 16px;
            padding: 60px 20px;
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .header-section h1 {
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 2.5rem;
        }

        .header-section p {
            font-size: 1.2rem;
        }

        /* Browser Card Styles */
        .card-browser {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fff;
            padding: 15px;
            width: 100%;
            max-width: 18rem;
            margin-bottom: 15px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card-browser:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .card-browser h6 {
            font-weight: 600;
            font-size: 1rem;
        }

        .card-browser p {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .card-browser .text-muted {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .card-browser .badge-status {
            font-weight: 600;
        }

        /* Hover Animation for Interactive Elements */
        .table-modern tbody tr {
            transition: background-color 0.2s ease-in-out;
        }

        .table-modern tbody tr:hover {
            background-color: #e9f3fc;
        }

        .card-modern {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-title i {
            transition: color 0.3s ease;
        }

        .card-title:hover i {
            color: #6a11cb;
        }

        .badge-modern {
            font-size: 0.85rem;
            font-weight: bold;
            border-radius: 10px;
            padding: 6px 10px;
        }

        .badge-modern.success {
            background: linear-gradient(45deg, #28a745, #85d066);
            color: #fff;
        }

        .badge-modern.danger {
            background: linear-gradient(45deg, #dc3545, #f28e94);
            color: #fff;
        }

        @media (max-width: 768px) {
            .card-modern {
                margin-bottom: 20px;
            }

            .card-title {
                font-size: 1rem;
            }
        }

        .header-section {
            background: linear-gradient(90deg, #6a11cb 20%, #2575fc 80%);
            border-radius: 16px;
            padding: 60px 20px;
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
        }

        .header-section:before,
        .header-section:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15), transparent 70%);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
        }

        .header-section h1 {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 2.5rem;
            z-index: 1;
            position: relative;
        }

        .header-section p {
            font-size: 1.2rem;
            z-index: 1;
            position: relative;
        }

        .header-section .btn {
            z-index: 1;
            position: relative;
            background: linear-gradient(45deg, #ff7e5f, #feb47b);
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .header-section .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

    </style>
</head>
<body>

<div class="container-fluid py-5">
    <!-- Header Section -->
    <div class="header-section">
        <div class="container text-center py-5">
            <h1 class="display-5 fw-bold">
                <i class="fas fa-envelope-open-text text-warning me-2"></i>
                Verified Emails and Browsers
            </h1>
            <p class="lead ">
                Easily track verified emails and associated browser sessions with a sleek and modern interface.
            </p>
        </div>
    </div>

    <!-- DataTable Section -->
    <div class="p-4 bg-white rounded-3 shadow-sm">
        <div class="table-responsive">
            <table id="verifiedEmailsTable" class="table table-modern table-hover align-middle table-bordered">
                <thead>
                <tr>
                    <th style="width: 15%;"><i class="fas fa-envelope"></i> Email</th>
                    <th style="width: 15%;"><i class="fas fa-globe"></i> Domain</th>
                    <th style="width: 15%;"><i class="fas fa-globe"></i>Session Status</th>
                    <th style="width: 10%;"><i class="fas fa-check-circle"></i> Last Verified</th>
                    <th style="width: 50%;"><i class="fas fa-desktop"></i> Browser Details</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#verifiedEmailsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("laravel-access-guard.verified-emails") }}',
            columns: [
                { data: 'email', name: 'email' , searchable: true },
                { data: 'domain', name: 'domain' , searchable: true },
                { data: 'status', name: 'status', searchable: true }, // Add status column
                { data: 'last_verified', name: 'last_verified' , searchable: true },
                { data: 'browser_details', name: 'browser_details', orderable: false, searchable: true }
            ],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;"
                }
            }
        });
    });
</script>

</body>
</html>
