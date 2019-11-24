// Restricts input for the given textbox to the given inputFilter function.
function setInputFilter(textbox, inputFilter) {
  if(textbox != undefined){
      ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
      textbox.oldValue = "";
      textbox.addEventListener(event, function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        }
      });
    });
  }
}
$(function() {
  $('#SalesDate').datepicker().datepicker('setDate', 'today');
  setInputFilter(document.getElementById('SalesQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('Amount'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  $("#form-group-ID_FkCategoryID").css('display', 'none');
  $("#ID_PkWasteSCID").select2({'disabled':true});
});

//Get Category Name
$('#ID_PkWasteSCID').on('change', function(){
  var wasteSubId = $(this).children('option:selected').val();
  if(wasteSubId == 7){
    $.ajax({
      url: '/admin/salesdetails/getcategory',
      type: 'post',
      data:  {'wasteSubId' : wasteSubId},
      success: function( data ) {
          var data = JSON.parse(data);
          $("#ID_FkCategoryID").select2({'disabled':false});
          $("#ID_FkCategoryID").empty();
          $.each(data, function(i, v){
            option = new Option(v.Category, v.id);
            $('#ID_FkCategoryID').append(option);
          });
          $('#ID_FkCategoryID').prepend('<option selected=""></option>').select2({placeholder: "Please select category"});
      }
    });
    $("#form-group-ID_FkCategoryID").css('display', 'block');
  }else{
    $("#form-group-ID_FkCategoryID").css('display', 'none');
  }
});

//On Waste Category Change
$('#ID_PkWasteID').on('change', function(){
  var wasteId = $(this).children('option:selected').val();
  $.ajax({
    url: '/admin/processingdetails/getwastesubtype',
    type: 'post',
    data:  {'wasteId' : wasteId},
    success: function( data ) {
        var data = JSON.parse(data);
        $("#ID_PkWasteSCID").select2({'disabled':false});
        $("#ID_PkWasteSCID").empty();
        $.each(data, function(i, v){
          option = new Option(v.SubCategoryName, v.id);
          $('#ID_PkWasteSCID').append(option);
        });
        $('#ID_PkWasteSCID').prepend('<option selected=""></option>').select2({placeholder: "Please select waste sub type"});
    }
  });
});

//Sales quantity on blur event
$("#SalesQty").on('blur', function(){
  var salesQty = $("#SalesQty").val();
  if(salesQty <= 0){
    alert('Sales quantity can not be zero or less than zero');
    $(this).val('');
    return false;
  }   
});

//Sales Amount on blur event
$("#Amount").on('blur', function(){
  var salesAmount = $("#Amount").val();
  if(salesAmount <= 0){
    alert('Sales amount can not be zero or less than zero');
    $(this).val('');
    return false;
  }   
});