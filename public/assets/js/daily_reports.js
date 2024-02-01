var jsonDataUrl = '../public/assets/json/daily_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.Employee.Avatar = `../public/assets/img/profiles/${item.Employee.Avatar}`;
        });
        if ($.fn.DataTable.isDataTable('#daily_reports_data')) {
            $('#daily_reports_data').DataTable().destroy();
        }
        var table = $('#daily_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <h2 class="table-avatar">
                            <a href="${data.Employee.ProfileURL}" class="avatar"><img alt=""
                                    src="${data.Employee.Avatar}"></a>
                            <a href="${data.Employee.ProfileURL}">${data.Employee.Name} <span>${data.Employee.EmployeeID}</span></a>
                        </h2>

            `;
                        return AvatarHtml;
                    }
                },
                { data: 'Date' },
                { data: 'Department' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Status === 'Week off') {
                            return `
                            <div class="text-center">
                                <button class="btn btn-outline-info btn-sm">${data.Status}</button>
                            </div>
                            `;
                        } else {
                            return `
                            <div class="text-center">
                                <button class="btn btn-outline-danger btn-sm">${data.Status}</button>
                            </div>
                            `;
                        }
                    }
                }
            ],
            paging: true,
            searching: false,
            info: true,
            scrollX: false,
            scrollY: false,
            order:[]
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });