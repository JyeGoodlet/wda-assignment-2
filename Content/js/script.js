

//Pagination for threads.php 
$(document).ready(function () {

    $('#subcategoryThreads').DataTable({
    "bSort": false
    });
    
//pagination for thread.php comments

    $('#threadComments').DataTable({
    "bSort": false
    });

    $(function () {
        
        $('.accordion').accordion({
            collapsible: true
        });

        AdminEditCategorySelectBoxChange();

    });

});

function AdminEditCategorySelectBoxChange() {
    if ($('#SelectCategoryName').val() == -1) {
        $('#CategoryName').val('');
        $('#adminEditCategorySubmit').prop('disabled', true);
    }
    else {
        $('#CategoryName').val($('#SelectCategoryName option:selected').text());
        $('#adminEditCategorySubmit').prop('disabled', false);
    }
}


