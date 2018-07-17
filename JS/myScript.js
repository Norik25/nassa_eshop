
$(function() {

    fileUploader();

    $("#myModal").on('hidden.bs.modal', function () {
        $("#product-name").val("");
        $("#product-type").val("");
        $("#product-brand").val("");
        $("#product-color").val("");
        $("#product-size").val("");
        $("#product-price").val("");
        $("#product-quantity").val("");
        $("#editRowID").val(0);
        $("#manageBtn").attr('value', 'Pridat Produkt').attr('onclick', "addItem('addNew')");
        $("#fileupload").val("");
    });
    $("#modalUserAdd").on('hidden.bs.modal', function () {
        $("#companyName").val("");
        $("#userEmail").val("");
        $("#userPhone").val("");
        $("#userAddress").val("");
        $("#userCity").val("");
        $("#userPostCode").val("");
        $("#userICO").val("");
        $("#userDIC").val("");
        $("#editUsersRowID").val(0);
        $("#manageBtnUser").attr('value', 'Pridat Uzivatele').attr('onclick', "addUser('addNewUser')");
    });
    getData(0, 5);
    getUserData(0, 5);
    login();
});




function login() {
    $("#login").on('click', function () {
        var email = $("#email").val();
        var password = $("#password").val();

        if (email == "") {
            alert('Please enter your email');
        } else if (password == "") {
            alert('Please enter your password');
        } else {
            $.ajax(
                {
                    url: 'index.php',
                    method: 'POST',
                    data: {
                        login: 1,
                        emailPHP: email,
                        passwordPHP: password,
                        dataType: 'text'
                    },
                    success: function (response) {
                        $("#response").html(response);

                        if (response.indexOf('success') >= 0) {
                            window.location = 'main.php';
                        }
                    }
                }
            );
        }
    });
}

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

}function getUserData(start, limit) {
    $.ajax({
        url: 'ajaxUsers.php',
        method: 'POST',
        dataType: 'text',
        data: {
            key: 'existingUserData',
            start: start,
            limit: limit
        },
        success: function (response) {

            $('#userTableBody').append(response);
            $('#userTable').DataTable();
        }

    });
}


function addItemToBasket(rowID) {

    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        dataType: 'json',
        data: {
            key: 'getRowData',
            rowID: rowID
        },
        success: function (response) {
            $("#getRowIDIndex").val(rowID);
            $("#itemSummaryModal").modal('show');
            $("#cardTitle").text('Nazev produktu: ' + response.itemName);
            $("#cardItemSize").text('Velikost produktu: '  + response.itemSize);
            $("#cardItemColor").text('Barva produktu: ' + response.itemColor );
            $("#cardItemPrice").text('Cena produktu: ' + response.itemPrice );
            $("#cardItemQuantity").text('Skladem: ' + response.itemQuantity + ' kusů');
            $("#cardImg").attr('src', 'uploads/' + response.itemImage).attr('alt', response.itemImage);
            $("#itemToBasketB").attr('onclick', "addItemToBasketClick("+ rowID +")");



        }
    });



}

function addItemToBasketClick(rowID) {
    var enterQuantity = $("#enterQuantity");

    if (isNotProductInputEmpty(enterQuantity)) {


        function getBasketItems(response) {
            // var qty = inputQty;
            var itemTags = '';
            for (var key in response.basketItems) {
                if (response.basketItems.hasOwnProperty(key)) {
                    // var qty = '<?php echo $_SESSION["Item' + response.basketItems[key].item['itemID'] + '"]; ?>';
                    itemTags += '<p id="' + enterQuantity.val() + '">Name: ' + response.basketItems[key].itemB['itemName'] + ' QTY: ' + response.basketItems[key].qty + '</p>';
                }
            }
            // console.log((<?php echo $_SESSION["Item' + response.basketItems[key]['itemID'] + '"]; ?>);
            console.log(itemTags);
            return itemTags;
        }
        $.ajax({
            url: 'basket.php',
            method: 'POST',
            dataType: 'json',
            data: {
                key: 'basket',
                rowID: rowID,
                qtyInput: enterQuantity.val()
            },
            success: function (response) {

                if (enterQuantity.val() > parseInt(response.itemClicked['itemQuantity'])) {
                    alert("Lutujeme ale zvoleny pocet nieje dostupny. Dostupnych je: " + response.itemClicked['itemQuantity'] + "kusu.");
                } else {
                    $("#itemSummaryModal").modal('hide');
                    // if ( $("#sideBasket").style.display === "none") {
                    //     $("#sideBasket").style.display = "block";
                    // } else {
                    //     $("#sideBasket").style.display = "none";
                    // }


                    $("#itemsInBasket").html(getBasketItems(response));


                    // $("#sideBasket").append('<p id=" rowID ">Produkt: ' + response.itemName + ', Quantity: ' + enterQuantity.val() + '' +
                    //     ', Price Total: ' + (parseFloat(enterQuantity.val()) * parseFloat(response.itemPrice))+'£' + '</p>');



                }

            }
        });
    }



}

function deleteItem(rowID) {

    if (confirm('Naozaj chcete odstranit produkt?')) {
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            dataType: 'text',
            data: {
                key: 'deleteRow',
                rowID: rowID
            },
            success: function (response) {
                $("#item_" + rowID).parent().remove();
                alert(response);
            }
        });
    }

}function deleteUser(rowID) {

    if (confirm('Naozaj chcete odstranit uzivatele?')) {
        $.ajax({
            url: 'ajaxUsers.php',
            method: 'POST',
            dataType: 'text',
            data: {
                key: 'deleteRow',
                rowID: rowID
            },
            success: function (response) {
                $("#user_" + rowID).parent().remove();
                alert(response);
            }
        });
    }

}

function editItem(rowID) {

    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        dataType: 'json',
        data: {
            key: 'getRowData',
            rowID : rowID
        },
        success: function (response) {
            // fileUploader();
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

}function editUser(rowID) {

    $.ajax({
        url: 'ajaxUsers.php',
        method: 'POST',
        dataType: 'json',
        data: {
            key: 'getRowUserData',
            rowIDUser: rowID
        },
        success: function (response) {
            // fileUploader();
            $("#editUsersRowID").val(rowID);
            $("#companyName").val(response.userCompanyName);
            $("#userEmail").val(response.userEmail);
            $("#userPhone").val(response.userPhone);
            $("#userAddress").val(response.userAddress);
            $("#userCity").val(response.userCity);
            $("#userPostCode").val(response.userPostCode);
            $("#userICO").val(response.userICO);
            $("#userDIC").val(response.userDIC);
            $("#modalUserAdd").modal('show');
            $("#manageBtnUser").attr('value', 'Uloz zmeny').attr('onclick', "addUser('updateUserRow')");


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
    var pic = $("#fileupload");



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
                rowID: editRowID.val(),
                pic: pic.val()

            },
            success: function (response) {
                if (response != 'Produkt bol uspesne upraveny.') {
                    alert(response);
                } else {

                    // $("#item_"+editRowID.val()).html(name.val());
                    productName.val('');
                    productType.val('');
                    productBrand.val('');
                    productColor.val('');
                    productSize.val('');
                    productPrice.val('');
                    productQuantity.val('');
                    $("#myModal").modal('hide');
                    $("#manageBtn").attr('value', 'Pridat Produkt').attr('onclick', "addItem('addNew')");
                }

            }
        });
    }

}/**
 * Adds user to the database when the Pridat uzivatele' button is pressed
 * @param key
 */
function addUser(key) {

    var companyName = $("#companyName");
    var userEmail = $("#userEmail");
    var userPhone = $("#userPhone");
    var userAddress = $("#userAddress");
    var userCity = $("#userCity");
    var userPostCode = $("#userPostCode");
    var userICO = $("#userICO");
    var userDIC = $("#userDIC");
    var editRowID = $("#editUsersRowID");




    if (isNotProductInputEmpty(companyName) && isNotProductInputEmpty(userEmail) && isNotProductInputEmpty(userPhone) &&
        isNotProductInputEmpty(userAddress) && isNotProductInputEmpty(userCity) &&
        isNotProductInputEmpty(userPostCode) && isNotProductInputEmpty(userICO) && isNotProductInputEmpty(userDIC)) {
        $.ajax({
            url: 'ajaxUsers.php',
            method: 'POST',
            dataType: 'text',
            data: {
                key: key,
                companyName: companyName.val(),
                userEmail: userEmail.val(),
                userPhone: userPhone.val(),
                userAddress: userAddress.val(),
                userCity: userCity.val(),
                userPostCode: userPostCode.val(),
                userICO: userICO.val(),
                userDIC: userDIC.val(),
                rowIDUser: editRowID.val()


            },
            success: function (response) {
                if (response != 'Pouzivatel bol uspense pridany.') {
                    alert(response);
                } else {
                    companyName.val('');
                    userEmail.val('');
                    userPhone.val('');
                    userAddress.val('');
                    userCity.val('');
                    userPostCode.val('');
                    userICO.val('');
                    userDIC.val('');
                    $("#modalUserAdd").modal('hide');
                    $("#manageBtnUser").attr('value', 'Pridat Uzivatele').attr('onclick', "addUser('addNewUser')");
                }

            }
        });
    }

}

/**
 * Preview of the picture
 * @param input
 */

function readImg(input) {
    if (input.files && input.files[0]) {
        input.files[0].name = Math.random() + input.files[0].name;

        var reader = new FileReader();
        reader.onload = function (e) {
            $('#files').fadeIn();
            console.log(input.files[0].name);
            $('#picPrew').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
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
        var files = $("#files");

        $('#fileupload').fileupload({
            url: 'ajax.php',
            dropZone: '#dropZone',
            dataType: 'json',
            replaceFileInput:false,
            autoUpload: false

        }).on('fileuploadadd', function (e, data) {
            var fileTypeAllowed = /.\.(gif|jpg|png|jpeg)$/i;
            // console.log(data.originalFiles[0]['name']);
            var fileName = data.originalFiles[0]['name'];
            var fileSize = data.originalFiles[0]['size'];

            if(!fileTypeAllowed.test(fileName)) {
                $('#error').html('Prosim vloz image file.');
            }
            else if (fileSize > 500000) {
                $('#error').html('Tvoj obrazok je prilis velky. Max velkost obrazku je 500KB');
            } else {
                $('#error').html("");
                readImg(data);

                $("#addImage").on('click', function () {
                    data.submit();
                });
            }
        }).on('fileuploaddone', function (e, data) {
            var status = data.jqXHR.responseJSON.status;
            var msg = data.jqXHR.responseJSON.msg;

            if (status == 1) {
                var path = data.jqXHR.responseJSON.path;
                // $("#files").fadeIn().append('<p><img style="width: 100px; height: 75px;" src="'+path+'" /></p>');
                $("#picAdd").hide();
                path = "";

            } else {
                $('#error').html(msg);
            }
        });
    });
}

