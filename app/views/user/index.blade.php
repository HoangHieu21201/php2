@extends('layout.adminLayout')

@section('content')
    <style>
        :root {
            --primary-color: #009981;
        }

        .text-brand {
            color: var(--primary-color) !important;
        }

        .btn-brand {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-brand:hover {
            background-color: #007a67;
            color: white;
        }
        
        /* Custom Pagination DataTables */
        .dataTables_wrapper .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>

    <div class="container py-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-people me-2"></i>Người dùng</h4>
                <a href="/user/create" class="btn btn-brand btn-sm shadow-sm"><i class="bi bi-person-plus me-1"></i> Thêm mới</a>
            </div>
            
            @if (isset($_SESSION['success']))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ $_SESSION['success'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="userTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Họ tên / Email</th>
                                <th>Liên hệ</th>
                                <th class="text-center">Địa chỉ mặc định</th>
                                <th class="text-center">Vai trò</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $u['name'] }}</div>
                                    <small class="text-muted">{{ $u['email'] }}</small>
                                </td>
                                <td>
                                    <div><i class="bi bi-telephone me-1"></i> {{ $u['phone'] ?? '---' }}</div>
                                </td>
                                <td class="text-center">
                                    <small class="text-muted text-truncate" style="max-width: 150px; display:inline-block;">
                                        {{ $u['default_address'] ?? 'Chưa cập nhật' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($u['role'] == 1)
                                    <span class="badge bg-danger">Admin</span>
                                    @else
                                    <span class="badge bg-info text-dark">Khách hàng</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($u['status'] == 1)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                                    @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Khóa</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <a href="/user/edit/{{ $u['id'] }}" class="btn btn-sm btn-light border text-primary" title="Sửa"><i class="bi bi-pencil"></i></a>
                                    <a href="/user/delete/{{ $u['id'] }}" class="btn btn-sm btn-light border text-danger ms-1" onclick="return confirm('Xóa người dùng này?');" title="Xóa"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Script để phân trang -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json"
                },
                "pageLength": 10
            });
        });
    </script>
@endsection