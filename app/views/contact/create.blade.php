<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
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
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h1 class="h3 mb-4 text-center">Contact Us</h1>
                            <p class="text-muted text-center mb-4">Have questions? We'd love to hear from you. Send us a
                                message and we'll respond as soon as possible.</p>

                            @if (isset($_SESSION['error']))
                                <div class="alert alert-danger">
                                    {{ $_SESSION['error'] }}
                                </div>
                                <?php unset($_SESSION['error']); ?>
                            @endif
                            @if (isset($_SESSION['success']))
                                <div class="alert alert-success">
                                    {{ $_SESSION['success'] }}
                                </div>
                                <?php unset($_SESSION['success']); ?>
                            @endif

                            <form action="/contact/store" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter your name" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter your email" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            placeholder="Enter your phone number" />
                                    </div>

                                    <div class="col-md-6">
                                        <label for="subject" class="form-label">Subject <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="" selected disabled>Select a subject</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="support">Technical Support</option>
                                            <option value="sales">Sales Question</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="message" class="form-label">Message <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your message here..."
                                            required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newsletter"
                                                name="newsletter" />
                                            <label class="form-check-label" for="newsletter">
                                                Subscribe to our newsletter for updates and offers
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Info Cards -->
                    <div class="row g-3 mt-4">
                        <div class="col-md-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                            <path
                                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                                        </svg>
                                    </div>
                                    <h5 class="card-title">Email</h5>
                                    <p class="card-text text-muted">contact@simpleshop.com</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                            <path
                                                d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                                        </svg>
                                    </div>
                                    <h5 class="card-title">Phone</h5>
                                    <p class="card-text text-muted">+84 123 456 789</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                            <path
                                                d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                        </svg>
                                    </div>
                                    <h5 class="card-title">Address</h5>
                                    <p class="card-text text-muted">123 Street, District 1, Ho Chi Minh City</p>
                                </div>
                            </div>
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
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
