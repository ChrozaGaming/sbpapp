var jsonDataUrl = '../public/assets/json/assets_category.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../public/assets/img/${item.image}`;
            item.edit_image = `../public/assets/img/icons/${item.edit_image}`;
            item.delete_image = `../public/assets/img/icons/${item.delete_image}`;
        });
        if ($.fn.DataTable.isDataTable('#assets_category_data')) {
            $('#assets_category_data').DataTable().destroy();
        }
        var table = $('#assets_category_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <a href="'assets-details" class="table-imgname">
                                    <img src="${data.image}" class="me-2" alt="img">
                                    <span>${data.categoryName}</span>
                                </a>

            `;
                        return imageHtml;
                    }
                },
                { data: 'createdOn' },
                {                   
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <div class="table-actions d-flex">
                                    <a class="delete-table me-2" data-bs-toggle="modal" data-bs-target="#edit-asset"
                                        href="javascript:void(0);">
                                        <img src="${data.edit_image}" alt="svg">
                                    </a>
                                    <a class="delete-table confirm-text" href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#delete_estimate">
                                        <img src="${data.delete_image}" alt="svg">
                                    </a>
                        </div>
            `;
                        return imageHtml;
                    }
                }
            ],
            paging: true,
            searching: true,
            info: true,
            scrollX:false,
            scrollY:false
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });