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
  $("#ID_PkWasteSCID, #ID_FkCategoryID, #ID_FkUnitID, #ID_FkPithID").attr("disabled", "disabled");
  $("#IntakeQty, #ProcessQty, #Stock, #NetBalance, #Capacity").prop('readonly',true);
  $("#form-group-ID_FkCategoryID, #form-group-ID_FkUnitID, #form-group-ID_FkPithID, #form-group-UnitGeneratedQty, #form-group-KandiKolasaQty, #form-group-BioCharQty").css('display', 'none');
  setInputFilter(document.getElementById('IntakeQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('ProcessQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('UnitGeneratedQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('KandiKolasaQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('BioCharQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });

  $('#ProcessDate').datepicker({
      endDate: new Date(),
      todayHighlight:true,
      enableOnReadonly:true,
      autoclose: true
  }).datepicker('setDate','today').datepicker('setDate','today').change(dateChanged)
    .on('changeDate', dateChanged);

});

function dateChanged(){
  $("#ID_PkWasteID, #ID_PkWasteSCID").val('').trigger('change');
  $("#CollectionQuantity, #Capacity, #IntakeQty, #Stock, #ProcessQty, #NetBalance").val('');

}

//On Waste Category Change
$('#ID_PkWasteID').on('change', function(){
  var wasteId = $(this).children('option:selected').val();
  var processDate = $("#ProcessDate").val();
  $("#ID_FkUnitID").html("");
  $("#ID_PkWasteSCID").html("");
  $("#ID_FkCategoryID").html("");
  $("#ID_FkPithID").html("");
  $.ajax({
    url: '/admin/processingdetails/getwastesubtype',
    type: 'post',
    data:  {'wasteId' : wasteId, 'processDate' : processDate},
    success: function( data ) {
        var data = JSON.parse(data);
        var cltqty = data.collectionQty; 
        $("#CollectionQuantity").val(cltqty.toFixed(2));
        $("#ID_PkWasteSCID").select2({'disabled':false});
        $("#ID_PkWasteSCID").empty();
        $.each(data.subType, function(i, v){
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
  //Initially set stock to zero always
  $("#ID_FkUnitID").html("");
  $("#ID_FkCategoryID").html("");
  $("#ID_FkPithID").html("");
  $("#Stock").val(0);
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
    $("#form-group-ID_FkCategoryID, #form-group-UnitGeneratedQty, #form-group-KandiKolasaQty, #form-group-BioCharQty").css('display', 'none');

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
    $("#form-group-ID_FkUnitID, #form-group-ID_FkPithID, #form-group-UnitGeneratedQty, #form-group-KandiKolasaQty, #form-group-BioCharQty").css('display', 'none');
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
  } else if (wasteSubId == 12){
    $("#form-group-UnitGeneratedQty").css('display', 'block');
    $("#form-group-KandiKolasaQty, #form-group-BioCharQty, #form-group-ID_FkCategoryID, #form-group-ID_FkUnitID, #form-group-ID_FkPithID").css('display', 'none');
  } else if (wasteSubId == 13){
    $("#form-group-UnitGeneratedQty, #form-group-ID_FkCategoryID, #form-group-ID_FkUnitID, #form-group-ID_FkPithID").css('display', 'none');
    $("#form-group-KandiKolasaQty, #form-group-BioCharQty").css('display', 'block');
  } else {
    $("#form-group-ID_FkCategoryID, #form-group-ID_FkUnitID, #form-group-ID_FkPithID, #form-group-UnitGeneratedQty, #form-group-KandiKolasaQty, #form-group-BioCharQty").css('display', 'none');
  }

  if($("#ID_PkWasteID option:selected").val() != "" && $("#ID_PkWasteSCID option:selected").val() != ""){
    $("#IntakeQty, #ProcessQty").prop('readonly',false);
  }

  //call to function for setting previous stock
  var params = {'wasteSubId' : wasteSubId};
  getPreviousStock(params);

});

//Intake qty On Blur event
$("#IntakeQty").on('blur', function(){
  var wasteSubId = $("#ID_PkWasteSCID").children('option:selected').val();
  var unitId = $("#ID_FkUnitID").children('option:selected').val();
  var pithId = $("#ID_FkPithID").children('option:selected').val();
  var categoryId = $("#ID_FkCategoryID").children('option:selected').val();
  var IntakeQty = $(this).val();
  var CollectionQty = $("#CollectionQuantity").val();

  if(parseFloat(IntakeQty) <= 0){
    alert('Intake quantity can not be zero or less than zero');
    $(this).val('');
    return false;
  }

  if(parseFloat(IntakeQty) > parseFloat(CollectionQty)){
    alert('Intake quantity can not be greater than collection quantity');
    $(this).val('');
    return false;
  }

  var params = {'wasteSubId' : wasteSubId, 'unitId' : unitId, 'pithId' : pithId, 'categoryId' : categoryId};
  getPreviousStock(params);
  
});

//Process qty On Blur event
$("#ProcessQty").on('blur', function(){
  var stockQty = $("#Stock").val();
  var processQty = $("#ProcessQty").val();
  var CollectionQty = $("#CollectionQuantity").val();

  if(parseFloat(processQty) <= 0){
    alert('Process quantity can not be zero or less than zero');
    $(this).val('');
    return false;
  }

  var NetBalance = parseFloat(CollectionQty) - parseFloat(processQty);
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
          var option = new Option(v.Pith+' - '+v.PithSize, v.id, false, false);
          $('#ID_FkPithID').append(option);
        });
        $('#ID_FkPithID').prepend('<option selected=""></option>').select2({placeholder: "Please select Category"});
    }
  });
});

//on change the pith set the previous quantity
$("#ID_FkPithID").on('change', function(){
    var wasteSubId = $("#ID_PkWasteSCID").children('option:selected').val();
    var unitId = $("#ID_FkUnitID").children('option:selected').val();
    var pithId = $("#ID_FkPithID").children('option:selected').val();
    var params = {'wasteSubId' : wasteSubId, 'unitId' : unitId, 'pithId' : pithId};
    getPreviousStock(params);
});

//on change the category set the previous quantity
$("#ID_FkCategoryID").on('change', function(){
    var wasteSubId = $("#ID_PkWasteSCID").children('option:selected').val();
    var categoryId = $("#ID_FkCategoryID").children('option:selected').val();
    var params = {'wasteSubId' : wasteSubId, 'categoryId' : categoryId};
    getPreviousStock(params);
});

function getPreviousStock(params){
  //get previous stock
  var IntakeQty = $("#IntakeQty").val();
  if(IntakeQty == ''){
    IntakeQty = 0;
  }
  $.ajax({
    url: '/admin/processingdetails/getpreviousstock',
    type: 'post',
    data:  params,
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
}