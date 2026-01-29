<footer class="bg-dark text-light pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: Brand Info -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-uppercase fw-bold mb-3 text-white" style="letter-spacing: 1px;">MyShop</h5>
                <p class="text-secondary small">
                    Chúng tôi cung cấp những sản phẩm thời trang chất lượng cao, mang lại phong cách và sự tự tin cho bạn.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Liên kết nhanh</h6>
                <ul class="list-unstyled small text-secondary">
                    <li class="mb-2"><a href="/" class="text-decoration-none text-secondary hover-white">Trang chủ</a></li>
                    <li class="mb-2"><a href="/shop" class="text-decoration-none text-secondary hover-white">Sản phẩm</a></li>
                    <li class="mb-2"><a href="/about" class="text-decoration-none text-secondary hover-white">Giới thiệu</a></li>
                    <li class="mb-2"><a href="/contact" class="text-decoration-none text-secondary hover-white">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Col 3: Customer Service -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Hỗ trợ</h6>
                <ul class="list-unstyled small text-secondary">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-white">Chính sách đổi trả</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-white">Hướng dẫn mua hàng</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-white">Bảo mật thông tin</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-white">Câu hỏi thường gặp</a></li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
            <div class="col-lg-4 col-md-6">
                <h6 class="text-white fw-bold mb-3">Đăng ký nhận tin</h6>
                <p class="text-secondary small">Nhận thông tin khuyến mãi mới nhất từ chúng tôi.</p>
                <form action="#" class="mt-3">
                    <div class="input-group">
                        <input type="email" class="form-control bg-transparent border-secondary text-white" placeholder="Email của bạn">
                        <button class="btn btn-primary" style="background-color: #009981; border: none;">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>

        <hr class="border-secondary my-4 opacity-25">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <small class="text-secondary">&copy; <?= date('Y') ?> <b>MyShop</b>. All Rights Reserved.</small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" height="20" class="me-2 bg-light rounded px-1">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="Paypal" height="20" class="me-2 bg-light rounded px-1">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" height="20" class="bg-light rounded px-1">
            </div>
        </div>
    </div>
    
    <style>
        .hover-white:hover { color: #fff !important; transition: 0.3s; }
    </style>
</footer>