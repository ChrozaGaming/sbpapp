var jsonDataUrl = '../public/assets/json/promotion.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        // Wrap the single object in an array
        var dataArray = [data[0]];
        
        // Update the image path
        dataArray[0].image = `../public/assets/img/profiles/${data[0].image}`;

        if ($.fn.DataTable.isDataTable('#promotion_data')) {
            $('#promotion_data').DataTable().destroy();
        }
        var table = $('#promotion_data').DataTable({
            ordering: true,
            data: dataArray, // Use the array of data
            columns: [
                { data: 'number' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                            <h2 class="table-avatar blue-link">
                                <a href="profile" class="avatar"><img alt=""
                                    src="${data.image}"></a>
                                <a href="profile">${data.PromotedEmployee}</a>
                            </h2> `;
                        return AvatarHtml;
                    }
                },
                { data: 'Department' },
                { data: 'PromotionDesignationFrom' },
                { data: 'PromotionDesignationTo' },
                { data: 'PromotionDate' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                            <div class="dropdown dropdown-action text-end">
                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#edit_promotion"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#delete_promotion"><i class="fa-regular fa-trash-can m-r-5"></i>
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
