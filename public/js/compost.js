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
    setInputFilter(document.getElementById('TotalQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('compostdetailQuantity'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  $('#CompostDate').datepicker({
      endDate: new Date(),
      todayHighlight:true,
      enableOnReadonly:true,
      autoclose: true
  }).datepicker('setDate','today');
  resetData();
  $('#btn-reset-form-compostdetail').click(function(){
    resetData();
  });

  //var qtyHtml = '<div class="form-group"><label class="control-label col-sm-2">Process Quantity</label><div class="col-sm-10"><input id="ProcessQuantity" type="text" name="ProcessQuantity" class="form-control" readonly="true"></div></div>';

  //$(".child-form-area").find('.form-group:first').after(qtyHtml);

});

function resetData(){
  if($('#table-compostdetail tbody tr:not(.trNull)').length > 0){
    var totalwasteqty = 0;
    $('#compostdetailID_FkWasteSCID option').each(function(){ 
          $(this).removeAttr('disabled');
    });
    $('#compostdetailID_FkWasteSCID').select2('destroy').select2();
    $('#table-compostdetail tbody tr:not(.trNull)').each(function() {
      var wasteType = $(this).find('td.ID_FkWasteSCID').find('input').val();
      // loop each select and set the selected value to disabled in all other selects
        $('#compostdetailID_FkWasteSCID option').each(function(){ 
               if($(this).attr('value') == wasteType){            
                   $(this).prop('disabled',true);               
               }
        });
        $('#compostdetailID_FkWasteSCID').select2('destroy').select2();
        var wasteqty = $(this).find('td.Quantity').find('input').val();
      totalwasteqty = parseFloat(totalwasteqty) + parseFloat(wasteqty);
    });
    $('#TotalQty').attr('value', totalwasteqty);
  }else{
    $('#TotalQty').attr('value', 0);
  }
}

function resetDelete(){
  if($('#table-compostdetail tbody tr:not(.trNull)').length > 0){
    var totalwasteqty = 0;
    $('#compostdetailID_FkWasteSCID option').each(function(){ 
          $(this).removeAttr('disabled');
      });
    $('#compostdetailID_FkWasteSCID').select2('destroy').select2();
    $('#table-compostdetail tbody tr:not(.trNull)').each(function() {
        var wasteType = $(this).find('td.ID_FkWasteSCID').find('input').val();
        $('#compostdetailID_FkWasteSCID').find('option[value='+wasteType+']').prop('disabled',true);
        var wasteqty = $(this).find('td.Quantity').find('input').val();
      totalwasteqty = parseFloat(totalwasteqty) + parseFloat(wasteqty);
    });
    $('#compostdetailID_FkWasteSCID').select2('destroy').select2();
    $('#TotalQty').attr('value', totalwasteqty);
  }else{
    $('#compostdetailID_FkWasteSCID option').each(function(){ 
          $(this).removeAttr('disabled');
      });
    $('#TotalQty').attr('value', 0);
  }
}

/*
//On Waste Category Change
$('#compostdetailID_FkWasteSCID').on('change', function(){
  var wasteSubId = $(this).children('option:selected').val();
  var compostDate = $("#CompostDate").val();
  //Initially set stock to zero always
  $("#Stock").val(0);
  $.ajax({
    url: '/admin/compostmain/getcompostprocessstock',
    type: 'post',
    data:  {'wasteSubId' : wasteSubId, 'compostDate' : compostDate},
    success: function( data ) {
      if(data != 'null'){
        var data = JSON.parse(data);
        var ProcessQuantity = parseFloat(data);
        $("#ProcessQuantity").val(ProcessQuantity.toFixed(2));
      }else{
        $("#ProcessQuantity").val(0);
      }
    }
  });
}); 

//Process qty On Blur event
$("#compostdetailQuantity").on('blur', function(){
  var compostQty = $("#compostdetailQuantity").val();
  var processQty = $("#ProcessQuantity").val();

  if(parseFloat(compostQty) > parseFloat(processQty)){
    alert('Compost quantity can not be greater than Process Quantity');
    $(this).val('');
    return false;
  }

  if(parseFloat(compostQty) <= 0){
    alert('Compost quantity can not be zero or less than zero');
    $(this).val('');
    return false;
  }
});
*/ 