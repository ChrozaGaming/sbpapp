var jsonDataUrl = '../public/assets/json/departments.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#departments_data')) {
            $('#departments_data').DataTable().destroy();
        }
        var table = $('#departments_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#', className: 'text-center' },
                { data: 'DepartmentName', className: 'text-center' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <div class="dropdown dropdown-action text-center">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit_department"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_department"><i class="fa-regular fa-trash-can m-r-5"></i>
                                            Delete</a>
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
            scrollY: false,
            order:[],
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });