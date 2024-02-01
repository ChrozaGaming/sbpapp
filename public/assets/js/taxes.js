var jsonDataUrl = '../public/assets/json/taxes.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/profiles/${item.image}`;
        });
        if ($.fn.DataTable.isDataTable('#taxes_data')) {
            $('#taxes_data').DataTable().destroy();
        }
        var table = $('#taxes_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                { data: '#' },
                { data: 'TaxName' },
                { data: 'TaxPercentage' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Active') {
                            return `<div class="dropdown action-label">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-success"></i> ${data.Status}
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Active</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
                            </div>
                        </div>`;
                        } else{
                           return `<div class="dropdown action-label">
                           <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                               data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status}
                           </a>
                           <div class="dropdown-menu">
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
                        var AvatarHtml = `<div class="dropdown dropdown-action text-end">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_tax"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_tax"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                        </div>
                    </div>`;
                        return AvatarHtml;
                    }
                },
            ],
            paging: false,
            searching: false,
            info: false,
            scrollX:false,
            scrollY:false
            
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });