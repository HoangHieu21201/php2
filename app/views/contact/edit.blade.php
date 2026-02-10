<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="home.html">SimpleShop</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-sm btn-outline-secondary" href="products.html">Products</a>
                <a class="btn btn-sm btn-outline-secondary" href="cart.html">Cart</a>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <a href="/contact" class="text-decoration-none text-muted">
                            &larr; Back to List
                        </a>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h1 class="h3 mb-4 text-center">Edit Contact</h1>
                            
                            @if (isset($errors))
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="/contact/update/{{ $contact['id'] }}" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        {{-- Ưu tiên hiển thị 'name' (khi nhập lỗi) rồi mới đến 'full_name' (từ DB) --}}
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $contact['name'] ?? $contact['full_name'] ?? '' }}" placeholder="Enter your name" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $contact['email'] ?? '' }}" placeholder="Enter your email" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            value="{{ $contact['phone'] ?? '' }}" placeholder="Enter your phone number" />
                                    </div>

                                    <div class="col-md-6">
                                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="" disabled>Select a subject</option>
                                            @php $subj = $contact['subject'] ?? ''; @endphp
                                            <option value="general" {{ $subj == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                            <option value="support" {{ $subj == 'support' ? 'selected' : '' }}>Technical Support</option>
                                            <option value="sales" {{ $subj == 'sales' ? 'selected' : '' }}>Sales Question</option>
                                            <option value="feedback" {{ $subj == 'feedback' ? 'selected' : '' }}>Feedback</option>
                                            <option value="other" {{ $subj == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your message here..." required>{{ $contact['message'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-12 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">Update Message</button>
                                        <a href="/contact" class="btn btn-light border">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 bg-white border-top mt-5">
        <div class="container text-center text-muted">
            <small>&copy; 2026 SimpleShop. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
</html>