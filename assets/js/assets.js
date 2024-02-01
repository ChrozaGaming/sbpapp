var jsonDataUrl = '../public/assets/json/assets.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
;
        if ($.fn.DataTable.isDataTable('#assets_data')) {
            $('#assets_data').DataTable().destroy();
        }
        var table = $('#assets_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'AssetUser' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <strong>${data.AssetName}</strong>

            `;
                        return imageHtml;
                    }
                },
                { data: 'AssetId' },
                { data: 'PurchaseDate' },
                { data: 'Warranty' },
                { data: 'WarrantyEnd' },
                { data: 'Amount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Pending') {
                            return `
                            <div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i>
                                    Pending</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i>
                                    Approved</a>
                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                    Returned</a>
                            </div>
                        </div>
                            `;
                        } else if (data.Status === 'Approved') {
                            return `
                            <div class="dropdown action-label">
                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-circle-dot text-success"></i> ${data.Status}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-danger"></i>
                                            Pending</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-success"></i>
                                            Approved</a>
                                        <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                            Returned</a>
                                    </div>
                                </div>
                            `;
                        } else {
                            return `
                            <div class="dropdown action-label">
                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-circle-dot text-info"></i> ${data.Status}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-danger"></i>
                                            Pending</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="fa-regular fa-circle-dot text-success"></i>
                                            Approved</a>
                                        <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i>
                                            Returned</a>
                                    </div>
                                </div>
                            </td>
                            `;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="dropdown dropdown-action text-end">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit_asset"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_asset"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                    </div>
                                </div>
            `;
                        return imageHtml;
                    }
                }
            ],
            paging: true,
            searching: false,
            info: true,
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });