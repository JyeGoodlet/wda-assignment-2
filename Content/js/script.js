

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
        $('.accordion2').accordion({
            collapsible: true
        });
    });


});


