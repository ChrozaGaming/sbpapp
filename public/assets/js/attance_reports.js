var jsonDataUrl = '../public/assets/json/attance_reports.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.ProfileImage = `../public/assets/img/profiles/${item.ProfileImage}`;
        });
        if ($.fn.DataTable.isDataTable('#attance_reports_data')) {
            $('#attance_reports_data').DataTable().destroy();
        }
        var table = $('#attance_reports_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <h2 class="table-avatar">
                                <a href="${data.ProfileLink}" class="avatar"><img alt="" src="${data.ProfileImage}"></a>
                                <a href="${data.ProfileLink}">${data.Name} <span>${data.Role}</span></a>
                        </h2>

            `;
                        return imageHtml;
                    }
                },
                { data: 'Date' },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (data.Shift === 'Morning Shift ( 8.0hrs )') {
                            return `<p class="mb-0">${data.Shift}</p>
                            <i class="fa fa-arrow-right text-success"></i> ${data.ArrivalTime} | <i class="fa fa-arrow-left text-danger"></i> ${data.DepartureTime}
                         `;
                        } else {
                            return ` <button class="btn btn-outline-danger btn-sm">Week off</button>
                            `;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if(data.Work === '-'){
                            return `${data.Work}`
                        } else {
                        return `
                        <button class="btn btn-outline-success btn-sm">${data.Work}</button>
                        `;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if(data.LateArrival === '-'){
                            return `${data.LateArrival}`
                        } else {
                        return `
                        <button class="btn btn-outline-warning btn-sm">${data.LateArrival}</button>
                        `;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if(data.MissingWork === '-'){
                            return `${data.MissingWork}`
                        } else {
                        return `
                        <button class="btn btn-outline-danger btn-sm">${data.MissingWork}</button>
                        `;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if(data.ExtraTime === '-'){
                            return `${data.ExtraTime}`
                        } else {
                        return `
                        <button class="btn btn-outline-success btn-sm">${data.ExtraTime}</button>     
                    `;
                        }  
                    }
                }
            ],
            paging: true,
            searching: true,
            info: true,
            scrollX: false,
            scrollY: false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });