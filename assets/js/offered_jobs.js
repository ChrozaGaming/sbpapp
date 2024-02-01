var jsonDataUrl = '../public/assets/json/offered_jobs.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.avatar = `../public/assets/img/profiles/${item.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#offered_jobs_data')) {
            $('#offered_jobs_data').DataTable().destroy();
        }
        var table = $('#offered_jobs_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <a href="job-details">${data.jobTitle} </a>`;
                        return AvatarHtml;
                    }
                },
                { data: 'department' },
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
                        var AvatarHtml = ` <a href="#" class="btn btn-sm btn-info download-offer"><span><i
                        class="fa fa-download me-1"></i> ${data.resume}</span></a>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: true,
            searching: false,
            info: true,
            order: [],
            scrollX: false,
            scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });