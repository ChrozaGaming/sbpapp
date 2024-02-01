var jsonDataUrl = '../public/assets/json/leaves_employee.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.approvedBy.avatar = `../public/assets/img/profiles/${item.approvedBy.avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#leaves_employee_data')) {
            $('#leaves_employee_data').DataTable().destroy();
        }
        var table = $('#leaves_employee_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'leaveType' },
                { data: 'from' },
                { data: 'to' },
                { data: 'noOfDays' },
                { data: 'reason' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status.text === 'New') {
                            return ` <div class="action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                <i class="fa-regular fa-circle-dot text-purple"></i> ${data.status.text}
                            </a>
                        </div>`;
                        } else if (data.status.text === 'Approved') {
                            return `<div class="action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.status.text}
                            </a>
                        </div>`;
                        } else{
                           return `<div class="action-label text-center">
                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                        <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status.text}
                                    </a>
                            </div>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <h2 class="table-avatar">
                        <a href="profile" class="avatar avatar-xs"><img
                                src="${data.approvedBy.avatar}" alt=""></a>
                        <a href="#">${data.approvedBy.name}</a>
                    </h2>`;
                        return AvatarHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `<div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_approve"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                }, 
                
            ],
            paging: true,
            searching: false,
            info: true,
            order:[[0,'asc']],
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });