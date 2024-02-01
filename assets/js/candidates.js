var jsonDataUrl = '../public/assets/json/candidates.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#candidates_data')) {
            $('#candidates_data').DataTable().destroy();
        }
        var table = $('#candidates_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <h2 class="table-avatar">
                                    <a href="profile" class="avatar"><img alt=""
                                            src="${data.image}"></a>
                                    <a href="profile">${data.Name} </a>
                                </h2>
            `;
                        return imageHtml;
                    }
                },
                { data: 'MobileNumber' },
                { data: 'Email' },
                { data: 'CreatedDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="dropdown dropdown-action text-center">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_job"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                    </div>
                                </div>

            `;
                        return imageHtml;
                    }
                },
                
            ],
            paging: true,
            searching: false,
            info: true,
            // scrollX: false,
            // scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });