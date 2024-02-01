var jsonDataUrl = '../public/assets/json/performance_appraisal.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.employee.avatar = `../public/assets/img/profiles/${item.employee.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#performance_appraisal_data')) {
            $('#performance_appraisal_data').DataTable().destroy();
        }
        var table = $('#performance_appraisal_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="${data.employee.profileUrl}" class="avatar"><img alt=""
                                src="${data.employee.avatar}"></a>
                        <a href="${data.employee.profileUrl}">${data.employee.name}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                { data: 'designation' },
                { data: 'department' },
                { data: 'appraisalDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="dropdown action-label">
                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-success"></i> Active</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_appraisal"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_appraisal"><i class="fa-regular fa-trash-can m-r-5"></i>
                                Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: true,
            searching: false,
            info: true,
            order:[],
            // scrollX:false,
            // scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });