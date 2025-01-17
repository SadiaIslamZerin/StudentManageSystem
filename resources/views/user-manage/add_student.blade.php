@extends('layouts/contentNavbarLayout')

@section('title', 'Add Student')

@section('content')
    <div class="row">
        <div class="col-xl">
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Student Manage</h5>
                    <small class="text-body float-end">Add Student</small>
                </div>
                <div class="card-body">
                    <form id="studentAddForm" action="{{ url('/manage/store_student') }}" onsubmit="validateFrom(event)">
                        @csrf
                        <div class="input-group input-group-merge mb-6">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                    class="ri-user-line ri-20px"></i></span>
                            <input type="text" class="form-control" id="basic-icon-default-fullname" name="studentName"
                                placeholder="Enter student name..." aria-label="Enter student name"
                                aria-describedby="basic-icon-default-fullname2" required />
                        </div>
                        <p class="mt-2 text-sm text-red-600"><span id="nameError" style="color: red;"></span></p>
                        <div class="input-group input-group-merge mb-6">
                            <span id="basic-icon-default-company2" class="input-group-text"><i
                                    class="ri-building-4-line ri-20px"></i></span>
                            <input type="text" id="basic-icon-default-company" class="form-control" name="universityName"
                                placeholder="Enter university name..." aria-label="Enter university name"
                                aria-describedby="basic-icon-default-company2" required />
                        </div>
                        <p class="mt-2 text-sm text-red-600"><span id="universityError" style="color: red;"></span></p>
                        <div class="mb-6">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-mail-line ri-20px"></i></span>
                                <input type="text" id="basic-icon-default-email" class="form-control"
                                    placeholder="Enter email..." aria-label="Enter email" name="studentEmail"
                                    aria-describedby="basic-icon-default-email2" required />
                                <span id="basic-icon-default-email2" class="input-group-text">@example.com</span>
                            </div>
                            <p class="mt-2 text-sm text-red-600"><span id="emailError" style="color: red;"></span></p>
                            <div class="form-text"> You can use letters, numbers & periods </div>
                        </div>
                        <div class="input-group input-group-merge mb-6">
                            <span id="basic-icon-default-phone2" class="input-group-text"><i
                                    class="ri-phone-fill ri-20px"></i></span>
                            <input type="text" id="basic-icon-default-phone" class="form-control phone-mask"
                                name="studentPhoneno" placeholder="Enter phone no..." aria-label="Enter phone number"
                                aria-describedby="basic-icon-default-phone2" required />
                        </div>
                        <p class="mt-2 text-sm text-red-600"><span id="phoneError" style="color: red;"></span></p>
                        <div class="input-group input-group-merge mb-6">
                            <h6 class="fw-medium d-block">Select Gender : </h6>&emsp;&emsp;
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="maleSelect"
                                    value="Male" />
                                <label class="form-check-label" for="maleSelect">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="femaleSelect"
                                    value="Female" />
                                <label class="form-check-label" for="femaleSelect">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="otherSelect"
                                    value="Other" />
                                <label class="form-check-label" for="otherSelect">Other</label>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-red-600"><span id="genderError" style="color: red;"></span></p>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-dismissible" id="successAlert" role="alert" style="display: none;">
            Success! Student added successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <script>
        async function validateFrom(event) {
            event.preventDefault();
            const studentName = document.getElementsByName('studentName')[0].value;
            const universityName = document.getElementsByName('universityName')[0].value;
            const studentEmail = document.getElementsByName('studentEmail')[0].value;
            const studentPhoneno = document.getElementsByName('studentPhoneno')[0].value;
            const gender = document.querySelector('input[name="inlineRadioOptions"]:checked');

            const nameError = document.getElementById('nameError');
            const universityError = document.getElementById('universityError');
            const emailError = document.getElementById('emailError');
            const phoneError = document.getElementById('phoneError');
            const genderError = document.getElementById('genderError');
            console.log(studentName, universityName);

            const nameRegex = /^[a-zA-Z. ]+$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^(01[3-9]\d{8})$/;

            nameError.textContent = '';
            universityError.textContent = '';
            emailError.textContent = '';
            phoneError.textContent = '';
            genderError.textContent = '';

            const form = document.getElementById('studentAddForm');
            const formData = new FormData(form);

            let isValid = 1;

            if (!nameRegex.test(studentName)) {
                nameError.textContent = '❗Name should contain only letters and spaces.';
                isValid = 0;
            }

            if (universityName === '') {
                universityError.textContent = '❗University name is required.';
                isValid = 0;
            }

            if (!emailRegex.test(studentEmail)) {
                emailError.textContent = '❗Invalid email address.';
                isValid = 0;
            }

            if (!phoneRegex.test(studentPhoneno)) {
                phoneError.textContent = '❗Invalid phone number.';
                isValid = 0;
            }

            if (!gender) {
                genderError.textContent = '❗Please select a gender.';
                isValid = 0;
            }

            if (isValid == 1) {
                nameError.textContent = '';
                universityError.textContent = '';
                emailError.textContent = '';
                phoneError.textContent = '';
                genderError.textContent = '';
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
                        const successAlert = document.getElementById('successAlert');
                        successAlert.style.display = 'block';
                        document.getElementById('studentAddForm').reset();
                    } else {
                        if (data.errors.studentName) {
                            nameError.textContent = data.errors.studentName;
                        }
                        if (data.errors.universityName) {
                            universityError.textContent = data.errors.universityName;
                        }
                        if (data.errors.studentEmail) {
                            emailError.textContent = data.errors.studentEmail;
                        }
                        if (data.errors.studentPhoneno) {
                            phoneError.textContent = data.errors.studentPhoneno;
                        }
                        if (data.errors.inlineRadioOptions) {
                            genderError.textContent = data.errors.inlineRadioOptions;
                        }
                    }

                } catch (error) {
                    genderError.textContent = error;
                }
            }
            return false;
        }
    </script>
@endsection
