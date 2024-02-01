var jsonDataUrl = '../public/assets/json/job_applicants.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#job_applicants_data')) {
            $('#job_applicants_data').DataTable().destroy();
        }
        var table = $('#job_applicants_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'number' },
                { data: 'name' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'applyDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'New') {
                            return ` <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-info"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                    New</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Hired</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Rejected</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Interviewed</a>
                            </div>
                        </div>`;
                        } else{
                            return `
                            <div class="dropdown action-label text-center">
                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                            New</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-success"></i> Hired</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-danger"></i> Rejected</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-danger"></i> Interviewed</a>
                                    </div>
                                </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> ${data.resume}</a>
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
                                        <a class="dropdown-item" href="#"><i class="fa-regular fa-clock m-r-5"></i> Schedule
                                            Interview</a>
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
            scrollX: false,
            scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });