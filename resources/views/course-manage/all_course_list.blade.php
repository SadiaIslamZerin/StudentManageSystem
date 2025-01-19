@extends('layouts/contentNavbarLayout')

@section('title', 'All Course')

@section('content')
    <div class="card">
        <h5 class="card-header">LIST OF COURSES</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Duration</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Class Schedule</th>
                        <th>Fees</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="courseTableBody">

                </tbody>
            </table>
        </div>
    </div><br>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-end">

        </ul>
    </nav>
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col mb-6 mt-2">
                            <div class="form-floating form-floating-outline">
                                <input type="number" id="studentId" class="form-control" placeholder="Enter Student Id..."
                                    oninput="fillStudentDetails()">
                                <label for="studentId">Student ID </label>
                            </div>
                            <p class="mt-2 text-sm text-red-600"><span id="studentError" style="color: red;"></span></p>
                        </div>
                        <div class="col mb-6 mt-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="studentName" class="form-control" placeholder="Auto fill..."
                                    disabled>
                                <label for="studentName">Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="email" id="emailWithTitle" class="form-control" placeholder="Auto fill..."
                                    disabled>
                                <label for="emailWithTitle">Email</label>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="StudentphoneNo" class="form-control" placeholder="Auto fill..."
                                    disabled>
                                <label for="StudentphoneNo">Phone No. </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="enrollStudent()">Add Student</button>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-dismissible" id="successAlert" role="alert" style="display: none;">
            Success! Course added successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentlistModal">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <h5 class="card-header">LIST OF STUDENTS</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>University</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0" id="courseStudentTableBody">

                                </tbody>
                            </table>
                        </div>
                    </div><br>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end" id="modal_pagination">

                        </ul>
                    </nav>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fetchCourses(page = 1) {
            fetch(`/manage/courselist?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector(".table-border-bottom-0");
                    tbody.innerHTML = "";

                    data.data.forEach(course => {
                        tbody.innerHTML += `
                                    <tr>
                                        <td>${course.id}</td>
                                        <td>${course.name}</td>
                                        <td>${course.duration}</td>
                                        <td>${course.start_date}</td>
                                        <td>${course.end_date}</td>
                                        <td>${course.class_start_hour}</td>
                                        <td>${course.class_end_hour}</td>
                                        <td>${course.classdays}</td>
                                        <td>${course.fees}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                                            class="ri-pencil-line me-1"></i> Edit
                                                        </a>
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="deleteCourse(${course.id});">
                                                            <i class="ri-delete-bin-6-line me-1"></i> Delete
                                                        </a>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalCenter" 
                                                            data-course-name="${course.name}" data-course-id="${course.id}" data-course-start="${course.start_date}"
                                                            data-course-end="${course.end_date}"><i
                                                            class="ri-user-add-fill me-1"></i> Add Student
                                                        </a>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#largeModal" 
                                                            data-course-name="${course.name}" data-course-id="${course.id}" data-course-start="${course.start_date}"
                                                            data-course-end="${course.end_date}"><i
                                                            class="ri-file-list-2-line me-1"></i> Student List
                                                        </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                    });

                    const pagination = document.querySelector(".pagination");
                    pagination.innerHTML = `
                        <li class="page-item ${data.prev_page_url ? "" : "disabled"}">
                            <a class="page-link" href="javascript:void(0);" onclick="fetchCourses(${data.current_page - 1})">
                            <i class="tf-icon ri-skip-back-mini-line ri-22px"></i>
                            </a>
                        </li>
                        `;

                    for (let i = 1; i <= data.last_page; i++) {
                        pagination.innerHTML += `
                            <li class="page-item ${data.current_page === i ? "active" : ""}">
                                <a class="page-link" href="javascript:void(0);" onclick="fetchCourses(${i})">${i}</a>
                            </li>
                            `;
                    }

                    pagination.innerHTML += `
                        <li class="page-item ${data.next_page_url ? "" : "disabled"}">
                            <a class="page-link" href="javascript:void(0);" onclick="fetchCourses(${data.current_page + 1})">
                            <i class="tf-icon ri-skip-forward-mini-line ri-22px"></i>
                            </a>
                        </li>
                        `;
                })
                .catch(error => console.log('Error fetching courses:', error));
        }

        async function loadStudents(courseId, page = 1) {
            try {
                const response = await fetch(`/course/${courseId}/students?page=${page}`);
                const data = await response.json();

                const tbody = document.getElementById('courseStudentTableBody');
                tbody.innerHTML = '';

                data.data.forEach(student => {

                    let paymentStatusBadge = "";

                    if (student.payment_status === 'printed') {
                        paymentStatusBadge =
                            `<span class="badge rounded-pill bg-label-info me-1">Printed</span>`;
                    } else if (student.payment_status === 'paid') {
                        paymentStatusBadge =
                            `<span class="badge rounded-pill bg-label-success me-1">Paid</span>`;
                    } else if (student.payment_status === 'pending') {
                        paymentStatusBadge =
                            `<span class="badge rounded-pill bg-label-danger me-1">Pending</span>`;
                    } else {
                        paymentStatusBadge = student.payment_status;
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${student.student_id}</td>
                        <td>${student.name}</td>
                        <td>${student.email}</td>
                        <td>${student.phone}</td>
                        <td>${student.university}</td>
                        <td>${paymentStatusBadge}</td>
                    `;
                    tbody.appendChild(row);
                });

                const pagination = document.getElementById('modal_pagination');
                pagination.innerHTML = '';

                pagination.innerHTML += `
                    <li class="page-item ${data.prev_page_url ? '' : 'disabled'}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadStudents(${courseId}, ${data.current_page - 1})">
                            <i class="tf-icon ri-skip-back-mini-line ri-22px"></i>
                        </a>
                    </li>
                `;

                for (let i = 1; i <= data.last_page; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${data.current_page === i ? 'active' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="loadStudents(${courseId}, ${i})">${i}</a>
                        </li>
                    `;
                }

                pagination.innerHTML += `
                    <li class="page-item ${data.next_page_url ? '' : 'disabled'}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadStudents(${courseId}, ${data.current_page + 1})">
                            <i class="tf-icon ri-skip-forward-mini-line ri-22px"></i>
                        </a>
                    </li>
                `;

            } catch (error) {
                console.error('Error loading students:', error);
                alert('Error loading students.');
            }
        }

        function getCourseIdFromModalTitle() {
            const modalTitle = document.getElementById('modalCenterTitle').innerText;

            const regex = /\[(\d+)\]/;

            const match = modalTitle.match(regex);
            if (match && match[1]) {
                return match[1];
            }
            return null;
        }

        function deleteCourse(courseId) {
            if (!confirm('Are you sure you want to delete this course?')) {
                return;
            }

            fetch(`/manage/courses/${courseId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Course deleted successfully!');
                        location.reload();
                    } else {
                        return response.json().then(data => {
                            alert(data.message || 'Failed to delete the course.');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the course.');
                });
        }

        fetchCourses();
        const modalCenter = document.getElementById('modalCenter');
        const largeModal = document.getElementById('largeModal');

        largeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const courseName = button.getAttribute('data-course-name');
            const courseId = button.getAttribute('data-course-id');
            const start_date = button.getAttribute('data-course-start');
            const end_date = button.getAttribute('data-course-end');

            loadStudents(courseId);


            const modalTitle = largeModal.querySelector('.modal-title');
            modalTitle.textContent = `${courseName} [${courseId}] [${start_date}] ~ [${end_date}]`;
        });

        modalCenter.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const courseName = button.getAttribute('data-course-name');
            const courseId = button.getAttribute('data-course-id');
            const start_date = button.getAttribute('data-course-start');
            const end_date = button.getAttribute('data-course-end');

            const modalTitle = modalCenter.querySelector('.modal-title');
            modalTitle.textContent = `${courseName} [${courseId}] [${start_date}] ~ [${end_date}]`;
        });

        function fillStudentDetails() {
            const studentId = document.getElementById('studentId').value;
            document.getElementById('studentError').textContent = '';

            if (studentId) {
                fetch(`/manage/student/${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            document.getElementById('studentName').value = '';
                            document.getElementById('emailWithTitle').value = '';
                            document.getElementById('StudentphoneNo').value = '';
                            document.getElementById('studentError').textContent = 'Student not found';
                        } else {
                            document.getElementById('studentName').value = data.name;
                            document.getElementById('emailWithTitle').value = data.email;
                            document.getElementById('StudentphoneNo').value = data.phone;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching student details:', error);
                        alert('Failed to fetch student data');
                    });
            } else {
                document.getElementById('studentName').value = '';
                document.getElementById('emailWithTitle').value = '';
                document.getElementById('StudentphoneNo').value = '';
            }
        }

        async function enrollStudent() {
            const studentId = document.getElementById('studentId').value;
            const courseId = getCourseIdFromModalTitle();

            const errorMessage = document.getElementById('studentError').textContent;

            if (errorMessage === 'Student not found') {
                return;
            }

            if (!studentId || !courseId) {
                alert('Please provide valid student and course details.');
                return;
            }

            const formData = new FormData();
            formData.append('student_id', studentId);
            formData.append('course_id', courseId);

            try {
                const response = await fetch('/manage/course/enroll', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                if (!response.ok) {
                    throw new Error('Enrollment failed');
                }

                const data = await response.json();

                if (data.success) {
                    alert('Student added successfully');
                    document.getElementById('studentName').value = '';
                    document.getElementById('emailWithTitle').value = '';
                    document.getElementById('StudentphoneNo').value = '';
                    document.getElementById('studentId').value = '';
                } else {
                    alert('Error: ' + data.error);
                    document.getElementById('studentName').value = '';
                    document.getElementById('emailWithTitle').value = '';
                    document.getElementById('StudentphoneNo').value = '';
                    document.getElementById('studentId').value = '';
                    return;
                }
            } catch (error) {
                console.error('Error during enrollment:', error);
                alert('Something went wrong during enrollment. Please try again.');
                return;
            }
        }
    </script>
@endsection
