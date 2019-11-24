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
  //Initially disable all the select dropdown
  $("#ID_PkWasteSCID, #ID_FkCategoryID, #ID_FkUnitID, #ID_FkPithID").select2({'disabled':'readonly'});
  $("#IntakeQty, #ProcessQty, #Stock, #NetBalance, #Capacity").prop('readonly',true);
  $("#form-group-ID_FkCategoryID, #form-group-ID_FkUnitID, #form-group-ID_FkPithID").css('display', 'none');
  setInputFilter(document.getElementById('IntakeQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('ProcessQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });

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

//On Waste Category Change
$('#ID_PkWasteSCID').on('change', function(){
  var wasteSubId = $(this).children('option:selected').val();
  $.ajax({
    url: '/admin/processingdetails/getwastecapacity',
    type: 'post',
    data:  {'wasteSubId' : wasteSubId},
    success: function( data ) {
      if(data != 'null'){
        var data = JSON.parse(data);
        var Capacity = parseFloat(data.Capacity);
        $("#Capacity").val(Capacity.toFixed(2));
      }else{
        $("#Capacity").val(0);
      }
    }
  });

  if(wasteSubId == 4 || wasteSubId == 5) {
    $("#form-group-ID_FkUnitID, #form-group-ID_FkPithID").css('display', 'block');
    $("#ID_FkUnitID").select2({'disabled':false});
    $("#form-group-ID_FkCategoryID").css('display', 'none');

    $.ajax({
      url: '/admin/processingdetails/getunits',
      type: 'post',
      data:  {'wasteSubId' : wasteSubId},
      success: function( data ) {
        var data = JSON.parse(data);
        if(data != 'null'){
          $("#ID_FkUnitID").html("");
          $.each(data, function(i, v){
            var option = new Option(v.Units, v.id, false, false);
            $('#ID_FkUnitID').append(option);
          });
          $('#ID_FkUnitID').prepend('<option selected=""></option>').select2({placeholder: "Please select Unit"});
        }
      }
    });

  } else if (wasteSubId == 6 || wasteSubId == 7) {
    $("#form-group-ID_FkUnitID, #form-group-ID_FkPithID").css('display', 'none');
    $.ajax({
      url: '/admin/processingdetails/getwastecategory',
      type: 'post',
      data:  {'wasteSubId' : wasteSubId},
      success: function( data ) {
        var data = JSON.parse(data);
        if(data != 'null'){
          $("#ID_FkCategoryID").html("");
          $.each(data, function(i, v){
            var option = new Option(v.Category, v.id, false, false);
            $('#ID_FkCategoryID').append(option);
          });
          $('#ID_FkCategoryID').prepend('<option selected=""></option>').select2({placeholder: "Please select Category"});
        }
      }
    });
    $("#form-group-ID_FkCategoryID").css('display', 'block');
    $("#ID_FkCategoryID").select2({'disabled':false});
  } else {
    $("#form-group-ID_FkCategoryID, #form-group-ID_FkUnitID, #form-group-ID_FkPithID").css('display', 'none');
  }

  if($("#ID_PkWasteID option:selected").val() != "" && $("#ID_PkWasteSCID option:selected").val() != ""){
    $("#IntakeQty, #ProcessQty").prop('readonly',false);
  }

});

//Intake qty On Blur event
$("#IntakeQty").on('blur', function(){
  var wasteSubId = $("#ID_PkWasteSCID").children('option:selected').val();
  var unitId = $("#ID_FkUnitID").children('option:selected').val();
  var pithId = $("#ID_FkPithID").children('option:selected').val();
  var categoryId = $("#ID_FkCategoryID").children('option:selected').val();
  var IntakeQty = $(this).val();

  if(IntakeQty <= 0){
    alert('Intake quantity can not be zero or less than zero');
    $(this).val('');
    return false;
  } 
    //get previous stock
    $.ajax({
      url: '/admin/processingdetails/getpreviousstock',
      type: 'post',
      data:  {'wasteSubId' : wasteSubId, 'unitId' : unitId, 'pithId' : pithId, 'categoryId' : categoryId},
      success: function( data ) {
        if(data != 'null'){
          var data = JSON.parse(data);
          var stock = parseFloat(data) + parseFloat(IntakeQty);
          $("#Stock").val(stock.toFixed(2));
        }else{
          $("#Stock").val(0);
        }
      }
    });
});

//Process qty On Blur event
$("#ProcessQty").on('blur', function(){
  var stockQty = $("#Stock").val();
  var processQty = $("#ProcessQty").val();

  if(processQty <= 0){
    alert('Process quantity can not be zero or less than zero');
    $(this).val('');
    return false;
  }

  var NetBalance = parseFloat(stockQty) - parseFloat(processQty);
  $("#NetBalance").val(NetBalance.toFixed(2));   
});

//getPiths for Unit
$('#ID_FkUnitID').on('change', function(){
  $("#ID_FkPithID").select2({'disabled':false});
  var unitId = $(this).children('option:selected').val();
  $.ajax({
    url: '/admin/processingdetails/getpiths',
    type: 'post',
    data:  {'unitId' : unitId},
    success: function( data ) {
        var data = JSON.parse(data);
        $("#ID_FkPithID").select2({'disabled':false});
        $("#ID_FkPithID").html("");
        $.each(data, function(i, v){
          var option = new Option(v.Pith, v.id, false, false);
          $('#ID_FkPithID').append(option);
        });
        $('#ID_FkPithID').prepend('<option selected=""></option>').select2({placeholder: "Please select Category"});
    }
  });
});