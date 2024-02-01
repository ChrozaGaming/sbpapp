var jsonDataUrl = '../public/assets/json/holidays.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        var table1Data = data;
        var table1HTML = generateTableHTMLColor(table1Data);
        document.querySelector('#holidays_data').innerHTML = table1HTML;
    })

    .catch(error => {
        console.log('Error:', error);
    });

function generateTableHTMLColor(data) {
    var html = '';
    for (var i = 0; i < data.length; i++) {
        if (i === 0 || i === 1 || i === 2 || i === 3 || i === 4 ) {
            html += `
            <tr class="holiday-completed">
            <td>${data[i].id}</td>
            <td>${data[i].title}</td>
            <td>${data[i].holidayDate}</td>
            <td>${data[i].day}</td>
            <td></td>
          </tr>
            `;
        }else if (i === 5 || i=== 6 || i === 7) {
            html += `
            <tr class="holiday-upcoming">
           <td>${data[i].id}</td>
            <td>${data[i].title}</td>
            <td>${data[i].holidayDate}</td>
            <td>${data[i].day}</td>
            <td class="text-end">
            <div class="dropdown dropdown-action">
                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="material-icons">more_vert</i></a>
                <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                data-bs-target="#edit_holiday"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                data-bs-target="#delete_holiday"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                </div>
            </div>
            </td>
          </tr>
            `;
        }
    }
    return html;
}
