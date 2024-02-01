var jsonDataUrl = '../public/assets/json/jobs.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#jobs_data')) {
            $('#jobs_data').DataTable().destroy();
        }
        var table = $('#jobs_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'number' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="job-details"> ${data.jobTitle}</a>
            `;
                        return AvatarHtml;
                    }
            },
                { data: 'department' },
                { data: 'startDate' },
                { data: 'expireDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.jobType === 'Full Time') {
                            return ` <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.jobType}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                    Full Time</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Part Time</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Internship</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-warning"></i> Temporary</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-warning"></i> Other</a>
                            </div>
                        </div>`;
                        } else if (data.jobType === 'Part Time') {
                            return `
                            <div class="dropdown action-label text-center">
                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-circle-dot text-success"></i> ${data.jobType}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                            Full Time</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-success"></i> Part Time</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-danger"></i> Internship</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-warning"></i> Temporary</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-warning"></i> Other</a>
                                    </div>
                                </div>`;
                        } else {
                            return `<div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.jobType}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-info"></i> Full Time</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Part Time</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Internship</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-warning"></i> Temporary</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-warning"></i> Other</a>
                            </div>
                        </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Open') {
                            return ` <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                    Open</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Closed</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Cancelled</a>
                            </div>
                        </div>`;
                        } else if (data.status === 'Closed') {
                            return `
                            <div class="dropdown action-label text-center">
                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-circle-dot text-success"></i>  ${data.status}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-info"></i> Open</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-success"></i> Closed</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-danger"></i> Cancelled</a>
                                    </div>
                                </div>`;
                        } else {
                            return ` <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i>  ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-info"></i> Open</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Closed</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Cancelled</a>
                            </div>
                        </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="job-applicants" class="btn btn-sm btn-primary">${data.applicants}</a>
            `;
                        return AvatarHtml;
                    }
            },
                {
                        data: null,
                        render: function (data, type, row) {
                            var AvatarHtml = `
                            <div class="dropdown dropdown-action text-end">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#delete_job"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                    </div>
                                </div>
                `;
                            return AvatarHtml;
                        }
                },

            ],
            paging: true,
            searching: false,
            info: true,
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });