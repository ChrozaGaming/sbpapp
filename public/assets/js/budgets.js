var jsonDataUrl = '../public/assets/json/budgets.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#budgets_data')) {
            $('#budgets_data').DataTable().destroy();
        }
        var table = $('#budgets_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                { data: 'BudgetNo' },
                { data: 'BudgetTitle' },
                { data: 'BudgetType' },
                { data: 'StartDate' },
                { data: 'EndDate' },
                { data: 'TotalRevenue' },
                { data: 'TotalExpenses' },
                { data: 'TaxAmount' },
                { data: 'BudgetAmount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="dropdown dropdown-action">
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
            // scrollX: false,
            // scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });