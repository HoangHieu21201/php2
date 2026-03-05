@extends('layout.userLayout')

@section('content')
<style>
    :root {
        --primary-color: #009981;
    }
    
    .profile-sidebar {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .profile-header-bg {
        background: linear-gradient(135deg, var(--primary-color) 0%, #00cba9 100%);
        height: 120px;
    }
    
    .avatar-wrapper {
        margin-top: -60px;
        position: relative;
        display: inline-block;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #fff;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--primary-color);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }
    
    .nav-pills .nav-link {
        color: #495057;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.2s;
        text-align: left;
        margin-bottom: 8px;
    }
    
    .nav-pills .nav-link:hover {
        background-color: #f8f9fa;
        color: var(--primary-color);
    }
    
    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
        color: #fff;
        box-shadow: 0 4px 6px rgba(0, 153, 129, 0.2);
    }
    
    .address-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        transition: all 0.2s;
        position: relative;
        background: #fff;
    }
    
    .address-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
    }
    
    .address-card.is-default {
        border: 1px solid var(--primary-color);
        background-color: rgba(0, 153, 129, 0.02);
    }
    
    .default-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--primary-color);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .btn-action-group {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #e9ecef;
    }
    
    .add-address-card {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        height: 100%;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8f9fa;
        color: #6c757d;
    }
    
    .add-address-card:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: #fff;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 153, 129, 0.25);
    }
    
    .btn-brand {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    .btn-brand:hover {
        background-color: #007a67;
        color: white;
    }
</style>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-4 col-xl-3">
            <div class="profile-sidebar shadow-sm text-center pb-4 mb-4">
                <div class="profile-header-bg"></div>
                <div class="avatar-wrapper mb-3">
                    <div class="profile-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">{{ $user['name'] }}</h5>
                <p class="text-muted small mb-3">{{ $user['email'] }}</p>
                <div class="px-4">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                        <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab">
                            <i class="bi bi-person-gear me-2"></i>Thông tin tài khoản
                        </button>
                        <button class="nav-link" id="v-pills-address-tab" data-bs-toggle="pill" data-bs-target="#v-pills-address" type="button" role="tab">
                            <i class="bi bi-map me-2"></i>Sổ địa chỉ
                        </button>
                        <a href="/auth/logout" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-xl-9">
            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-4 border-bottom-0">
                            <h4 class="fw-bold mb-0">Cập nhật thông tin</h4>
                        </div>
                        <div class="card-body p-4 pt-0">
                            @if(isset($_SESSION['success']))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ $_SESSION['success'] }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    @php unset($_SESSION['success']); @endphp
                                </div>
                            @endif
                            @if(isset($_SESSION['error']))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $_SESSION['error'] }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    @php unset($_SESSION['error']); @endphp
                                </div>
                            @endif

                            <form action="/profile/update" method="POST">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Họ và tên</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user['name'] }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Số điện thoại</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user['phone'] }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="text" class="form-control bg-light" value="{{ $user['email'] }}" disabled readonly>
                                    </div>
                                    
                                    <div class="col-12"><hr class="my-2 text-muted opacity-25"></div>
                                    
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-3">Đổi mật khẩu <small class="text-muted fw-normal">(Để trống nếu không đổi)</small></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Mật khẩu mới</label>
                                        <input type="password" name="new_password" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                                        <input type="password" name="confirm_password" class="form-control">
                                    </div>
                                    
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-brand px-4 py-2 fw-semibold shadow-sm">
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Sổ địa chỉ -->
                <div class="tab-pane fade" id="v-pills-address" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Địa chỉ giao hàng</h4>
                        <button class="btn btn-brand btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="bi bi-plus-lg me-1"></i> Thêm địa chỉ mới
                        </button>
                    </div>

                    <div class="row g-3">
                        <!-- Danh sách địa chỉ -->
                        @if(isset($addresses) && count($addresses) > 0)
                            @foreach($addresses as $addr)
                            <div class="col-md-6">
                                <div class="address-card {{ $addr['is_default'] ? 'is-default' : '' }}">
                                    @if($addr['is_default'])
                                        <span class="default-badge"><i class="bi bi-check2 me-1"></i>Mặc định</span>
                                    @endif
                                    
                                    <div class="mb-2">
                                        <strong class="text-dark fs-5">{{ $addr['recipient_name'] }}</strong>
                                        <span class="text-muted ms-2 small">| {{ $addr['recipient_phone'] }}</span>
                                    </div>
                                    
                                    <p class="text-muted mb-3 small" style="min-height: 40px;">
                                        {{ $addr['address'] }}
                                    </p>
                                    
                                    <div class="btn-action-group d-flex justify-content-between align-items-center">
                                        @if(!$addr['is_default'])
                                            <a href="/profile/setDefaultAddress/{{ $addr['id'] }}" class="btn btn-link btn-sm text-decoration-none p-0 text-muted">
                                                Đặt làm mặc định
                                            </a>
                                        @else
                                            <span class="text-success small fw-bold"><i class="bi bi-star-fill me-1"></i>Địa chỉ chính</span>
                                        @endif
                                        
                                        <a href="/profile/deleteAddress/{{ $addr['id'] }}" 
                                           class="btn btn-outline-danger btn-sm border-0" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')"
                                           title="Xóa địa chỉ">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif

                        <!-- Nút thêm nhanh -->
                        <div class="col-md-6">
                            <div class="add-address-card" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-circle fs-1 mb-2"></i>
                                <span class="fw-semibold">Thêm địa chỉ mới</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Địa Chỉ -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="/profile/addAddress" method="POST">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-brand">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ tên người nhận</label>
                            <input type="text" class="form-control" name="recipient_name" value="{{ $user['name'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" class="form-control" name="recipient_phone" value="{{ $user['phone'] }}" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Tỉnh / Thành phố</label>
                            <select class="form-select" id="province" required>
                                <option value="" selected>Chọn Tỉnh/Thành</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Quận / Huyện</label>
                            <select class="form-select" id="district" disabled required>
                                <option value="" selected>Chọn Quận/Huyện</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Phường / Xã</label>
                            <select class="form-select" id="ward" disabled required>
                                <option value="" selected>Chọn Phường/Xã</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Địa chỉ chi tiết (Số nhà, Tên đường)</label>
                            <input type="text" class="form-control" id="specific_address" required placeholder="Ví dụ: 123 Đường Nguyễn Văn A">
                            <input type="hidden" name="address_detail" id="full_address">
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-brand px-4">Lưu địa chỉ</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        const specificAddressInput = document.getElementById('specific_address');
        const fullAddressInput = document.getElementById('full_address');
        
        const API_URL = 'https://provinces.open-api.vn/api';

        axios.get(`${API_URL}/p/`)
            .then(response => {
                response.data.forEach(item => {
                    let option = new Option(item.name, item.code);
                    option.setAttribute('data-name', item.name);
                    provinceSelect.add(option);
                });
            })
            .catch(error => console.error('Lỗi load tỉnh thành:', error));

        provinceSelect.addEventListener('change', function() {
            districtSelect.length = 1; wardSelect.length = 1; wardSelect.disabled = true;
            if (this.value) {
                districtSelect.disabled = false;
                axios.get(`${API_URL}/p/${this.value}?depth=2`).then(res => {
                    res.data.districts.forEach(item => {
                        let opt = new Option(item.name, item.code);
                        opt.setAttribute('data-name', item.name);
                        districtSelect.add(opt);
                    });
                });
            } else { districtSelect.disabled = true; }
            updateFullAddress();
        });

        districtSelect.addEventListener('change', function() {
            wardSelect.length = 1;
            if (this.value) {
                wardSelect.disabled = false;
                axios.get(`${API_URL}/d/${this.value}?depth=2`).then(res => {
                    res.data.wards.forEach(item => {
                        let opt = new Option(item.name, item.code);
                        opt.setAttribute('data-name', item.name);
                        wardSelect.add(opt);
                    });
                });
            } else { wardSelect.disabled = true; }
            updateFullAddress();
        });

        wardSelect.addEventListener('change', updateFullAddress);
        specificAddressInput.addEventListener('input', updateFullAddress);

        function updateFullAddress() {
            const p = provinceSelect.options[provinceSelect.selectedIndex]?.getAttribute('data-name') || '';
            const d = districtSelect.options[districtSelect.selectedIndex]?.getAttribute('data-name') || '';
            const w = wardSelect.options[wardSelect.selectedIndex]?.getAttribute('data-name') || '';
            const s = specificAddressInput.value.trim();
            
            let parts = [];
            if (s) parts.push(s);
            if (w) parts.push(w);
            if (d) parts.push(d);
            if (p) parts.push(p);
            
            fullAddressInput.value = parts.join(', ');
        }
    });
</script>
@endsection