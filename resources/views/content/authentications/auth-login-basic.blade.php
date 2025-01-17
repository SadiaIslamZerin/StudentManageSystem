@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6 mx-4">

                <!-- Login -->
                <div class="card p-7">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ url('/login') }}" class="app-brand-link gap-3">
                            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                            <span
                                class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-1">
                        <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! 👋🏻</h4>
                        <p class="mb-5">Please sign-in to your account</p><br>

                        <form id="formAuthentication" class="mb-5" action="{{ url('/login_Validation') }}" method="POST"
                            onsubmit="loginvalidateform(event)">
                            @csrf
                            <div class="form-floating form-floating-outline mb-5">
                                <input type="text" class="form-control" id="email" name="email-username"
                                    placeholder="Enter your email or username" autofocus required>
                                <label for="email">Email or Phone no</label>
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span id="emailError"
                                        class="font-medium text-sm text-red-600 dark:text-red-500"
                                        style="color: red;"></span>
                                </p>
                            </div>
                            <div class="mb-5">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password" required>
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="ri-eye-off-line ri-20px"></i></span>
                                    </div>
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span id="passwordError"
                                            class="font-medium text-sm text-red-600 dark:text-red-500"
                                            style="color: red;"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-5 pb-2 d-right justify-content-between pt-2 align-items-center">
                                <a href="{{ url('auth/forgot-password-basic') }}" class="float-end mb-1">
                                    <span>Forgot Password?</span>
                                </a>
                            </div>
                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                        <p class="text-center mb-5">
                            <span>Don't have account?</span>
                            <a href="{{ url('/register') }}">
                                <span>Create an account</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Login -->
                <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree"
                    class="authentication-image-object-left d-none d-lg-block">
                <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}"
                    class="authentication-image d-none d-lg-block" height="172" alt="triangle-bg">
                <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree"
                    class="authentication-image-object-right d-none d-lg-block">
            </div>
        </div>
    </div>
    <script>
        async function loginvalidateform(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');

            let flag = 1;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,12}$/;
            const phoneRegex = /^(01[3-9]\d{8})$/;

            emailError.textContent = '';
            passwordError.textContent = '';

            const form = document.getElementById('formAuthentication');
            const formData = new FormData(form);

            if (!emailRegex.test(email) && !phoneRegex.test(email)) {
                emailError.textContent = "❗Invalid email or phone no.";
                flag = 0;
            }
            if (!passwordRegex.test(password)) {
                passwordError.textContent = "❗Invalid password.";
                flag = 0;
            }

            if (flag == 1) {
                emailError.textContent = '';
                passwordError.textContent = '';
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        window.location.href = "{{ url('/Home') }}";
                    } else {
                        if (data.errors) {
                            // Show validation errors
                            if (data.errors['email-username']) {
                                emailError.textContent = data.errors['email-username'][0];
                            }
                            if (data.errors.password) {
                                passwordError.textContent = data.errors.password[0];
                            }
                        } else {
                            passwordError.textContent = data.message;
                        }
                    }
                } catch (error) {
                    alert('An error occurred. Please try again later.');
                    console.error('Error:', error);
                }

            }
            return false;
        }
    </script>
@endsection
