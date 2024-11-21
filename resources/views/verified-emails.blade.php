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
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }

        .card-modern {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .table-modern {
            border: 1px solid #dee2e6;
        }

        .table-modern thead th {
            background-color: #6a11cb;
            color: #fff;
            border-bottom: 2px solid #6a11cb;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-modern tbody tr:hover {
            background-color: #f1f5ff;
        }

        .table-modern td {
            border-top: 1px solid #dee2e6;
            padding: 12px 16px;
        }

        .badge-modern {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: #fff;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .header-section {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            border-radius: 16px;
            padding: 60px 20px;
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
        }

        .header-section h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .header-section p {
            font-size: 1.2rem;
        }

        .card-browser {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fff;
            padding: 15px;
            width: 100%;
            max-width: 18rem;
            margin-bottom: 15px;
        }

        .card-browser h6 {
            font-weight: 600;
        }

        .card-browser p {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .card-browser .text-muted {
            font-size: 0.8rem;
        }

        .card-browser .badge-status {
            font-weight: 600;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Icons in Table Headers */
        .table-modern thead th i {
            margin-right: 8px;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <!-- Header Section -->
    <div class="header-section">
        <h1 class="display-5">Verified Emails and Browsers</h1>
        <p>Easily track verified emails and associated browser sessions with a modern interface.</p>
    </div>

    <!-- DataTable Section -->
    <div class="p-4 bg-white rounded-3 shadow-sm">
        <div class="table-responsive">
            <table id="verifiedEmailsTable" class="table table-modern table-hover align-middle table-bordered">
                <thead>
                <tr>
                    <th><i class="fas fa-envelope"></i>Email</th>
                    <th><i class="fas fa-globe"></i>Domain</th>
                    <th><i class="fas fa-check-circle"></i>Last Verified</th>
                    <th><i class="fas fa-desktop"></i>Browser Details</th>
                </tr>
                </thead>
                <tbody>
                @foreach($verifiedEmails as $record)
                    <tr>
                        <!-- Email Column -->
                        <td class="text-primary fw-bold">{{ $record->email }}</td>
                        <!-- Domain Column -->
                        <td>{{ $record->domain }}</td>
                        <!-- Last Verified Column -->
                        <td>{{ $record->last_verified_at ?? 'N/A' }}</td>
                        <!-- Browser Details Column -->
                        <td>
                            <div class="d-flex flex-wrap gap-3">
                                @forelse($record->browsers as $browser)
                                    <div class="card-browser">
                                        <h6 class="mb-2"><i class="fas fa-browser"></i> {{ $browser->browser }}</h6>
                                        <p class="text-muted">Verified At: {{ $browser->verified_at }}</p>
                                        <p><strong>IP:</strong> {{ $browser->session_ip }}</p>
                                        <p>
                                            <strong>Expires:</strong>
                                            <span class="badge-status {{ $browser->expires_at && now()->lessThan($browser->expires_at) ? 'text-success' : 'text-danger' }}">
                        {{ $browser->expires_at ?? 'No Expiry' }}
                      </span>
                                        </p>
                                    </div>
                                @empty
                                    <span class="text-muted fst-italic">No verified browsers</span>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
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
            responsive: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 3 } // Disable sorting on Browser Details column
            ]
        });
    });
</script>
</body>
</html>
