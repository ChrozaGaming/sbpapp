var jsonDataUrl = '../public/assets/json/attendance_employee.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
;
        if ($.fn.DataTable.isDataTable('#attendance_employee_data')) {
            $('#attendance_employee_data').DataTable().destroy();
        }
        var table = $('#attendance_employee_data').DataTable({
            ordering: false,
            data: data,
            columns: [
                { data: '#' },
                { data: 'Date' },
                { data: 'PunchIn' },
                { data: 'PunchOut' },
                { data: 'Production' },
                { data: 'Break' },
                { data: 'Overtime' }
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