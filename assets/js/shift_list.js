var jsonDataUrl = '../public/assets/json/shift_list.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#shift_list_data')) {
            $('#shift_list_data').DataTable().destroy();
        }
        var table = $('#shift_list_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'number' },
                { data: 'shiftName' },
                { data: 'minStartTime' },
                { data: 'startTime' },
                { data: 'maxStartTime' },
                { data: 'minEndTime' },
                { data: 'endTime' },
                { data: 'maxEndTime' },
                { data: 'breakTime' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Active') {
                            return ` <div class="action-label">
                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.status}
                            </a>
                        </div>`;
                        } else {
                            return `div class="action-label">
                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.status}
                            </a>
                        </div>`;
                        }

                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_shift"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
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