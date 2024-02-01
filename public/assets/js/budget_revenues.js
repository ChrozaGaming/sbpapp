var jsonDataUrl = '../public/assets/json/budget_revenues.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#budget_revenues_data')) {
            $('#budget_revenues_data').DataTable().destroy();
        }
        var table = $('#budget_revenues_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                { data: 'No' },
                { data: 'Notes' },
                { data: 'CategoryName' },
                { data: 'SubCategoryName' },
                { data: 'Amount' },
                { data: 'RevenueDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="dropdown dropdown-action text-end">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit_categories"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                    </div>
                                </div>

            `;
                        return imageHtml;
                    }
                },
                
            ],
            paging: false,
            searching: false,
            info: false,
            scrollX: false,
            scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });