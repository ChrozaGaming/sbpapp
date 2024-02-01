var jsonDataUrl = '../public/assets/json/experiance_level.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#experiance_level_data')) {
            $('#experiance_level_data').DataTable().destroy();
        }
        var table = $('#experiance_level_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                { data: 'Experience' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Active') {
                            return `<div class="action-label">
                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.Status} </a>
                        </div>`;
                        } else {
                            return `
                            <div class="action-label">
                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status} </a>
                        </div>
                    `;

                        }
                    }
                },
                {
                        data: null,
                        render: function (data, type, row) {
                            var AvatarHtml = `
                            <div class="dropdown dropdown-action text-center">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#delete_job"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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