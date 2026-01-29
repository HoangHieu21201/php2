@extends('layout.userLayout')

@section('content')

    <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        </div>
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" style="height: 500px;">
                <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop" class="d-block w-100 h-100 object-fit-cover" alt="Tech Banner 1">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-4 rounded mb-5">
                    <h1 class="display-4 fw-bold">Công Nghệ Đột Phá</h1>
                    <p class="lead">Trải nghiệm sức mạnh kỹ thuật số với những sản phẩm tiên tiến nhất.</p>
                    <a href="#shop-now" class="btn btn-brand px-4 py-2 fw-bold shadow-sm">Mua ngay <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item" style="height: 500px;">
                <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop" class="d-block w-100 h-100 object-fit-cover" alt="Tech Banner 2">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-4 rounded mb-5">
                    <h1 class="display-4 fw-bold">Thế Giới Số Trong Tầm Tay</h1>
                    <p class="lead">Kết nối không giới hạn với hệ sinh thái thiết bị thông minh đa dạng.</p>
                    <a href="#shop-now" class="btn btn-light text-brand fw-bold px-4 py-2 shadow-sm">Xem chi tiết <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <div class="container pb-5" id="shop-now">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark border-start border-4 border-success ps-3 m-0">Sản phẩm nổi bật</h3>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                    Sắp xếp
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Mới nhất</a></li>
                    <li><a class="dropdown-item" href="#">Giá thấp đến cao</a></li>
                    <li><a class="dropdown-item" href="#">Giá cao đến thấp</a></li>
                </ul>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                <?php
                    $currentPrice = $p['price']; 
                    $minPrice = $p['min_price'] ?? null; 
                    $maxPrice = $p['max_price'] ?? null; 
                    
                    $displayPrice = number_format($currentPrice, 0, ',', '.') . 'đ';
                    
                    if ($minPrice && $maxPrice) {
                        if ($minPrice < $maxPrice) {
                            $displayPrice = number_format($minPrice, 0, ',', '.') . ' - ' . number_format($maxPrice, 0, ',', '.') . 'đ';
                        } elseif ($minPrice > 0) {
                            $displayPrice = number_format($minPrice, 0, ',', '.') . 'đ';
                        }
                    }
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm product-card overflow-hidden">
                        <!-- Ảnh sản phẩm -->
                        <div class="position-relative">
                            <a href="/userproductdetail/detail/<?= $p['id'] ?>">
                                <img src="<?= !empty($p['image']) ? '/' . $p['image'] : 'https://placehold.co/300x300?text=No+Img' ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($p['name']) ?>"
                                     style="height: 250px; object-fit: cover;">
                            </a>
                            <?php if(isset($p['sale_price']) && $p['sale_price'] > 0): ?>
                                <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold">Sale</span>
                            <?php endif; ?>
                            
                            <div class="position-absolute bottom-0 end-0 m-2">
                                <a href="/userproductdetail/detail/<?= $p['id'] ?>" class="btn btn-light shadow-sm rounded-circle text-brand btn-sm" title="Xem chi tiết">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="mb-1">
                                <small class="text-muted text-uppercase" style="font-size: 0.75rem;">
                                    <?= htmlspecialchars($p['category_name'] ?? 'Danh mục') ?>
                                </small>
                            </div>
                            <h6 class="card-title fw-bold mb-auto">
                                <a href="/userproductdetail/detail/<?= $p['id'] ?>" class="text-decoration-none text-dark stretched-link">
                                    <?= htmlspecialchars($p['name']) ?>
                                </a>
                            </h6>
                            
                            <div class="mt-3">
                                <span class="fw-bold text-brand fs-5"><?= $displayPrice ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Empty" width="200">
                    <p class="text-muted mt-3">Chưa có sản phẩm nào được bày bán.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

@endsection