var jsonDataUrl = '../public/assets/json/assets_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.allocatedTo.profileImage = `../public/assets/img/profiles/${item.allocatedTo.profileImage}`;
            item.image = `../public/assets/img/${item.image}`;
            item.edit_image = `../public/assets/img/icons/${item.edit_image}`;
            item.delete_image = `../public/assets/img/icons/${item.delete_image}`;
        });
        if ($.fn.DataTable.isDataTable('#assets_reports_data')) {
            $('#assets_reports_data').DataTable().destroy();
        }
        var table = $('#assets_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <a href="assets-details" class="table-imgname">
                        <img src="${data.image}" class="me-2" alt="img">
                        <span>${data.name}</span>
                    </a>

            `;
                        return imageHtml;
                    }
                },
                { data: 'assetId' },
                { data: 'category' },
                { data: 'department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="table-namesplit">
                                <a href="javascript:void(0);" class="table-profileimage">
                                    <img src="${data.allocatedTo.profileImage}" class="me-2" alt="img">
                                </a>
                                <a href="javascript:void(0);" class="table-name">
                                    <span>${data.allocatedTo.name}</span>
                                    <p>${data.allocatedTo.email}</p>
                                </a>
                            </div>

            `;
                        return imageHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Assigned') {
                            return `
                        <div class="dropdown action-label">
                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle text-primary" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            ${data.status}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"> Pending</a>
                            <a class="dropdown-item" href="#">Approved</a>
                            <a class="dropdown-item" href="#">Returned</a>
                        </div>
                    </div>

            `;
                        }else {
                            return `
                            <a href="#" data-bs-toggle="modal" data-bs-target="#add-assigns"
                            class="btn btn-assign"><i class="fa fa-plus"></i> Assign </a>
                            `;
                        }
                    }
                },
                {                   
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="table-actions d-flex">
                                    <a class="delete-table me-2" data-bs-toggle="modal" data-bs-target="#edit-asset"
                                        href="javascript:void(0);">
                                        <img src="${data.edit_image}" alt="svg">
                                    </a>
                                    <a class="delete-table confirm-text" href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#delete_estimate">
                                        <img src="${data.delete_image}" alt="svg">
                                    </a>
                        </div>
            `;
                        return imageHtml;
                    }
                }
            ],
            paging: true,
            searching: true,
            info: true,
            scrollX:false,
            scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });