var jsonDataUrl = '../public/assets/json/invoice_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#invoice_reports_data')) {
            $('#invoice_reports_data').DataTable().destroy();
        }
        var table = $('#invoice_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'number' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <a href="invoice-view">${data.invoiceNumber}</a>
            `;
                        return AvatarHtml;
                    }
            },
                { data: 'client' },
                { data: 'createdDate' },
                { data: 'dueDate' },
                { data: 'amount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.status === 'Paid') {
                            return ` <span class="badge bg-inverse-success">${data.status}</span>`;
                        } else {
                            return `
                            <span class="badge bg-inverse-info">${data.status}</span>
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
                                        <a class="dropdown-item" href="edit-invoice"><i
                                                class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="invoice-view"><i
                                                class="fa fa-eye m-r-5"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="fa-regular fa-file-pdf m-r-5"></i>
                                            Download</a>
                                        <a class="dropdown-item" href="#"><i class="fa-regular fa-trash-can m-r-5"></i>
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
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });