var jsonDataUrl = '../public/assets/json/dashboard_invoices.json';

  fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
      var table = $('#dashboard_invoices_data').DataTable({
        ordering: false,
        data: data,
        columns: [
          {
            data: 'invoice_id',
            render: function (data, type, row) {
              return '<a href="invoice-view">' + data + '</a>';
            }
          },
          {
            data: 'client',
            render: function (data, type, row) {
              return '<h2><a href="#">' + data + '</a></h2>';
            }
          },
          { data: 'due_date' },
          { data: 'total' },
          {
            data: 'status',
            render: function (data, type, row) {
              var badgeClass = 'bg-inverse-danger';
              if (data === 'Partially Paid') {
                badgeClass = 'bg-inverse-warning';
              } else if (data === 'Paid') {
                badgeClass = 'bg-inverse-success';
              }
              return '<span class="badge ' + badgeClass + '">' + data + '</span>';
            }
          }
        ],
        paging: false,
        searching: false,
        info: false
      });
    })
    .catch(error => {
      console.error('Error:', error);
    });