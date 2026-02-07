@extends('layout.adminLayout')

@section('content')
    <style>
        .text-brand {
            color: #009981 !important;
        }

        .btn-brand {
            background-color: #009981;
            color: white;
            border-color: #009981;
        }

        .btn-brand:hover {
            background-color: #007a67;
            color: white;
            border-color: #007a67;
        }

        .bg-light-brand {
            background-color: #f0fdf9;
        }

        /* Table Styles Professional */
        .variant-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #555;
            background-color: #f8f9fa;
            vertical-align: middle;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            white-space: nowrap;
            padding: 12px;
        }

        .variant-table td {
            vertical-align: middle;
            padding: 8px;
            position: relative;
        }

        .variant-table input.form-control,
        .variant-table select.form-select {
            border-radius: 4px;
            border-color: #dee2e6;
            font-size: 0.9rem;
            padding: 6px 8px;
        }

        .variant-table input.form-control:focus,
        .variant-table select.form-select:focus {
            border-color: #009981;
            box-shadow: 0 0 0 0.2rem rgba(0, 153, 129, 0.15);
        }

        /* Validation Error States */
        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff8f8;
            animation: shake 0.3s ease-in-out;
        }

        .row-error td {
            background-color: #fff5f5 !important;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-3px);
            }

            50% {
                transform: translateX(3px);
            }

            75% {
                transform: translateX(-3px);
            }

            100% {
                transform: translateX(0);
            }
        }

        .img-preview-sm {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }

        .img-preview-sm:hover {
            transform: scale(1.1);
            border-color: #009981;
        }

        /* Toolbar */
        .card-header-actions {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.5rem;
        }

        .attr-toolbar {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* Toast Container */
        .toast-container {
            z-index: 1060;
        }

        /* Switch Status */
        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: #009981;
            border-color: #009981;
        }
    </style>

    <div class="container-fluid px-4 py-4 position-relative">

        <div class="toast-container position-fixed top-0 end-0 p-3"></div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-brand m-0">Quản lý Biến thể Sản phẩm</h4>
                <div class="text-muted small mt-1">Sản phẩm: <strong class="text-dark">{{ $product['name'] }}</strong></div>
            </div>
            <a href="/productvariant" class="btn btn-outline-secondary btn-sm shadow-sm px-3 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Danh sách
            </a>
        </div>

        @php
            $oldVariants = $_SESSION['old_variants'] ?? [];
            unset($_SESSION['old_variants']);

            $ssSuccess = $_SESSION['success'] ?? '';
            unset($_SESSION['success']);

            $ssError = $_SESSION['error'] ?? '';
            unset($_SESSION['error']);
        @endphp

        <form action="/productvariant/store" method="POST" enctype="multipart/form-data" id="variantForm">
            <input type="hidden" name="product_id" value="{{ $product['id'] }}">

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header-actions d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h6 class="fw-bold mb-0 text-secondary d-flex align-items-center">
                        <i class="bi bi-grid-3x3-gap-fill me-2 text-brand"></i> DANH SÁCH & CẤU HÌNH
                    </h6>

                    <div class="attr-toolbar">
                        <div class="input-group input-group-sm">
                            <select class="form-select border-secondary text-secondary fw-bold" id="attrSelector"
                                style="min-width: 150px;">
                                <option value="">+ Thêm cột thuộc tính</option>
                                @foreach ($attributes as $attr)
                                    <option value="{{ $attr['id'] }}">{{ $attr['name'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-success px-3 fw-bold" id="btnAddAttrCol"
                                title="Thêm vào bảng">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                        <div class="vr mx-2 text-secondary opacity-25 my-1"></div>

                        <button type="button" class="btn btn-sm btn-outline-primary border-0 fw-bold"
                            data-bs-toggle="modal" data-bs-target="#createAttrModal">
                            <i class="bi bi-plus-circle me-1"></i> Thêm thuộc tính
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 fw-bold"
                            data-bs-toggle="modal" data-bs-target="#manageAttrModal">
                            <i class="bi bi-gear-fill me-1"></i> Quản lý thuộc tính 
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive" style="min-height: 400px; border-top: 1px solid #eee;">
                        <table class="table table-bordered mb-0 variant-table w-100" id="gridTable">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">Ảnh</th>
                                    <th style="min-width: 160px;">SKU <span class="fw-light text-muted"
                                            style="font-size: 0.75em">(Tự động)</span></th>
                                    <th style="width: 150px;" class="bg-light-brand text-dark">Giá bán <span
                                            class="text-danger">*</span></th>
                                    <th style="width: 140px;">Giá khuyến mãi</th>
                                    <th style="width: 110px;">Tồn kho <span class="text-danger">*</span></th>
                                    <th style="width: 80px;">Trạng thái</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="gridBody"></tbody>
                        </table>
                    </div>

                    <div id="emptyState" class="text-center py-5 d-none">
                        <div class="mb-3"><i class="bi bi-inbox fs-1 text-muted opacity-25"></i></div>
                        <p class="text-muted small">Chưa có biến thể nào. Hãy thêm dòng mới hoặc chọn thuộc tính.</p>
                    </div>
                </div>

                <div
                    class="card-footer bg-white py-3 d-flex justify-content-between align-items-center sticky-bottom border-top shadow-sm">
                    <button type="button" class="btn btn-light border text-primary fw-bold px-3" onclick="addVariantRow()">
                        <i class="bi bi-plus-circle-dotted me-2"></i>Thêm dòng biến thể
                    </button>

                    <div class="d-flex gap-2">
                        <a href="/productvariant" class="btn btn-light border px-4 fw-bold text-secondary">Hủy bỏ</a>
                        <button type="button" onclick="validateAndSubmit()" class="btn btn-brand fw-bold px-5 shadow-sm">
                            <i class="bi bi-save me-2"></i> LƯU THAY ĐỔI
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('product.attribute-variant.partials.modals')

    <script>
        const attributesData = <?= json_encode($attributes) ?>;
        const variantsData = <?= json_encode($variantsData) ?>;
        const ssSuccess = "<?= htmlspecialchars($ssSuccess) ?>";
        const ssError = "<?= htmlspecialchars($ssError) ?>";

        let activeAttributes = [];
        let rowCount = 0;

        document.addEventListener('DOMContentLoaded', () => {
            if (ssSuccess) showToast(ssSuccess, 'success');
            if (ssError) showToast(ssError, 'error');

            if (variantsData && variantsData.length > 0) {
                variantsData.forEach(v => {
                    const attrs = v.attributes || {};
                    for (const attrId in attrs) {
                        if (!activeAttributes.includes(attrId)) {
                            addAttributeColumn(attrId, false);
                        }
                    }
                });
                variantsData.forEach(v => addVariantRow(v));
            } else {
                addVariantRow();
            }
            checkDuplicates();
        });

        function showToast(message, type = 'success') {
            const container = document.querySelector('.toast-container');
            const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

            const html = `
                <div class="toast align-items-center text-white ${bgClass} border-0 mb-2 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center gap-2 py-3 px-3">
                            <i class="bi ${icon} fs-5"></i>
                            <div class="fw-semibold">${message}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            const tpl = document.createElement('template');
            tpl.innerHTML = html.trim();
            const el = tpl.content.firstChild;
            container.appendChild(el);
            new bootstrap.Toast(el, {
                delay: 5000
            }).show();
            el.addEventListener('hidden.bs.toast', () => el.remove());
        }

        function addAttributeColumn(attrId, animate = true) {
            if (activeAttributes.includes(attrId)) {
                const th = document.querySelector(`th[data-attr-id="${attrId}"]`);
                if (th && animate) {
                    th.classList.add('is-invalid');
                    setTimeout(() => th.classList.remove('is-invalid'), 500);
                }
                showToast(`Thuộc tính này đã được thêm!`, 'error');
                return;
            }

            const attr = attributesData.find(a => a.id == attrId);
            if (!attr) return;

            activeAttributes.push(attrId);

            const headerRow = document.querySelector('#gridTable thead tr');
            const newTh = document.createElement('th');
            newTh.setAttribute('data-attr-id', attrId);
            newTh.innerHTML =
                `${attr.name} <i class="bi bi-x-circle-fill text-secondary cursor-pointer ms-1 small opacity-50 hover-opacity-100" onclick="removeCol(this, '${attrId}')"></i>`;
            newTh.style.minWidth = "130px";
            newTh.className = "bg-light text-dark";

            // Insert before Price (index -5 because added Status column)
            const insertIdx = headerRow.children.length - 5;
            headerRow.insertBefore(newTh, headerRow.children[insertIdx]);

            const rows = document.querySelectorAll('#gridBody tr');
            rows.forEach(row => {
                const idx = row.getAttribute('data-idx');
                const newTd = document.createElement('td');
                newTd.className = `attr-col-${attrId}`;
                newTd.innerHTML = renderSelectHtml(attrId, idx);
                row.insertBefore(newTd, row.children[insertIdx]);
            });

            if (animate) checkDuplicates();
        }

        document.getElementById('btnAddAttrCol').addEventListener('click', function() {
            const select = document.getElementById('attrSelector');
            if (select.value) addAttributeColumn(select.value);
            else showToast('Vui lòng chọn thuộc tính!', 'error');
        });

        function removeCol(icon, attrId) {
            if (!confirm('Gỡ cột này sẽ xóa dữ liệu thuộc tính của cột đó ở tất cả các dòng?')) return;
            icon.closest('th').remove();
            document.querySelectorAll(`.attr-col-${attrId}`).forEach(td => td.remove());
            activeAttributes = activeAttributes.filter(id => id != attrId);
            checkDuplicates();
        }

        function renderSelectHtml(attrId, rowIdx, selectedValue = null) {
            const attr = attributesData.find(a => a.id == attrId);
            let options = `<option value="">-- Chọn --</option>`;
            if (attr && attr.values) {
                attr.values.forEach(v => {
                    const isSelected = (selectedValue == v.id) ? 'selected' : '';
                    options += `<option value="${v.id}" ${isSelected}>${v.value}</option>`;
                });
            }
            options += `<option value="NEW" class="text-success fw-bold">+ Tạo mới...</option>`;
            return `<select name="variants[${rowIdx}][attributes][${attrId}]" class="form-select attr-select" onchange="checkNewValue(this, '${attrId}'); checkDuplicates()">
                        ${options}
                    </select>`;
        }

        // --- Row Logic ---
        function addVariantRow(data = null) {
            const tbody = document.getElementById('gridBody');
            const tr = document.createElement('tr');
            tr.className = 'variant-row';
            tr.setAttribute('data-idx', rowCount);

            const id = data?.id || '';
            const sku = data?.sku || '';
            const price = data?.price || '';
            const sale_price = data?.sale_price || 0;
            const quantity = data?.quantity || 10;
            const status = (data && data.status !== undefined) ? data.status : 1;
            const image = (data?.image) ? '/' + data.image : 'https://placehold.co/40x40?text=+';
            const currentImgInput = (data?.image) ?
                `<input type="hidden" name="variants[${rowCount}][current_image]" value="${data.image}">` : '';
            let hiddenId = id ? `<input type="hidden" name="variants[${rowCount}][id]" value="${id}">` : '';
            const attrMap = data?.attributes || {};

            let cells = `
                <td class="text-center position-relative">
                    ${hiddenId} ${currentImgInput}
                    <label class="cursor-pointer d-block">
                        <img src="${image}" class="img-preview-sm" id="preview_${rowCount}" onerror="this.src='https://placehold.co/40x40?text=+'">
                        <input type="file" name="variants[${rowCount}][image]" class="d-none" onchange="previewImage(this, ${rowCount})">
                    </label>
                </td>
                <td><input type="text" name="variants[${rowCount}][sku]" class="form-control" placeholder="Tự động" value="${sku}"></td>
            `;

            activeAttributes.forEach(attrId => {
                const valId = attrMap[attrId] || null;
                cells += `<td class="attr-col-${attrId}">${renderSelectHtml(attrId, rowCount, valId)}</td>`;
            });

            cells += `
                <td><input type="number" name="variants[${rowCount}][price]" class="form-control text-end fw-bold text-brand" required min="0" value="${price}" oninput="removeError(this)"></td>
                <td><input type="number" name="variants[${rowCount}][sale_price]" class="form-control text-end" min="0" value="${sale_price}" oninput="removeError(this)"></td>
                <td><input type="number" name="variants[${rowCount}][quantity]" class="form-control text-center" required min="0" value="${quantity}" oninput="removeError(this)"></td>
                <td class="text-center">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" role="switch" id="status_${rowCount}" ${status == 1 ? 'checked' : ''} onchange="updateStatus(this, ${rowCount})">
                        <input type="hidden" name="variants[${rowCount}][status]" id="status_val_${rowCount}" value="${status}">
                    </div>
                </td>
                <td class="text-center">
                    ${id ? `<a href="/productvariant/delete/${id}" class="btn btn-sm text-danger border-0" onclick="return confirm('Xóa vĩnh viễn biến thể này?')" title="Xóa"><i class="bi bi-trash-fill fs-5"></i></a>` 
                         : `<button type="button" class="btn btn-sm text-secondary border-0" onclick="removeRow(this)"><i class="bi bi-x-lg fs-5"></i></button>`}
                </td>
            `;

            tr.innerHTML = cells;
            tbody.appendChild(tr);
            rowCount++;

            document.getElementById('emptyState').classList.add('d-none');
            if (data) checkDuplicates();
        }

        // --- Status Toggle Logic ---
        function updateStatus(checkbox, idx) {
            document.getElementById(`status_val_${idx}`).value = checkbox.checked ? 1 : 0;
        }

        // --- Real-time Check ---
        function checkDuplicates() {
            const rows = document.querySelectorAll('.variant-row');
            const signatures = {};

            // Reset
            rows.forEach(r => {
                r.classList.remove('row-error');
                r.querySelectorAll('.attr-select').forEach(s => s.classList.remove('is-invalid'));
            });

            rows.forEach(row => {
                const selects = row.querySelectorAll('.attr-select');
                if (selects.length === 0) return;

                let values = [];
                let allSelected = true;
                selects.forEach(sel => {
                    if (sel.value && sel.value !== 'NEW') values.push(sel.value);
                    else allSelected = false;
                });

                if (allSelected && values.length === activeAttributes.length) {
                    values.sort();
                    const sig = values.join('-');
                    if (!signatures[sig]) signatures[sig] = [];
                    signatures[sig].push(row);
                }
            });

            let hasDuplicate = false;
            for (const sig in signatures) {
                if (signatures[sig].length > 1) {
                    hasDuplicate = true;
                    signatures[sig].forEach(row => {
                        row.classList.add('row-error'); 
                        row.querySelectorAll('.attr-select').forEach(s => s.classList.add(
                            'is-invalid')); 
                    });
                }
            }
            if (hasDuplicate) showToast('Phát hiện các biến thể trùng lặp!', 'error');
        }

        function validateAndSubmit() {
            const rows = document.querySelectorAll('.variant-row');
            let hasError = false;
            let firstErrorEl = null;

            document.querySelectorAll('.is-invalid').forEach(el => {
                if (el.tagName === 'INPUT') el.classList.remove('is-invalid');
            });

            rows.forEach(row => {
                const priceIn = row.querySelector('input[name*="[price]"]');
                const saleIn = row.querySelector('input[name*="[sale_price]"]');
                const qtyIn = row.querySelector('input[name*="[quantity]"]');

                if (!priceIn.value || parseFloat(priceIn.value) < 0) {
                    priceIn.classList.add('is-invalid');
                    hasError = true;
                    if (!firstErrorEl) firstErrorEl = priceIn;
                }
                if (parseFloat(saleIn.value) > parseFloat(priceIn.value)) {
                    saleIn.classList.add('is-invalid');
                    hasError = true;
                    if (!firstErrorEl) firstErrorEl = saleIn;
                }

                // Check duplicates class
                if (row.classList.contains('row-error')) {
                    hasError = true;
                }
            });

            if (hasError) {
                showToast('Vui lòng kiểm tra các ô bị đỏ hoặc dòng bị trùng lặp!', 'error');
                if (firstErrorEl) firstErrorEl.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
                return;
            }

            document.getElementById('variantForm').submit();
        }

        function removeError(input) {
            input.classList.remove('is-invalid');
        }

        function previewImage(input, idx) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`preview_${idx}`).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
            checkDuplicates();
        }

        let currentSelectElement = null;

        function checkNewValue(select, attrId) {
            if (select.value === 'NEW') {
                currentSelectElement = select;
                document.getElementById('currentAttrIdForValue').value = attrId;
                const attr = attributesData.find(a => a.id == attrId);
                document.getElementById('valueModalLabel').innerText = `Thêm giá trị cho: ${attr.name}`;
                document.getElementById('newValueInput').value = '';
                new bootstrap.Modal(document.getElementById('createValueModal')).show();
            }
        }

        function submitNewValue() {
            const attrId = document.getElementById('currentAttrIdForValue').value;
            const value = document.getElementById('newValueInput').value;
            if (!value) return;
            fetch('/attributevalue/storeAjax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    attribute_id: attrId,
                    value: value
                })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    document.querySelectorAll(`select[name^="variants"][name$="[attributes][${attrId}]"]`).forEach(
                        sel => {
                            sel.add(new Option(data.data.value, data.data.id), sel.options[sel.options.length -
                                1]);
                        });
                    if (currentSelectElement) currentSelectElement.value = data.data.id;
                    bootstrap.Modal.getInstance(document.getElementById('createValueModal')).hide();
                    const attr = attributesData.find(a => a.id == attrId);
                    if (attr) attr.values.push(data.data);
                    showToast('Đã thêm giá trị mới!', 'success');
                } else {
                    showToast(data.message, 'error');
                }
            }).catch(err => console.error(err));
        }

        // Manage Attr Logic
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
                    manageAttrActions.style.display = 'block';
                    updateAttrName.value = selectedOption.text;
                    formUpdateAttr.action = `/attribute/update/${attrId}`;
                    linkDeleteAttr.href = `/attribute/delete/${attrId}`;
                } else {
                    manageAttrActions.style.display = 'none';
                }
            });
        }
    </script>
@endsection
