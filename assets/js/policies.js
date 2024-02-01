var jsonDataUrl = '../public/assets/json/policies.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#policies_data')) {
            $('#policies_data').DataTable().destroy();
        }
        var table = $('#policies_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                { data: 'policyName' },
                { data: 'department' },
                { data: 'description' },
                { data: 'created' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = ` <div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="fa fa-download m-r-5"></i>
                                Download</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_policy"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_policy"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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