

//Pagination for threads.php 
$(document).ready(function () {

    $('#subcategoryThreads').DataTable({
    "bSort": false
    });
    
//pagination for thread.php comments

    $('#threadComments').DataTable({
    "bSort": false
    });

    $('#adminUserTable').DataTable({
        "bSort": false
    });

    $(function () {      

        AdminAddCategoryTextBoxChange();
        AdminEditCategorySelectBoxChange();
        AdminEditSubcategorySelectBoxChange();

    });

});

function AdminAddCategoryTextBoxChange() {
    if ($('#CategoryName').val().length > 0) {
        $('#adminAddCategorySubmit').prop('disabled', false);
    }
    else {
        $('#adminAddCategorySubmit').prop('disabled', true);
    }
}

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

function AdminEditSubcategorySelectBoxChange() {
    if ($('#SelectSubcategoryName').val() == -1) {
        $('#SubcategoryName').val('');
        $('#adminEditSubcategorySubmit').prop('disabled', true);
    }
    else {
        $('#SubcategoryName').val($('#SelectSubcategoryName option:selected').text());
        $('#adminEditSubcategorySubmit').prop('disabled', false);
    }
}


