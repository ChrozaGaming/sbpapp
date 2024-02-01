var jsonDataUrl = '../public/assets/json/user_all_jobs.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#user_all_jobs_data')) {
            $('#user_all_jobs_data').DataTable().destroy();
        }
        var table = $('#user_all_jobs_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<a href="job-details">${data.jobTitle}</a>`;
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
            ],
            paging: true,
            searching: false,
            info: true,
            order: [[0, 'asc']],
            // scrollX: false,
            // scrollY: false
        });

    })
    .catch(error => {
        console.error('Error:', error);
    });