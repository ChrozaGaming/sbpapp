var jsonDataUrl = '../public/assets/json/expense_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.Image = `../public/assets/img/profiles/${item.Image}`;
        });
        if ($.fn.DataTable.isDataTable('#expense_reports_data')) {
            $('#expense_reports_data').DataTable().destroy();
        }
        var table = $('#expense_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <strong>${data.Item}</strong>

            `;
                        return AvatarHtml;
                    }
                },
                { data: 'PurchaseFrom' },
                { data: 'PurchaseDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="profile" class="avatar avatar-xs">
                                    <img src="${data.Image}" alt="">
                                </a>
                        <h2><a href="profile">${data.PurchasedBy}</a></h2>

            `;
                        return AvatarHtml;
                    }
                },
                { data: 'Amount' },
                { data: 'PaidBy' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Pending') {
                            return `<div class="dropdown action-label text-center">
                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-circle-dot text-danger"></i> ${data.Status}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-danger"></i> Pending</a>
                                <a class="dropdown-item" href="#"><i
                                        class="fa-regular fa-circle-dot text-success"></i> Approved</a>
                            </div>
                        </div>`;
                        } else {
                            return `
                        <div class="dropdown action-label">
                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-circle-dot text-success"></i> ${data.Status}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-danger"></i> Pending</a>
                            <a class="dropdown-item" href="#"><i
                                    class="fa-regular fa-circle-dot text-success"></i> Approved</a>
                        </div>
                    </div>
                    `;

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
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#delete_approve"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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
            // scrollX: false,
            // scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });