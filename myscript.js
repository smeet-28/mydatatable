var dataTable;
$(document).ready(function(){

    // Initialize datatable
    dataTable = $('#empTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'ajaxfile.php',
            'data': function(data){
                // Read values
                data.request = 1;
            }
        },
        'columns': [
            { data: 'roll_no' },
            { data: 'stud_fname' },
            { data: 'stud_lname' },
            { data: 'email' },          
            { data: 'dept' },
            { data: 'action' },
        ],
        'columnDefs': [ {
            'targets': [5], // column index (start from 0)
            'orderable': false,  // set orderable false for selected columns
        }],
        'dom': 'Bfrtip',
        'buttons': [
            'printHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'
        ],
    });

    // Check all 
    $('#checkall').click(function(){
        if($(this).is(':checked')){
            $('.delete_check').prop('checked', true);
        }else{
            $('.delete_check').prop('checked', false);
        }
    });

    // Delete record
    $('#delete_record').click(function(){

        var deleteids_arr = [];
        // Read all checked checkboxes
        $("input:checkbox[class=delete_check]:checked").each(function () {
            deleteids_arr.push($(this).val());
        });

        // Check checkbox checked or not
        if(deleteids_arr.length > 0){

            // Confirm alert
            var confirmdelete = confirm("Do you really want to Delete records?");
            if (confirmdelete == true) {
                $.ajax({
                    url: 'ajaxfile.php',
                    type: 'post',
                    data: {request: 2,deleteids_arr: deleteids_arr},
                    success: function(response){
                        dataTable.ajax.reload();
                    }
                });
            } 
        }
    });

});


// Checkbox checked
function checkcheckbox(){

    // Total checkboxes
    var length = $('.delete_check').length;

    // Total checked checkboxes
    var totalchecked = 0;
    $('.delete_check').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    }); 

    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#checkall").prop('checked', true);
    }else{
        $('#checkall').prop('checked', false);
    }
}
