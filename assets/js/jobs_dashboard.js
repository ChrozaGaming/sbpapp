var jsonDataUrl = '../public/assets/json/jobs_dashboard.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#jobs_dashboard_data')) {
            $('#jobs_dashboard_data').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#jobs_dashboard_data_2')) {
            $('#jobs_dashboard_data_2').DataTable().destroy();
        }
        var table1Data = data.slice(0, 3); 
        var table2Data = data.slice(3); 
        var table = $('#jobs_dashboard_data').DataTable({
            ordering: false,
            data: table1Data,
            columns: [
                { data: 'number' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <h2 class="table-avatar">
                        <a href="'profile" class="avatar"><img alt=""
                                src="${data.image}"></a>
                        <a href="'profile">${data.name} <span>${data.designation}</span></a>
                    </h2>
            `;
                        return AvatarHtml;
                    }
            },
            {
                data: null,
                render: function (data, type, row) {
                    var AvatarHtml = `
                    <a href="job-details">${data.jobTitle}</a> `;
                    return AvatarHtml;
                }
        },
                
                { data: 'departments' },
                { data: 'startDate' },
                { data: 'expireDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.jobTypes === 'Full Time') {
                            return ` <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.jobTypes}
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
                        } else if (data.jobTypes === 'Part Time'){
                            return `
                            <div class="dropdown action-label text-center">
                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.jobTypes}
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
                        } else {
                            return ` <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.jobTypes}
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
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-info"></i> Open</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Closed</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Cancelled</a>
                            </div>
                        </div>`;
                        } else if (data.status === 'Closed'){
                            return `
                            <div class="dropdown action-label text-center">
                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
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
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
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
                        <a href="javascript:void(0);"
                            class="btn btn-sm btn-primary"><i class="fa fa-download me-1"></i>${data.resume}</a>`;
                        return AvatarHtml;
                    }
            },
                {
                        data: null,
                        render: function (data, type, row) {
                            var AvatarHtml = `
                            <div class="dropdown dropdown-action text-center">
                                            <a href="#" class="action-icon dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#delete_job"><i class="fa-regular fa-trash-can m-r-5"></i>
                                                    Delete</a>
                                            </div>
                                        </div>
                `;
                            return AvatarHtml;
                        }
                },

            ],
            paging: false,
            searching: false,
            info: false,
        });
        var table = $('#jobs_dashboard_data_2').DataTable({
            ordering: false,
            data: table2Data,
            columns: [
                { data: 'number' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <h2 class="table-avatar">
                        <a href="'profile" class="avatar"><img alt=""
                                src="${data.image}"></a>
                        <a href="'profile">${data.name} <span>${data.designation}</span></a>
                    </h2>
            `;
                        return AvatarHtml;
                    }
            },
            {
                data: null,
                render: function (data, type, row) {
                    var AvatarHtml = `
                    <a href="job-details">${data.jobTitle}</a> `;
                    return AvatarHtml;
                }
        },
                
                { data: 'departments' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <div class="action-label text-center">
                                            <a class="btn btn-white btn-sm btn-rounded" href="#">
                                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                                            </a>
                        </div>`;
                        return AvatarHtml;
                    }
            },
            ],
            paging: false,
            searching: false,
            info: false,
            scrollX:false,
            scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });