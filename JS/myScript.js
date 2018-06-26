
$(function() {

    getData(0, 5);
});


function getData(start, limit) {
    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        dataType: 'text',
        data: {
            key: 'existingData',
            start: start,
            limit: limit
        },
        success: function (response) {

                $('#itemTableBody').append(response);
                $('#itemTable').DataTable();
            }

    });
}

function edit(rowID) {

    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        dataType: 'json',
        data: {
            key: 'getRowData',
            rowID: rowID
        },
        success: function (response) {
            $("#editRowID").val(rowID);
            $("#product-name").val(response.itemName);
            $("#product-type").val(response.itemType);
            $("#product-brand").val(response.itemBrand);
            $("#product-color").val(response.itemColor);
            $("#product-size").val(response.itemSize);
            $("#product-price").val(response.itemPrice);
            $("#product-quantity").val(response.itemQuantity);
            $("#myModal").modal('show');
            $("#manageBtn").attr('value', 'Uloz zmeny').attr('onclick', "addItem('updateRow')");
        }
    });



}

// $(document).ready(function() {
//     $('#itemTable').DataTable( {
//         "processing": true,
//         "serverSide": true,
//         "ajax": "ajax.php"
//     } );
// } );






/**
 * Adds item to the database when the Vlozit Produkt' button is pressed
 * @param key
 */
function addItem(key) {

    var productName = $("#product-name");
    var productType = $("#product-type");
    var productBrand = $("#product-brand");
    var productColor = $("#product-color");
    var productSize = $("#product-size");
    var productPrice = $("#product-price");
    var productQuantity = $("#product-quantity");
    var editRowID = $("#editRowID");



    if (isNotProductInputEmpty(productName) && isNotProductInputEmpty(productType) && isNotProductInputEmpty(productBrand) &&
        isNotProductInputEmpty(productColor) && isNotProductInputEmpty(productSize) &&
        isNotProductInputEmpty(productPrice) && isNotProductInputEmpty(productQuantity)) {
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            dataType: 'text',
            data: {
                key: key,
                name: productName.val(),
                type: productType.val(),
                brand: productBrand.val(),
                color: productColor.val(),
                size: productSize.val(),
                price: productPrice.val(),
                quantity: productQuantity.val(),
                rowID: editRowID.val()
            }, 
            success: function (response) {
                alert(response);
            }
        });
    }

}

/**
 * Notifies the user if the input from is empty
 * @param caller
 * @returns {boolean}
 */
function isNotProductInputEmpty(caller) {
    if (caller.val() === '' || caller.value === null) {
        caller.css('border', '1px solid red');
        return false;
    } else {
        caller.css('border', '');
    }
    return true;
}

/**
 * Manages file uploads to the server
 */
function fileUploader() {
    $(function () {
        var files = $('#files');

        $('#fileupload').fileupload({
            url: 'ajax.php',
            dropZone: '#dropZone',
            dataType: 'json',
            autoUpload: false
        }).on('fileuploadadd', function (e, data) {
            var fileTypeAllowed = /.\.(gif|jpg|png|jpeg)$/i;
            var fileName = data.originalFiles[0]['name'];
            var fileSize = data.originalFiles[0]['size'];

            if(!fileTypeAllowed.test(fileName)) {
                $('#error').html('Prosim vloz image file.');
            }
            else if (fileSize > 500000) {
                $('#error').html('Tvoj obrazok je prilis velky. Max velkost obrazku je 500KB');
            } else {
                $('#error').html('');
                data.submit();
            }
        }).on('fileuploaddone', function (e, data) {
            var status = data.jqXHR.responseJSON.status;
            var msg = data.jqXHR.responseJSON.msg;

            if (statu == 1) {
                var path = data.jqXHR.responseJSON.path;
                $("#files").fadeIn().append('<p><img style="width: 30px; height: 20px;" src="'+path+'" /></p>');
            } else {
                $('#error').html(msg);
            }
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress').html('Spracovano: ' + progress + '%');
        });
    });
}
fileUploader();
