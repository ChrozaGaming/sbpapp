var jsonDataUrl = '../public/assets/json/attendance_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#attendance_reports_data')) {
            $('#attendance_reports_data').DataTable().destroy();
        }
        var table = $('#attendance_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                { data: 'Date' },
                { data: 'ClockIn' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if(data.ClockOut === 'Week Off'){
                            return `<div colspan="3" class="text-danger text-center">${data.ClockOut}</div>`
                        } else {
                        return `
                        ${data.ClockOut}
                    `;
                        }  
                    }
                },
                { data: 'WorkStatus' },
               
            ],
            paging: true,
            searching: false,
            info: true,
            scrollX:false,
            scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });