var jsonDataUrl = '../public/assets/json/interview_questions.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        if ($.fn.DataTable.isDataTable('#interview_questions_data')) {
            $('#interview_questions_data').DataTable().destroy();
        }
        var table = $('#interview_questions_data').DataTable({
            ordering: true,
            data: data,
            columns: [
                { data: '#' },
                { data: 'Questions' },
                { data: 'OptionA' },
                { data: 'OptionB' },
                { data: 'OptionC' },
                { data: 'OptionD' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var AvatarHtml = `
                        <div class="text-center">${data.CorrectAnswer}</div>
            `;
                        return AvatarHtml;
                    }
            },
                {
                        data: null,
                        render: function (data, type, row) {
                            var AvatarHtml = `
                            <div class="dropdown dropdown-action text-center">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit_question"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_employee"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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