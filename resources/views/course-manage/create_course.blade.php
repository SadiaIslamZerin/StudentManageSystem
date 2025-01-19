@extends('layouts/contentNavbarLayout')

@section('title', 'Add Course')

@section('content')
    <div class="row">
        <div class="col-xl">
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Course Manage</h5>
                    <small class="text-body float-end">Add Course</small>
                </div>
                <div class="card-body">
                    <form id="courseAddForm" action="{{ url('/manage/store-course') }}" onsubmit="validateFrom(event)">
                        @csrf
                        <div class="input-group input-group-merge mb-6">
                            <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                    class="ri-book-line ri-20px"></i></span>
                            <input type="text" class="form-control" id="basic-icon-default-fullname" name="courseName"
                                placeholder="Enter course name..." aria-label="Enter course name"
                                aria-describedby="basic-icon-default-fullname2" required />
                        </div>
                        <p class="mt-2 text-sm text-red-600"><span id="coursenameError" style="color: red;"></span></p>
                        <div class="input-group input-group-merge mb-6">
                            <span id="basic-icon-default-company2" class="input-group-text"><i
                                    class="ri-timeline-view ri-20px"></i></span>
                            <input type="number" id="basic-icon-default-company" class="form-control" name="courseDuration"
                                placeholder="Enter course duration [month]..." aria-label="Enter course duration"
                                aria-describedby="basic-icon-default-company2" required />
                        </div>
                        <p class="mt-2 text-sm text-red-600"><span id="courseDurationError" style="color: red;"></span></p>
                        <div class="form-floating form-floating-outline mb-6">
                            <input class="form-control" type="date" id="html5-date-input" name="courseStartDate" />
                            <label for="html5-date-input">Start Date</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-6">
                            <input class="form-control" type="date" id="html5-date-input" name="courseEndDate" />
                            <label for="html5-date-input">End Date</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-6">
                            <input class="form-control" type="time" id="html5-time-input" name="courseStartTime" />
                            <label for="html5-time-input">Class Start Time</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-6">
                            <input class="form-control" type="time" id="html5-time-input" name="courseEndTime" />
                            <label for="html5-time-input">Class End Time</label>
                        </div>
                        <div class="mb-6">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ri-calendar-2-fill ri-20px"></i></span>
                                <input type="text" id="basic-icon-default-email" class="form-control"
                                    placeholder="Enter class days..." aria-label="Enter class days" name="courseClassDay"
                                    aria-describedby="basic-icon-default-email2" required />
                            </div>
                            <p class="mt-2 text-sm text-red-600"><span id="classDayError" style="color: red;"></span></p>
                            <div class="form-text"> Enter Fri,Sat,Sun,Mon... </div>
                        </div>
                        <div class="input-group input-group-merge mb-6">
                            <span id="basic-icon-default-phone2" class="input-group-text"><i
                                    class="ri-money-dollar-box-line ri-20px"></i></span>
                            <input type="number" step="0.01" id="basic-icon-default-phone"
                                class="form-control phone-mask" name="courseFees" placeholder="Enter course fee..."
                                aria-label="Enter course fee" aria-describedby="basic-icon-default-phone2" required />
                        </div>
                        <p class="mt-2 text-sm text-red-600"><span id="courseFeeError" style="color: red;"></span></p>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-dismissible" id="successAlert" role="alert" style="display: none;">
            Success! Course added successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <script>
        async function validateFrom(event) {
            event.preventDefault();
            const courseName = document.getElementsByName('courseName')[0].value;
            const courseDuration = document.getElementsByName('courseDuration')[0].value;
            const courseClassDay = document.getElementsByName('courseClassDay')[0].value;
            const courseFees = document.getElementsByName('courseFees')[0].value;

            const courseNameError = document.getElementById('coursenameError');
            const courseDurationError = document.getElementById('courseDurationError');
            const classDayError = document.getElementById('classDayError');
            const courseFeeError = document.getElementById('courseFeeError');

            courseNameError.textContent = '';
            courseDurationError.textContent = '';
            classDayError.textContent = '';
            courseFeeError.textContent = '';

            let isValid = 1;

            const nameRegex = /^[a-zA-Z ]+$/;
            if (!nameRegex.test(courseName)) {
                courseNameError.textContent = '❗Course name should contain only letters and spaces.';
                isValid = 0;
            }

            if (courseDuration <= 0) {
                courseDurationError.textContent = '❗Duration must be greater than 0.';
                isValid = 0;
            }

            const classDaysRegex = /^(Fri|Sat|Sun|Mon|Tue|Wed|Thu)(, (Fri|Sat|Sun|Mon|Tue|Wed|Thu))*$/;
            if (!classDaysRegex.test(courseClassDay)) {
                classDayError.textContent = '❗Invalid class days format. Use: Fri, Sat, Sun, etc.';
                isValid = 0;
            }

            if (courseFees <= 0) {
                courseFeeError.textContent = '❗Course fee must be a positive number.';
                isValid = 0;
            }

            if (isValid === 1) {
                try {
                    const form = document.getElementById('courseAddForm');
                    const formData = new FormData(form);
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
                        document.getElementById('successAlert').style.display = 'block';
                        form.reset();
                    } else {
                        if (data.errors.courseName) {
                            document.getElementById('courseNameError').textContent = data.errors.courseName;
                        }
                        if (data.errors.courseDuration) {
                            document.getElementById('courseDurationError').textContent = data.errors.courseDuration;
                        }
                        if (data.errors.courseClassDay) {
                            document.getElementById('courseClassDayError').textContent = data.errors.courseClassDay;
                        }
                        if (data.errors.courseFees) {
                            document.getElementById('courseFeesError').textContent = data.errors.courseFees;
                        }
                    }
                } catch (error) {
                    document.getElementById('courseFeesError').textContent = error;
                }
            }
        }
    </script>
@endsection
