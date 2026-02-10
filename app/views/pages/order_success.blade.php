@extends('layout.userLayout')

@section('content')
<style>
    :root {
        --primary-color: #009981;
    }
    .text-brand { color: var(--primary-color) !important; }
    
    .btn-brand { 
        background-color: var(--primary-color); 
        border-color: var(--primary-color); 
        color: white; 
        transition: all 0.3s;
    }
    .btn-brand:hover { 
        background-color: #007a67; 
        border-color: #007a67;
        color: white; 
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .btn-outline-brand {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: transparent;
        transition: all 0.3s;
    }
    .btn-outline-brand:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
</style>

<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-5">
                <div class="mb-4 text-brand">
                    <i class="bi bi-check-circle-fill" style="font-size: 5rem;"></i>
                </div>
                <h2 class="fw-bold mb-3 text-dark">Đặt hàng thành công!</h2>
                <p class="text-muted mb-4">
                    Cảm ơn bạn đã tin tưởng và đặt hàng. Đơn hàng của bạn đã được tiếp nhận và đang trong quá trình xử lý. Chúng tôi sẽ sớm liên hệ để giao hàng cho bạn.
                </p>
                
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="/" class="btn btn-outline-brand px-4 py-2 fw-semibold rounded-pill">
                        <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                    <a href="/profile" class="btn btn-brand px-4 py-2 fw-semibold rounded-pill">
                        Xem đơn hàng<i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection