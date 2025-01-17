@extends('layouts/contentNavbarLayout')

@section('title', 'All Student')

@section('content')
    <div class="card">
        <h5 class="card-header">LIST OF STUDENTS</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>University Name</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="studentTableBody">

                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">

            </ul>
        </nav>
    </div>
    <script>
        function fetchStudents(page = 1) {
            fetch(`/manage/studentlist?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector(".table-border-bottom-0");
                    tbody.innerHTML = "";

                    data.data.forEach(student => {
                        tbody.innerHTML += `
                                    <tr>
                                        <td>${student.id}</td>
                                        <td>${student.name}</td>
                                        <td>${student.university}</td>
                                        <td>${student.email}</td>
                                        <td>${student.phone}</td>
                                        <td>${student.gender}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                                            class="ri-pencil-line me-1"></i> Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                                            class="ri-delete-bin-6-line me-1"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                    });

                    const pagination = document.querySelector(".pagination");
                    pagination.innerHTML = `
                        <li class="page-item ${data.prev_page_url ? "" : "disabled"}">
                            <a class="page-link" href="javascript:void(0);" onclick="fetchStudents(${data.current_page - 1})">
                            <i class="tf-icon ri-skip-back-mini-line ri-22px"></i>
                            </a>
                        </li>
                        `;

                    for (let i = 1; i <= data.last_page; i++) {
                        pagination.innerHTML += `
                            <li class="page-item ${data.current_page === i ? "active" : ""}">
                                <a class="page-link" href="javascript:void(0);" onclick="fetchStudents(${i})">${i}</a>
                            </li>
                            `;
                    }

                    pagination.innerHTML += `
                        <li class="page-item ${data.next_page_url ? "" : "disabled"}">
                            <a class="page-link" href="javascript:void(0);" onclick="fetchStudents(${data.current_page + 1})">
                            <i class="tf-icon ri-skip-forward-mini-line ri-22px"></i>
                            </a>
                        </li>
                        `;
                })
                .catch(error => console.error('Error fetching students:', error));
        }

        fetchStudents();
    </script>
@endsection
