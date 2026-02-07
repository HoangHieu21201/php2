<!-- Modal Tạo Thuộc tính -->
<div class="modal fade" id="createAttrModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 bg-primary text-white">
                <h6 class="modal-title fw-bold">Tạo thuộc tính mới</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/attribute/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tên thuộc tính</label>
                        <input type="text" name="name" class="form-control" placeholder="VD: Chất liệu" required>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 btn-sm">Lưu ngay</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tạo Giá trị -->
<div class="modal fade" id="createValueModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 bg-success text-white">
                <h6 class="modal-title fw-bold">Thêm giá trị mới</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="currentAttrIdForValue">
                <div class="mb-3">
                    <label class="form-label small fw-bold" id="valueModalLabel">Giá trị</label>
                    <input type="text" id="newValueInput" class="form-control" placeholder="VD: Xanh ngọc">
                </div>
                <button type="button" class="btn btn-brand w-100 btn-sm" onclick="submitNewValue()">Lưu giá
                    trị</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Quản lý -->
<div class="modal fade" id="manageAttrModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 bg-secondary text-white">
                <h6 class="modal-title fw-bold">Quản lý Thuộc tính hệ thống</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Chọn thuộc tính cần sửa:</label>
                    <select class="form-select" id="manageAttrSelect">
                        <option value="">-- Chọn thuộc tính --</option>
                        @foreach ($attributes as $attr)
                            <option value="{{ $attr['id'] }}">{{ $attr['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="manageAttrActions" style="display: none;">
                    <form id="formUpdateAttr" method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tên hiển thị mới:</label>
                            <input type="text" name="name" id="updateAttrName" class="form-control" required>
                            <div class="form-text small">Lưu ý: Thay đổi này sẽ cập nhật trên toàn hệ thống.</div>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <a href="#" id="linkDeleteAttr" class="btn btn-sm btn-outline-danger px-3"
                                onclick="return confirm('CẢNH BÁO: Xóa thuộc tính này sẽ xóa tất cả các giá trị và dữ liệu liên quan trong các sản phẩm đã tạo. Bạn có chắc chắn không?')">
                                <i class="bi bi-trash me-1"></i> Xóa
                            </a>
                            <button type="submit" class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-save me-1"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
