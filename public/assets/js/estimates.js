var jsonDataUrl = '../public/assets/json/estimates.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#estimates_data')) {
            $('#estimates_data').DataTable().destroy();
        }
        var table = $('#estimates_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="estimate-view">${data.EstimateNumber}</a>

            `;
                        return AvatarHtml;
                    }
                },
                { data: 'Client' },
                { data: 'EstimateDate' },
                { data: 'ExpiryDate' },
                { data: 'Amount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if(data.Status === 'Accepted'){
                            return `<span class="badge bg-inverse-success">${data.Status}</span>`
                        } else if(data.Status === 'Declined'){
                        return `
                        <span class="badge bg-inverse-danger">${data.Status}</span>
                    `;
                        } else if(data.Status === 'Sent'){
                            return `<span class="badge bg-inverse-info">${data.Status}</span>`;
                        }else{
                            return `<span class="badge bg-inverse-warning">${data.Status}</span>`;
                        }

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
                                        <a class="dropdown-item" href="edit-estimate"><i class="fa fa-pencil m-r-5"></i>
                                            Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_estimate"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                    </div>
                                </div>
            `;
                        return AvatarHtml;
                    }
                },
               
            ],
            paging: false,
            searching: false,
            info: false,
            // scrollX:false,
            // scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });