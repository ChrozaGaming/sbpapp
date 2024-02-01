var jsonDataUrl = '../public/assets/json/visited_jobs.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#visited_jobs_data')) {
            $('#visited_jobs_data').DataTable().destroy();
        }
        var table = $('#visited_jobs_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <a href="job-details">${data.JobTitle}</a>`;
                        return AvatarHtml;
                    }
                },
                { data: 'Department' },
                { data: 'StartDate' },
                { data: 'ExpireDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.JobType === 'Full Time') {
                            return ` <div class=" dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.JobType}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Part Time</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Internship</a>
                                            </div>
                        </div>`;
                        } else if (data.JobType === 'Part Time') {
                            return `<div class="dropdown action-label text-center">
                           <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                               aria-expanded="false">
                               <i class="fa-regular fa-circle-dot text-success"></i> ${data.JobType}
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
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.JobType}
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
                        if (data.Status === 'Open') {
                            return `  <div class="action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-success"></i> Cancelled</a>
                                                <a class="dropdown-item" href="#"><i
                                                    class="fa-regular fa-circle-dot text-danger"></i> Closed</a>
                                            </div>
                        </div>`;
                        } else if (data.Status === 'Closed') {
                            return `<div class="action-label text-center">
                           <a class="btn btn-white btn-sm btn-rounded" href="#" data-bs-toggle="dropdown"
                               aria-expanded="false">
                               <i class="fa-regular fa-circle-dot text-success"></i> ${data.Status}
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
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status}
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
        });

    })
    .catch(error => {
        console.error('Error:', error);
    });