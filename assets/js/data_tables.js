var jsonDataUrl = '../public/assets/json/data_tables.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#data_tables_data')) {
            $('#data_tables_data').DataTable().destroy();
        }
        var table = $('#data_tables_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'Name' },
                { data: 'Position' },
                { data: 'Office' },
                { data: 'Age' },
                { data: 'Salary' },
            ],
            paging: true,
            searching: false,
            info: true,
            scrollX: false,
            scrollY: false,
            order:[[0, 'asc']],
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });