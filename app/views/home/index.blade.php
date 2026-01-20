<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shop Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- CSS tùy chỉnh cho ảnh -->
  <style>
      .product-img {
          width: 100%;
          height: 200px; /* Chiều cao cố định */
          object-fit: cover; /* Cắt ảnh vừa khung mà không bị méo */
          object-position: center;
      }
  </style>
</head>

<body class="bg-light">

  @include('layout.components.header')

  <!-- Main -->
  <main class="container py-4">
    <div class="row g-4">

      <section class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h1 class="h4 mb-0">Products</h1>

          <div class="d-flex gap-2">
            <select class="form-select" style="max-width: 190px;">
              <option selected>Sort: Featured</option>
              <option>Price: Low to High</option>
              <option>Price: High to Low</option>
              <option>Newest</option>
            </select>
            <button class="btn btn-outline-secondary">Filter</button>
          </div>
        </div>

        <div class="row g-3">
          @foreach($products as $product)
          <div class="col-12 col-sm-6 col-xl-4">
            <div class="card h-100 shadow-sm">
              @php
                  $imgSrc = '';
                  if(empty($product['image']) || !isset($product['image'])) {
                      $imgSrc = 'image/error/err.png'; 
                  } else {
                      $imgs = explode(';', $product['image']);
                      $firstImg = $imgs[0];
                      
                      if (strpos($firstImg, 'http') === 0) {
                          $imgSrc = $firstImg;
                      } else {
                          $imgSrc = $firstImg; 
                      }
                  }
              @endphp

              <img src="{{ $imgSrc }}" class="card-img-top product-img" alt="{{ $product['name'] }}" >
              
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <h5 class="card-title mb-1 text-truncate" title="{{ $product['name'] }}">{{ $product['name'] }}</h5>
                  <span class="badge text-bg-primary">{{ $product['category_name'] ?? 'General' }}</span>
                </div>
                <p class="card-text text-muted small mb-2 text-truncate">{{ $product['description'] }}</p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="fw-semibold text-danger">${{ number_format($product['price']) }}</div>
                  {{-- <a href="#" class="btn btn-sm btn-outline-primary">View</a> --}}
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
          </ul>
        </nav>
      </section>

    </div>
  </main>

  @include('layout.components.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>