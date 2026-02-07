@extends('layout.adminLayout')

@section('content')
    <style>
        .text-brand { color: #009981 !important; }
        .btn-brand { background-color: #009981; color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        .bg-light-brand { background-color: #f2fcfb; }
        
        /* Table styles to match template */
        .variant-table th { font-size: 0.85rem; text-transform: uppercase; color: #6c757d; background-color: #f8f9fa; }
        .variant-table td { vertical-align: middle; }
        .variant-table input.form-control-sm { border-radius: 4px; }
        .img-preview-sm { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; cursor: pointer; }
    </style>

    <div class="container-fluid px-4 py-4">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-brand m-0">Cấu hình Sản phẩm & Biến thể</h4>
                <div class="text-muted small mt-1">Sản phẩm: <strong class="text-dark">{{ $product['name'] }}</strong></div>
            </div>
            <a href="/productvariant" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay lại</a>
        </div>

        @if (isset($_SESSION['success']))
            <div class="alert alert-success d-flex align-items-center mb-3 p-2 small"><i class="bi bi-check-circle me-2"></i> {{ $_SESSION['success'] }}</div>
            @php unset($_SESSION['success']); @endphp
        @endif

        @if (isset($_SESSION['error']))
            <div class="alert alert-danger d-flex align-items-center mb-3 p-2 small"><i class="bi bi-exclamation-triangle me-2"></i> {{ $_SESSION['error'] }}</div>
            @php unset($_SESSION['error']); @endphp
        @endif

        <div class="row g-4">
            <!-- PHẦN 1: THÊM BIẾN THỂ MỚI (LƯỚI DỮ LIỆU) - ĐƯỢC ĐẨY LÊN TRÊN -->
            <div class="col-12">
                <form action="/productvariant/store" method="POST" enctype="multipart/form-data" id="variantForm">
                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-brand"><i class="bi bi-plus-square me-2"></i> Thêm biến thể mới</h6>
                            
                            <!-- Toolbar chọn thuộc tính -->
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="attrSelector" style="width: 200px;">
                                    <option value="">+ Thêm cột thuộc tính</option>
                                    @foreach($attributes as $attr)
                                        <option value="{{ $attr['id'] }}">{{ $attr['name'] }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-sm btn-success text-nowrap" id="btnAddAttrCol">Thêm</button>
                                
                                <div class="vr mx-1"></div>
                                
                                <!-- Nút Tạo mới thuộc tính -->
                                <button type="button" class="btn btn-sm btn-outline-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#createAttrModal">
                                    <i class="bi bi-plus-circle"></i> Tạo thuộc tính
                                </button>

                                <!-- Nút Quản lý thuộc tính (Sửa/Xóa) -->
                                <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap" data-bs-toggle="modal" data-bs-target="#manageAttrModal">
                                    <i class="bi bi-gear"></i> Quản lý
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 variant-table" id="gridTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;" class="text-center">Ảnh</th>
                                            <th style="width: 150px;">SKU (Tự động)</th>
                                            <!-- Các cột thuộc tính sẽ được JS chèn vào đây -->
                                            <th style="width: 150px;">Giá bán <span class="text-danger">*</span></th>
                                            <th style="width: 150px;">Giá gốc</th>
                                            <th style="width: 100px;">Kho</th>
                                            <th style="width: 50px;" class="text-center"><i class="bi bi-trash"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="gridBody">
                                        <!-- Dòng mẫu mặc định -->
                                        <tr class="variant-row" data-idx="0">
                                            <td class="text-center">
                                                <label class="cursor-pointer">
                                                    <img src="https://placehold.co/40x40?text=+" class="img-preview-sm" id="preview_0">
                                                    <input type="file" name="variants[0][image]" class="d-none" onchange="previewImage(this, 0)">
                                                </label>
                                            </td>
                                            <td><input type="text" name="variants[0][sku]" class="form-control form-control-sm" placeholder="Auto"></td>
                                            <!-- Cột thuộc tính động sẽ chèn vào đây -->
                                            <td><input type="number" name="variants[0][price]" class="form-control form-control-sm" required min="0"></td>
                                            <td><input type="number" name="variants[0][sale_price]" class="form-control form-control-sm" min="0" value="0"></td>
                                            <td><input type="number" name="variants[0][quantity]" class="form-control form-control-sm" required min="0" value="10"></td>
                                            <td class="text-center"><button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)">&times;</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-light border text-brand fw-bold btn-sm" onclick="addVariantRow()">
                                <i class="bi bi-plus-lg"></i> Thêm dòng biến thể
                            </button>
                            <button type="submit" class="btn btn-brand fw-bold px-4">Lưu thay đổi</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- PHẦN 2: DANH SÁCH BIẾN THỂ ĐÃ CÓ - ĐƯỢC ĐẨY XUỐNG DƯỚI -->
            <div class="col-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold"><i class="bi bi-list-check me-2"></i> Danh sách biến thể hiện tại</div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Ảnh</th>
                                    <th>SKU</th>
                                    <th>Thuộc tính</th>
                                    <th>Giá bán</th>
                                    <th>Kho</th>
                                    <th class="text-end pe-3">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($existingVariants))
                                <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có biến thể nào.</td></tr>
                                @else
                                    @foreach($existingVariants as $v)
                                    <tr>
                                        <td class="ps-3"><img src="/{{ $v['image'] ?? 'placeholder.png' }}" class="img-preview-sm" onerror="this.src='https://placehold.co/40x40'"></td>
                                        <td class="fw-bold text-dark">{{ $v['sku'] }}</td>
                                        <td>
                                            @if(!empty($v['attributes_string']))
                                                @foreach(explode(', ', $v['attributes_string']) as $attr)
                                                    <span class="badge bg-light text-secondary border me-1">{{ $attr }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{ number_format($v['price'], 0, ',', '.') }}đ</td>
                                        <td>{{ $v['quantity'] }}</td>
                                        <td class="text-end pe-3">
                                            <a href="/productvariant/delete/{{ $v['id'] }}" class="btn btn-sm btn-light text-danger border" onclick="return confirm('Xóa biến thể này?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tạo Thuộc tính Mới -->
    <div class="modal fade" id="createAttrModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 bg-light">
                    <h6 class="modal-title fw-bold">Tạo thuộc tính mới</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/attribute/store" method="POST">
                        <div class="mb-3">
                            <label class="form-label small">Tên thuộc tính</label>
                            <input type="text" name="name" class="form-control" placeholder="VD: Chất liệu" required>
                        </div>
                        <button type="submit" class="btn btn-brand w-100 btn-sm">Lưu ngay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Quản lý Thuộc tính (Sửa/Xóa) -->
    <div class="modal fade" id="manageAttrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 bg-secondary text-white">
                    <h6 class="modal-title fw-bold">Quản lý Thuộc tính</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Chọn thuộc tính cần sửa:</label>
                        <select class="form-select" id="manageAttrSelect">
                            <option value="">-- Chọn thuộc tính --</option>
                            @foreach($attributes as $attr)
                                <option value="{{ $attr['id'] }}">{{ $attr['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Form Sửa/Xóa hiển thị khi chọn -->
                    <div id="manageAttrActions" style="display: none;">
                        <form id="formUpdateAttr" method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Tên hiển thị mới:</label>
                                <input type="text" name="name" id="updateAttrName" class="form-control" required>
                                <div class="form-text small">Thay đổi này sẽ cập nhật tên thuộc tính trên toàn hệ thống.</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="#" id="linkDeleteAttr" class="btn btn-sm btn-danger" onclick="return confirm('CẢNH BÁO: Xóa thuộc tính này sẽ xóa tất cả các giá trị và dữ liệu liên quan trong các sản phẩm đã tạo. Bạn có chắc chắn không?')">
                                    <i class="bi bi-trash"></i> Xóa vĩnh viễn
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tạo Giá trị Thuộc tính Mới (Hidden, gọi bằng JS) -->
    <div class="modal fade" id="createValueModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 bg-light">
                    <h6 class="modal-title fw-bold">Thêm giá trị mới</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="currentAttrIdForValue">
                    <div class="mb-3">
                        <label class="form-label small" id="valueModalLabel">Giá trị</label>
                        <input type="text" id="newValueInput" class="form-control" placeholder="VD: Xanh ngọc">
                    </div>
                    <button type="button" class="btn btn-brand w-100 btn-sm" onclick="submitNewValue()">Lưu giá trị</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT XỬ LÝ LƯỚI ĐỘNG -->
    <script>
        // Dữ liệu thuộc tính và giá trị từ Server
        const attributesData = <?= json_encode($attributes) ?>;
        let activeAttributes = []; // Danh sách ID các thuộc tính đã thêm vào bảng
        let rowCount = 1;

        // --- 1. Xử lý Logic Quản lý Thuộc tính (Sửa/Xóa) ---
        const manageAttrSelect = document.getElementById('manageAttrSelect');
        const manageAttrActions = document.getElementById('manageAttrActions');
        const updateAttrName = document.getElementById('updateAttrName');
        const formUpdateAttr = document.getElementById('formUpdateAttr');
        const linkDeleteAttr = document.getElementById('linkDeleteAttr');

        if (manageAttrSelect) {
            manageAttrSelect.addEventListener('change', function() {
                const attrId = this.value;
                if (attrId) {
                    const selectedOption = this.options[this.selectedIndex];
                    const attrName = selectedOption.text;
                    
                    // Hiển thị form
                    manageAttrActions.style.display = 'block';
                    updateAttrName.value = attrName;
                    
                    // Cập nhật action form và link xóa
                    formUpdateAttr.action = `/attribute/update/${attrId}`;
                    linkDeleteAttr.href = `/attribute/delete/${attrId}`;
                } else {
                    manageAttrActions.style.display = 'none';
                }
            });
        }

        // --- 2. Thêm cột thuộc tính vào bảng ---
        document.getElementById('btnAddAttrCol').addEventListener('click', function() {
            const select = document.getElementById('attrSelector');
            const attrId = select.value;
            const attrName = select.options[select.selectedIndex].text;

            if (!attrId) return;
            if (activeAttributes.includes(attrId)) {
                alert('Thuộc tính này đã được thêm!');
                return;
            }

            activeAttributes.push(attrId);

            // Thêm Header
            const headerRow = document.querySelector('#gridTable thead tr');
            // Chèn trước cột Giá bán (index 2)
            const newTh = document.createElement('th');
            newTh.innerHTML = `${attrName} <i class="bi bi-x-circle text-danger cursor-pointer ms-1" onclick="removeCol(this, '${attrId}')" title="Bỏ cột"></i>`;
            newTh.style.minWidth = "120px";
            // Insert before Price column (which is effectively the 3rd cell now, index 2)
            headerRow.insertBefore(newTh, headerRow.children[headerRow.children.length - 4]);

            // Thêm Cell vào từng dòng
            const rows = document.querySelectorAll('#gridBody tr');
            rows.forEach(row => {
                const idx = row.getAttribute('data-idx');
                const newTd = document.createElement('td');
                newTd.className = `attr-col-${attrId}`;
                newTd.innerHTML = renderSelectHtml(attrId, idx);
                row.insertBefore(newTd, row.children[row.children.length - 4]);
            });
        });

        // Helper: Tạo HTML Select cho thuộc tính
        function renderSelectHtml(attrId, rowIdx) {
            const attr = attributesData.find(a => a.id == attrId);
            let options = `<option value="">-- Chọn --</option>`;
            if (attr && attr.values) {
                attr.values.forEach(v => {
                    options += `<option value="${v.id}">${v.value}</option>`;
                });
            }
            // Thêm nút tạo mới giá trị
            options += `<option value="NEW" class="text-brand fw-bold">+ Tạo mới...</option>`;
            
            // Name: variants[0][attributes][1] = value_id
            return `<select name="variants[${rowIdx}][attributes][${attrId}]" class="form-select form-select-sm" onchange="checkNewValue(this, '${attrId}')">
                        ${options}
                    </select>`;
        }

        // --- 3. Thêm dòng biến thể mới ---
        function addVariantRow() {
            const tbody = document.getElementById('gridBody');
            const tr = document.createElement('tr');
            tr.className = 'variant-row';
            tr.setAttribute('data-idx', rowCount);
            
            let cells = `
                <td class="text-center">
                    <label class="cursor-pointer">
                        <img src="https://placehold.co/40x40?text=+" class="img-preview-sm" id="preview_${rowCount}">
                        <input type="file" name="variants[${rowCount}][image]" class="d-none" onchange="previewImage(this, ${rowCount})">
                    </label>
                </td>
                <td><input type="text" name="variants[${rowCount}][sku]" class="form-control form-control-sm" placeholder="Auto"></td>
            `;

            // Render các cột thuộc tính đã active
            activeAttributes.forEach(attrId => {
                cells += `<td class="attr-col-${attrId}">${renderSelectHtml(attrId, rowCount)}</td>`;
            });

            cells += `
                <td><input type="number" name="variants[${rowCount}][price]" class="form-control form-control-sm" required min="0"></td>
                <td><input type="number" name="variants[${rowCount}][sale_price]" class="form-control form-control-sm" min="0" value="0"></td>
                <td><input type="number" name="variants[${rowCount}][quantity]" class="form-control form-control-sm" required min="0" value="10"></td>
                <td class="text-center"><button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)">&times;</button></td>
            `;

            tr.innerHTML = cells;
            tbody.appendChild(tr);
            rowCount++;
        }

        // 4. Xử lý ảnh preview
        function previewImage(input, idx) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`preview_${idx}`).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // 5. Xóa dòng
        function removeRow(btn) {
            const rows = document.querySelectorAll('#gridBody tr');
            if (rows.length > 1) {
                btn.closest('tr').remove();
            } else {
                alert('Phải có ít nhất 1 biến thể!');
            }
        }

        // 6. Xử lý khi chọn "Tạo mới..." giá trị
        let currentSelectElement = null; // Lưu lại select đang thao tác
        function checkNewValue(select, attrId) {
            if (select.value === 'NEW') {
                currentSelectElement = select;
                document.getElementById('currentAttrIdForValue').value = attrId;
                
                // Hiển thị modal
                const attr = attributesData.find(a => a.id == attrId);
                document.getElementById('valueModalLabel').innerText = `Thêm giá trị mới cho ${attr.name}`;
                document.getElementById('newValueInput').value = '';
                
                const modal = new bootstrap.Modal(document.getElementById('createValueModal'));
                modal.show();
            }
        }

        // Gửi Ajax tạo giá trị mới
        function submitNewValue() {
            const attrId = document.getElementById('currentAttrIdForValue').value;
            const value = document.getElementById('newValueInput').value;
            
            if (!value) return;

            fetch('/attributevalue/storeAjax', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ attribute_id: attrId, value: value })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Thêm option mới vào TẤT CẢ các select của attribute này
                    const allSelects = document.querySelectorAll(`select[name^="variants"][name$="[attributes][${attrId}]"]`);
                    allSelects.forEach(sel => {
                        const opt = new Option(data.data.value, data.data.id);
                        // Insert trước option "Tạo mới"
                        sel.add(opt, sel.options[sel.options.length - 1]);
                    });

                    // Set giá trị cho select hiện tại
                    if (currentSelectElement) {
                        currentSelectElement.value = data.data.id;
                    }
                    
                    // Đóng modal
                    const modalEl = document.getElementById('createValueModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    modalInstance.hide();
                    
                    // Cập nhật lại attributesData client-side (để dòng mới render đúng)
                    const attr = attributesData.find(a => a.id == attrId);
                    if(attr) attr.values.push(data.data);

                } else {
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
        }

        // 7. Xóa cột (chỉ xóa trên giao diện)
        function removeCol(icon, attrId) {
            // Xóa header
            icon.closest('th').remove();
            
            // Xóa cell ở body
            const cells = document.querySelectorAll(`.attr-col-${attrId}`);
            cells.forEach(td => td.remove());
            
            // Xóa khỏi mảng active
            activeAttributes = activeAttributes.filter(id => id != attrId);
        }
    </script>
@endsection