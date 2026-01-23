@extends('layout.adminLayout')

@section('content')
    <!-- Main Content: Signup Form -->
    <main class="container py-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white text-center py-4 border-0">
                        <h4 class="mb-1 fw-bold">Create an Account</h4>
                    </div>

                    <div class="card-body p-4">
                        <!-- Signup Form -->
                        <form action="/auth/storeRegister" method="POST">

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label text-muted small fw-semibold">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $_SESSION['old']['name'] ?? '' }}" placeholder="Nguyen Van A">
                                @if (isset($_SESSION['errors']['name']))
                                    <div class="text-danger small mt-1">
                                        {{ $_SESSION['errors']['name'] }}
                                    </div>
                                @endif
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label text-muted small fw-semibold">Email
                                    Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $_SESSION['old']['email'] ?? '' }}" placeholder="name@example.com">
                                @if (isset($_SESSION['errors']['email']))
                                    <div class="text-danger small mt-1">
                                        {{ $_SESSION['errors']['email'] }}
                                    </div>
                                @endif
                            </div>

                            {{-- phone --}}
                            <div class="mb-3">
                                <label for="phone" class="form-label text-muted small fw-semibold">Phone
                                    Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ $_SESSION['old']['phone'] ?? '' }}" placeholder="0123456789">
                                @if (isset($_SESSION['errors']['phone']))
                                    <div class="text-danger small mt-1">
                                        {{ $_SESSION['errors']['phone'] }}
                                    </div>
                                @endif

                                {{-- address --}}
                                <div class="mb-3">
                                    <label for="address" class="form-label text-muted small fw-semibold">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{ $_SESSION['old']['address'] ?? '' }}" placeholder="123 Street, City">
                                    @if (isset($_SESSION['errors']['address']))
                                        <div class="text-danger small mt-1">
                                            {{ $_SESSION['errors']['address'] }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Password Row -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password"
                                            class="form-label text-muted small fw-semibold">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            value="{{ $_SESSION['old']['password'] ?? '' }}" placeholder="Minimum 8 chars">
                                        @if (isset($_SESSION['errors']['password']))
                                            <div class="text-danger small mt-1">
                                                {{ $_SESSION['errors']['password'] }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mt-3 mt-md-0">
                                        <label for="confirm_password"
                                            class="form-label text-muted small fw-semibold">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password"
                                            value="{{ $_SESSION['old']['confirm_password'] ?? '' }}"
                                            placeholder="Retype password">
                                        @if (isset($_SESSION['errors']['confirm_password']))
                                            <div class="text-danger small mt-1">
                                                {{ $_SESSION['errors']['confirm_password'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Terms Checkbox -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms">
                                    <label class="form-check-label small text-muted" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a>
                                        and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                    @if (isset($_SESSION['errors']['terms']))
                                        <div class="text-danger small mt-1">
                                            {{ $_SESSION['errors']['terms'] }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                                    Create Account
                                </button>
                                @if (isset($_SESSION['success']))
                                    <div class="text-danger small mt-1">
                                        {{ $_SESSION['success'] }}
                                    </div>
                                @endif
                                @if (isset($_SESSION['error']))
                                    <div class="text-danger small mt-1">
                                        {{ $_SESSION['error'] }}
                                    </div>
                                @endif

                                <!-- Divider -->
                                <div class="position-relative my-4">
                                    <hr class="text-secondary opacity-25">
                                    <span
                                        class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small text-uppercase">Or</span>
                                </div>

                                <!-- Google Signup Button -->
                                <a href="#"
                                    class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 48 48">
                                        <path fill="#FFC107"
                                            d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                                        <path fill="#FF3D00"
                                            d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                                        <path fill="#4CAF50"
                                            d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                                        <path fill="#1976D2"
                                            d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                                    </svg>
                                    <span class="fw-semibold text-secondary">Sign up with Google</span>
                                </a>

                        </form>
                    </div>

                    <div class="card-footer bg-light text-center py-3 border-0">
                        <p class="small mb-0 text-muted">
                            Already have an account?
                            <a href="/auth/login" class="text-decoration-none fw-semibold">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
