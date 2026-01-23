@extends('layout.adminLayout')

@section('content')
    <!-- Main Content: Login Form -->
    <main class="container py-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white text-center py-4 border-0">
                        <h4 class="mb-1 fw-bold">Welcome Back</h4>
                        <p class="text-muted small mb-0">Please login to your account</p>
                    </div>
                    @if (isset($_SESSION['success']))
                        <div class="text-success text-center small mt-1">
                            {{ $_SESSION['success'] }}
                        </div>
                    @endif
                    <div class="card-body p-4">
                        <!-- Login Form -->
                        <form action="#" method="POST">

                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label text-muted small fw-semibold">Email
                                    Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="name@example.com" autofocus>
                            </div>

                            <!-- Password Input -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-muted small fw-semibold">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter your password">
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label small text-muted" for="remember">Remember me</label>
                                </div>
                                <a href="#" class="text-decoration-none small">Forgot password?</a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                                Sign In
                            </button>

                            <!-- Divider -->
                            <div class="position-relative my-4">
                                <hr class="text-secondary opacity-25">
                                <span
                                    class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small text-uppercase">Or</span>
                            </div>

                            <!-- Google Login Button -->
                            <a href="#"
                                class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                                    <path fill="#FFC107"
                                        d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                                    <path fill="#FF3D00"
                                        d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                                    <path fill="#4CAF50"
                                        d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                                    <path fill="#1976D2"
                                        d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                                </svg>
                                <span class="fw-semibold text-secondary">Sign in with Google</span>
                            </a>

                        </form>
                    </div>

                    <div class="card-footer bg-light text-center py-3 border-0">
                        <p class="small mb-0 text-muted">
                            Don't have an account?
                            <a href="/auth/register" class="text-decoration-none fw-semibold">Create Account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
