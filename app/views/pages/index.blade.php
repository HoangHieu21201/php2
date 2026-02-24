@extends('layout.userLayout')

@section('content')

<!-- Hero Carousel -->
{{-- <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" style="height: 500px;">
            <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop" class="d-block w-100 h-100 object-fit-cover" alt="Banner 1">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-4 rounded mb-5">
                <h1 class="display-4 fw-bold">Công Nghệ Đột Phá</h1>
                <p class="lead">Trải nghiệm sức mạnh kỹ thuật số với những sản phẩm tiên tiến nhất.</p>
            </div>
        </div>
        <div class="carousel-item" style="height: 500px;">
            <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop" class="d-block w-100 h-100 object-fit-cover" alt="Banner 2">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-4 rounded mb-5">
                <h1 class="display-4 fw-bold">Thế Giới Số</h1>
                <p class="lead">Kết nối không giới hạn với hệ sinh thái thiết bị thông minh.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div> --}}

<div class="container pb-5 mt-5" id="shop-now">
    
    <!-- Search & Filter Form -->
    <!-- Form này bao trọn cả thanh tìm kiếm và bộ lọc -->
    <form action="" method="GET" id="filterForm">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h3 class="fw-bold text-dark border-start border-4 border-success ps-3 m-0">
                    <?php if (!empty($keyword)): ?>
                        Kết quả tìm kiếm: "<?= htmlspecialchars($keyword) ?>"
                    <?php else: ?>
                        Sản phẩm nổi bật
                    <?php endif; ?>
                </h3>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-md-end">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control" name="keyword" placeholder="Tìm kiếm sản phẩm..." value="<?= $keyword ?? '' ?>">
                        <button class="btn btn-brand" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                    
                    <!-- Sắp xếp -->
                    <select class="form-select w-auto" name="sort" onchange="document.getElementById('filterForm').submit()">
                        <option value="newest" <?= (isset($currentSort) && $currentSort == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="price_asc" <?= (isset($currentSort) && $currentSort == 'price_asc') ? 'selected' : '' ?>>Giá thấp đến cao</option>
                        <option value="price_desc" <?= (isset($currentSort) && $currentSort == 'price_desc') ? 'selected' : '' ?>>Giá cao đến thấp</option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Product Grid -->
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($products) && count($products) > 0): ?>
            <?php foreach ($products as $p): ?>
                <?php
                    $minPrice = $p['min_price'] ?? 0;
                    $maxPrice = $p['max_price'] ?? 0;
                    
                    if ($minPrice > 0) {
                        if ($minPrice < $maxPrice) {
                            $displayPrice = number_format($minPrice, 0, ',', '.') . 'đ - ' . number_format($maxPrice, 0, ',', '.') . 'đ';
                        } else {
                            $displayPrice = number_format($minPrice, 0, ',', '.') . 'đ';
                        }
                    } else {
                        $displayPrice = '<span class="text-muted small">Liên hệ</span>';
                    }
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm product-card overflow-hidden">
                        <div class="position-relative">
                            <a href="/userproductdetail/detail/<?= $p['id'] ?>">
                                <img src="<?= !empty($p['image']) ? '/' . $p['image'] : 'https://placehold.co/300x300?text=No+Img' ?>" 
                                     class="card-img-top" 
                                     alt="<?= $p['name'] ?>"
                                     style="height: 250px; object-fit: cover;">
                            </a>
                            
                            <?php if(isset($p['is_new']) && $p['is_new']): ?>
                                <span class="position-absolute top-0 start-0 m-2 badge bg-success">Mới</span>
                            <?php endif; ?>
                            
                            <div class="position-absolute bottom-0 end-0 m-2 product-action">
                                <a href="/userproductdetail/detail/<?= $p['id'] ?>" class="btn btn-light shadow-sm rounded-circle text-brand btn-sm" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="mb-1">
                                <small class="text-muted text-uppercase" style="font-size: 0.75rem;">
                                    <?= $p['category_name'] ?? 'Sản phẩm' ?>
                                </small>
                            </div>
                            <h6 class="card-title fw-bold mb-auto">
                                <a href="/userproductdetail/detail/<?= $p['id'] ?>" class="text-decoration-none text-dark stretched-link two-line-text">
                                    <?= $p['name'] ?>
                                </a>
                            </h6>
                            
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-brand fs-5"><?= $displayPrice ?></span>
                                <small class="text-muted" style="font-size: 0.8rem;">Đã bán: <?= $p['sold_count'] ?? 0 ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-search fs-1 text-muted opacity-50"></i>
                </div>
                <h5 class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</h5>
                <p class="text-muted small">Hãy thử tìm kiếm với từ khóa khác.</p>
                <a href="?" class="btn btn-outline-brand mt-2">Xem tất cả</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if(isset($totalPages) && $totalPages > 1): ?>
    <div class="mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- Helper để tạo Link giữ nguyên Search & Sort -->
                <?php 
                    function buildUrl($page, $keyword, $sort) {
                        $query = ['page' => $page];
                        if (!empty($keyword)) $query['keyword'] = $keyword;
                        if (!empty($sort)) $query['sort'] = $sort;
                        return '?' . http_build_query($query);
                    }
                ?>

                <!-- Previous Page -->
                <li class="page-item <?= ($currentPage ?? 1) == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= buildUrl(($currentPage ?? 1) - 1, $keyword ?? '', $currentSort ?? '') ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                
                <!-- Page Numbers -->
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($currentPage ?? 1) == $i ? 'active' : '' ?>">
                        <a class="page-link" href="<?= buildUrl($i, $keyword ?? '', $currentSort ?? '') ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next Page -->
                <li class="page-item <?= ($currentPage ?? 1) == $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= buildUrl(($currentPage ?? 1) + 1, $keyword ?? '', $currentSort ?? '') ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>

</div>

<style>
    :root { --primary-color: #009981; }
    .text-brand { color: var(--primary-color) !important; }
    .btn-brand { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }
    .btn-brand:hover { background-color: #007a67; border-color: #007a67; color: white; }
    
    .product-card { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    
    .two-line-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.5em; 
    }
    
    .product-action {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }
    
    .product-card:hover .product-action {
        opacity: 1;
        transform: translateY(0);
    }

    .pagination .page-link { color: #333; border: none; margin: 0 2px; border-radius: 4px; }
    .pagination .page-link:hover { background-color: #f0f0f0; color: var(--primary-color); }
    .pagination .page-item.active .page-link { background-color: var(--primary-color); color: white; }
    .pagination .page-item.disabled .page-link { color: #ccc; background-color: transparent; }
</style>

@endsection