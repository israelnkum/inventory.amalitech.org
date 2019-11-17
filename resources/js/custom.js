// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
$(document).ready(function () {
    $('#session_name').mask(new Date().getFullYear()+'-~~',);
    $('#phone_number').mask('+233 ~~~ ~~ ~~~~',);

    window.setTimeout(function () {
        $('.alert').fadeTo(1000,0).slideUp(1000, function () {
            $(this).remove();
        });
    },5000);

    $("#programs").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });



    /*
    Category Table
     */
    let category_table =  $("#category_table").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });
    category_table.column(2).visible(false);
    category_table.on('click','.edit',function () {

        let  $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }


        let data = category_table.row($tr).data();


        $('#edit-category_name').val(data[3]);

        $('#edit-category-form').attr('action', 'categories/'+data[2]);
        $('#edit-category-modal').modal('show');
        $('#category-title').text(data[3]);
    });


    //delete category
    let category_ids =[];
    $('#category_table tbody').on( 'click', 'td:first-child', function () {

        let  $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }
        let data = category_table.row($tr).data();
        if (!category_ids.includes(data[2])){
            category_ids.push(data[2]);
        }else{
            for( let i = 0; i < category_ids.length; i++){
                if ( category_ids[i] === data[2]) {
                    category_ids.splice(i, 1);
                }
            }
        }
        if (category_ids.length >0){
            $('#btn-delete-category').removeAttr('disabled');
        }  else{
            $('#btn-delete-category').attr('disabled','disabled');

        }
        $("#category_ids").val(category_ids);
    } );
    //End Category Table


    /*
  Area Table
   */
    let area_table =  $("#area_table").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });
    area_table.column(2).visible(false);
    area_table.on('click','.edit',function () {

        let  $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }


        let data = area_table.row($tr).data();


        $('#edit-area_name').val(data[3]);

        $('#edit-area-form').attr('action', 'areas/'+data[2]);
        $('#edit-area-modal').modal('show');
        $('#area-title').text(data[3]);
    });


    //delete area
    let area_ids =[];
    $('#area_table tbody').on( 'click', 'td:first-child', function () {

        let  $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }
        let data = area_table.row($tr).data();
        if (!area_ids.includes(data[2])){
            area_ids.push(data[2]);
        }else{
            for( let i = 0; i < area_ids.length; i++){
                if ( area_ids[i] === data[2]) {
                    area_ids.splice(i, 1);
                }
            }
        }
        if (area_ids.length >0){
            $('#btn-delete-area').removeAttr('disabled');
        }  else{
            $('#btn-delete-area').attr('disabled','disabled');

        }
        $("#area_ids").val(area_ids);
    } );
    //End area Table




    /*
  Brand Table
   */
    let brand_table =  $("#brand_table").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });
    brand_table.column(2).visible(false);
    brand_table.on('click','.edit',function () {

        let  $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }


        let data = brand_table.row($tr).data();


        $('#edit-brand_name').val(data[3]);

        $('#edit-brand-form').attr('action', 'brands/'+data[2]);
        $('#edit-brand-modal').modal('show');
        $('#brand-title').text(data[3]);
    });


    //delete area
    let brands_ids =[];
    $('#brand_table tbody').on( 'click', 'td:first-child', function () {

        let  $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }
        let data = brand_table.row($tr).data();
        if (!brands_ids.includes(data[2])){
            brands_ids.push(data[2]);
        }else{
            for( let i = 0; i < brands_ids.length; i++){
                if ( brands_ids[i] === data[2]) {
                    brands_ids.splice(i, 1);
                }
            }
        }
        if (brands_ids.length >0){
            $('#btn-delete-brand').removeAttr('disabled');
        }  else{
            $('#btn-delete-brand').attr('disabled','disabled');

        }
        $("#brand_ids").val(brands_ids);
    } );
    //End brand Table


    /*
Ownership Table
*/
    let ownership_table =  $("#ownerships_table").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });
    ownership_table.column(2).visible(false);
    ownership_table.on('click','.edit',function () {

        let  $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }


        let data = ownership_table.row($tr).data();


        $('#edit-ownerships_name').val(data[3]);

        $('#edit-ownerships-form').attr('action', 'ownerships/'+data[2]);
        $('#edit-ownerships-modal').modal('show');
        $('#ownerships-title').text(data[3]);
    });


    //delete Ownership
    let ownership_ids =[];
    $('#ownerships_table tbody').on( 'click', 'td:first-child', function () {

        let  $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }
        let data = ownership_table.row($tr).data();
        if (!ownership_ids.includes(data[2])){
            ownership_ids.push(data[2]);
        }else{
            for( let i = 0; i < ownership_ids.length; i++){
                if ( ownership_ids[i] === data[2]) {
                    ownership_ids.splice(i, 1);
                }
            }
        }
        if (ownership_ids.length >0){
            $('#btn-delete-ownerships').removeAttr('disabled');
        }  else{
            $('#btn-delete-ownerships').attr('disabled','disabled');

        }
        $("#ownerships_ids").val(ownership_ids);
    } );
    //End Ownership Table


    /*
status Table
*/
    let status_table =  $("#status_table").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });
    status_table.column(2).visible(false);
    status_table.on('click','.edit',function () {

        let  $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }


        let data = status_table.row($tr).data();


        $('#edit-status_name').val(data[3]);

        $('#edit-status-form').attr('action', 'status/'+data[2]);
        $('#edit-status-modal').modal('show');
        $('#status-title').text(data[3]);
    });


    //delete Ownership
    let status_ids =[];
    $('#status_table tbody').on( 'click', 'td:first-child', function () {

        let  $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }
        let data = status_table.row($tr).data();
        if (!status_ids.includes(data[2])){
            status_ids.push(data[2]);
        }else{
            for( let i = 0; i < status_ids.length; i++){
                if ( status_ids[i] === data[2]) {
                    status_ids.splice(i, 1);
                }
            }
        }
        if (status_ids.length >0){
            $('#btn-delete-status').removeAttr('disabled');
        }  else{
            $('#btn-delete-status').attr('disabled','disabled');

        }
        $("#status_ids").val(status_ids);
    } );
    //End status Table


    /*
item type Table
*/
    let item_type_table =  $("#item_type_table").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });
    item_type_table.column(2).visible(false);
    item_type_table.on('click','.edit',function () {

        let  $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }


        let data = item_type_table.row($tr).data();


        $('#edit-item_type_name').val(data[3]);

        $('#edit-item-type-form').attr('action', 'status/'+data[2]);
        $('#edit-item-type-modal').modal('show');
        $('#item-type-title').text(data[3]);
    });


    //delete Ownership
    let item_type_ids =[];
    $('#item_type_table tbody').on( 'click', 'td:first-child', function () {

        let  $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }
        let data = item_type_table.row($tr).data();
        if (!item_type_ids.includes(data[2])){
            status_ids.push(data[2]);
        }else{
            for( let i = 0; i < item_type_ids.length; i++){
                if ( item_type_ids[i] === data[2]) {
                    item_type_ids.splice(i, 1);
                }
            }
        }
        if (item_type_ids.length >0){
            $('#btn-delete-status').removeAttr('disabled');
        }  else{
            $('#btn-delete-status').attr('disabled','disabled');

        }
        $("#item_type_ids").val(item_type_ids);
    } );
    //End item type Table

    /*
Sessions type Table
*/
    let session_table =  $("#session_table").DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        }, order: [[ 1, 'asc' ]]
    });
    session_table.column(2).visible(false);
    session_table.on('click','.edit',function () {

        let  $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }


        let data = session_table.row($tr).data();


        $('#edit-session_name').val(data[3]);

        $('#edit-session-form').attr('action', 'sessions/'+data[2]);
        $('#edit-session-modal').modal('show');
        $('#session-title').text(data[3]);
    });


    //delete Ownership
    let session_ids =[];
    $('#session_table tbody').on( 'click', 'td:first-child', function () {

        let  $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }
        let data = session_table.row($tr).data();
        if (!session_ids.includes(data[2])){
            session_ids.push(data[2]);
        }else{
            for( let i = 0; i < session_ids.length; i++){
                if ( session_ids[i] === data[2]) {
                    session_ids.splice(i, 1);
                }
            }
        }
        if (session_ids.length >0){
            $('#btn-delete-session').removeAttr('disabled');
        }  else{
            $('#btn-delete-session').attr('disabled','disabled');
        }
        $("#session_ids").val(session_ids);
    } );
    //End item type Table

    $('.select2').select2({
        placeholder: 'Choose one',
        allowClear:true,
        searchInputPlaceholder: 'Search'
    });

    $('.choose-location').select2({
        placeholder: 'Choose one',
        allowClear:true,
        searchInputPlaceholder: 'Search'
    });



    let _URL = window.URL;
    $("#wizard-picture").change(function () {
        let file, img;
        if ((file = this.files[0])) {
            img = new Image();
            let p = document.getElementById("displayError");

            img.onload = function () {
                $('#displayError').text("");
                if (this.width !== 413  && this.height !== 531){
                    document.getElementById('wizard-picture').value = '';
                    $('#wizardPicturePreview').attr('src', 'avata.png');
                    p.innerText = p.innerText+" Picture size dimension should be  413x531 pixels";
                }
                if(file.size>500000){
                    p.innerText = p.innerText+", Image size should be be <=500kb";
                    document.getElementById('wizard-picture').value = '';
                    $('#wizardPicturePreview').attr('src', 'avata.png');
                }
            };
            img.onerror = function() {
                $('#displayError').text("");
                p.innerText = p.innerText+"Select an image";
            };
            img.src = _URL.createObjectURL(file);

        }
        readURL(this);
    });

    //Function to show image before upload

    function readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }



    $("#item-picture").change(function () {
        let file, img;
        if ((file = this.files[0])) {
            img = new Image();
            let error = document.getElementById("displayItemError");

            img.onload = function () {
                $('#displayItemError').text("");
                if(file.size>500000){
                    error.innerText = error.innerText+" Image size should be be <=500kb";
                    document.getElementById('wizard-picture').value = '';
                    $("#itemPicturePreview").attr("src",'item-default.jpg');
                }else{
                    $('#displayItemError').text("");
                }
            };
            img.onerror = function() {
                $('#displayItemError').text("");
                error.innerText = error.innerText+"Select an image";
            };
            img.src = _URL.createObjectURL(file);
        }
        previewItemPicture(this);

    });

    //Function to show image before upload

    function previewItemPicture(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#itemPicturePreview').attr('src', e.target.result).fadeIn('slow');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});
