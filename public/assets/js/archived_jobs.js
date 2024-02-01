var jsonDataUrl = '../public/assets/json/archived_jobs.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#archived_jobs_data')) {
            $('#archived_jobs_data').DataTable().destroy();
        }
        var table = $('#archived_jobs_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <a href="job-details">${data.jobTitle}</a>

            `;
                        return imageHtml;
                    }
                },
                { data: 'department' },
                { data: 'startDate' },
                { data: 'expireDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.jobType === 'Full Time') {
                            return ` <div class=" dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.jobType}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Part Time</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Internship</a>
                                            </div>
                        </div>`;
                        } else if (data.jobType === 'Part Time') {
                            return `<div class="dropdown action-label text-center">
                           <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                               aria-expanded="false">
                               <i class="fa-regular fa-circle-dot text-success"></i> ${data.jobType}
                           </a>
                           <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Full TIme</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Internship</a>
                                            </div>
                       </div>`;
                        } else {
                            return `<div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.jobType}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Part Time</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Full Time</a>
                                            </div>
                        </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Open') {
                            return `  <div class="action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Cancelled</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Closed</a>
                                            </div>
                        </div>`;
                        } else if (data.status === 'Closed') {
                            return `<div class="action-label text-center">
                           <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                               aria-expanded="false">
                               <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
                           </a>
                           <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Open</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Cancelled</a>
                                            </div>
                       </div>`;
                        } else {
                            return `<div class="action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Open</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Closed</a>
                                            </div>
                        </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="dropdown dropdown-action text-center">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_employee"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                    </div>
                                </div>
            `;
                        return imageHtml;
                    }
                }
            ],
            paging: true,
            searching: false,
            info: true,
            // scrollX: false,
            // scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });