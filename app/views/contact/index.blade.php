<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact List - SimpleShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="home.html">SimpleShop</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-sm btn-outline-secondary" href="products.html">Products</a>
                <a class="btn btn-sm btn-outline-secondary" href="cart.html">Cart</a>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3">Contact Messages</h1>
                        <a href="/contact/create" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add New
                        </a>
                    </div>

                    @if (isset($_SESSION['success']))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $_SESSION['success'] }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">ID</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($contacts) && count($contacts) > 0)
                                            @foreach($contacts as $contact)
                                            <tr>
                                                <td class="ps-4 fw-bold">#{{ $contact['id'] }}</td>
                                                <td>{{ $contact['full_name'] }}</td>
                                                <td>{{ $contact['email'] }}</td>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 rounded-pill">
                                                        {{ ucfirst($contact['subject']) }}
                                                    </span>
                                                </td>
                                                <td class="text-muted small">{{ date('d/m/Y', strtotime($contact['created_at'])) }}</td>
                                                {{-- @if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') --}}

                                                <td class="text-end pe-4">
                                                    <a href="/contact/edit/{{ $contact['id'] }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="/contact/delete/{{ $contact['id'] }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this message?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                                {{-- @else
                                                <td class="text-end pe-4">
                                                    <span class="text-muted small">Không có quyền truy cập</span>
                                                </td>
                                                @endif --}}
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    No messages found.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 bg-white border-top mt-5">
        <div class="container text-center text-muted">
            <small>&copy; 2026 SimpleShop. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
</html>