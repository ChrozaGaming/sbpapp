var jsonDataUrl = '../public/assets/json/leave_type.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#leave_type_data')) {
            $('#leave_type_data').DataTable().destroy();
        }
        var table = $('#leave_type_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'number' },
                { data: 'leaveType' },
                { data: 'days' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Active') {
                            return ` <div class="dropdown action-label">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                <a href="#" class="dropdown-item"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                            </div>
                        </div>`;
                        } else {
                            return `<div class="dropdown action-label">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                            </div>
                        </div>`;
                        }
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
                                data-bs-target="#edit_leavetype"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_leavetype"><i class="fa-regular fa-trash-can m-r-5"></i>
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
            order:[]
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });