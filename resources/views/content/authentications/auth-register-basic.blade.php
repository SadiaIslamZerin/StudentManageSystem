@extends('layouts/blankLayout')

@section('title', 'Register')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection


@section('content')
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6 mx-4">

                <!-- Register Card -->
                <div class="card p-7">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ url('/login') }}" class="app-brand-link gap-3">
                            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20])</span>
                            <span
                                class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <div class="card-body mt-1">
                        <h4 class="mb-1">Adventure starts here 🚀</h4>
                        <p class="mb-5">Make your management easy and fun!</p>

                        <form id="formAuthentication" class="mb-5" action="{{ url('/store_user_register') }}"
                            onsubmit="validateForm(event)">
                            @csrf
                            <div class="form-floating form-floating-outline mb-5">
                                <input type="text" class="form-control" id="username" name="username" required
                                    placeholder="Enter your name" autofocus>
                                <label for="username">Name</label>
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span id="usernameError"
                                        class="font-medium text-sm text-red-600 dark:text-red-500"
                                        style="color: red;"></span>
                                </p>
                            </div>
                            <div class="form-floating form-floating-outline mb-5">
                                <input type="text" class="form-control" id="email" name="email" required
                                    placeholder="Enter your email or phone no.">
                                <label for="email">Email or Phone No.</label>
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span id="emailError"
                                        class="font-medium text-sm text-red-600 dark:text-red-500"
                                        style="color: red;"></span>
                                </p>
                            </div>
                            <div class="mb-5 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password" required
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" />
                                        <label for="password">Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="ri-eye-off-line ri-20px"></i></span>
                                </div>
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span id="passwordError"
                                        class="font-medium text-sm text-red-600 dark:text-red-500"
                                        style="color: red;font-size: 1rem;"></span>
                                </p>
                            </div>
                            <button class="btn btn-primary d-grid w-100 mb-5">
                                Sign up
                            </button>
                        </form>

                        <p class="text-center mb-5">
                            <span>Already have an account?</span>
                            <a href="{{ url('/login') }}">
                                <span>Sign in instead</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Register Card -->
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
        async function validateForm(event) {
            event.preventDefault();
            const username = document.getElementById('username').value;
            const usernameError = document.getElementById('usernameError');
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');

            const nameRegex = /^[a-zA-Z. ]+$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,12}$/;
            const phoneRegex = /^(01[3-9]\d{8})$/;
            usernameError.textContent = '';
            emailError.textContent = '';
            passwordError.textContent = '';

            const form = document.getElementById('formAuthentication');
            const formData = new FormData(form);

            let flag = 1;

            if (!nameRegex.test(username)) {
                usernameError.textContent = "❗Name should only contain letters and dots.";
                flag = 0;
            }
            if (!emailRegex.test(email) && !phoneRegex.test(email)) {
                emailError.textContent = "❗Invalid email address or phone no.";
                flag = 0;
            }
            if (!passwordRegex.test(password)) {
                passwordError.textContent =
                    "❗Password must be 5-12 character and contain at least one letter and one digit.";
                flag = 0;
            }

            if (flag == 1) {
                usernameError.textContent = '';
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
                        alert('Your account has been created successfully');
                        window.location.href = "{{ url('/login') }}";
                    } else {
                        if (data.errors.username) {
                            document.getElementById('usernameError').textContent = data.errors.username;
                        }
                        if (data.errors.email) {
                            document.getElementById('emailError').textContent = data.errors.email;
                        }
                        if (data.errors.password) {
                            document.getElementById('passwordError').textContent = data.errors.password;
                        }
                    }

                } catch (error) {
                    document.getElementById('passwordError').textContent = error;
                }
            }

            return false;
        }
    </script>
@endsection
