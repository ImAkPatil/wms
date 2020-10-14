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
  setInputFilter(document.getElementById('collectiondetailWasteQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('TotalWasteQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  resetData();
  $('#collectiondetailID_FkWasteID').on('change', function(){
    $('#collectiondetailUOM').val('tonne');
  });

  $("#ID_FkTripID, #collectiondetailID_FkWasteID").attr("disabled", "disabled");
  $("#collectiondetailWasteQty").prop('readonly',true);

  $('#btn-reset-form-collectiondetail').click(function(){
    resetData();
      $('#collectiondetailID_FkWasteID').select2('destroy').select2();
      $("#ID_FkTripID, #collectiondetailID_FkWasteID").removeAttr("disabled");
      $('#collectiondetailID_FkWasteID option').each(function(){ 
          $(this).prop('disabled',false);
      });
      $("#collectiondetailWasteQty").removeAttr('readonly'); 
      
  });

  $('#CollectionDate').datepicker({
      endDate: new Date(),
      todayHighlight:true,
      enableOnReadonly:true,
      autoclose: true
  }).datepicker('setDate','today').change(dateChanged)
    .on('changeDate', dateChanged);

});

function dateChanged(){
  $("#ID_FkDriverID, #ID_FkTripID").val('').trigger('change');
  resetFormcollectiondetail();
}

function resetData(){
  if($('#table-collectiondetail tbody tr:not(.trNull)').length > 0){
      var totalwasteqty = 0;
    $('#table-collectiondetail tbody tr:not(.trNull)').each(function() {
      var wasteType = $(this).find('td.ID_FkWasteID').find('input').val();

      // loop each select and set the selected value to disabled in all other selects
        $('#collectiondetailID_FkWasteID option').each(function(){ 
               if($(this).attr('value') == wasteType){            
                   $(this).prop('disabled',true);               
               }
        });

      var wasteqty = $(this).find('td.WasteQty').find('input').val();
      totalwasteqty = parseFloat(totalwasteqty) + parseFloat(wasteqty);
    });
    $('#TotalWasteQty').attr('value', totalwasteqty);
  }else{
    $('#TotalWasteQty').attr('value', 0);
  }
}

function resetDelete(){
  if($('#table-collectiondetail tbody tr:not(.trNull)').length > 0){
    var totalwasteqty = 0;
    $('#collectiondetailID_FkWasteID option').each(function(){ 
          $(this).removeAttr('disabled');
    });
    $('#collectiondetailID_FkWasteID').select2('destroy').select2();
    $('#table-collectiondetail tbody tr:not(.trNull)').each(function() {
      var wasteType = $(this).find('td.ID_FkWasteID').find('input').val();
      $('#collectiondetailID_FkWasteID').find('option[value='+wasteType+']').prop('disabled',true);
      // loop each select and set the selected value to disabled in all other selects
        var wasteqty = $(this).find('td.WasteQty').find('input').val();
      totalwasteqty = parseFloat(totalwasteqty) + parseFloat(wasteqty);
    });
    $('#collectiondetailID_FkWasteID').select2('destroy').select2();
    $('#TotalWasteQty').attr('value', totalwasteqty);
  }else{
    $('#collectiondetailID_FkWasteID option').each(function(){ 
          $(this).removeAttr('disabled');
      });
    $('#TotalWasteQty').attr('value', 0);
  }
}

//get vehical trip details
$('#ID_FkDriverID').on('change', function(){
  var vehicalnumber = $(this).children('option:selected').val();
  var CollectionDate = $('#CollectionDate').val();
  $.ajax({
    url: '/admin/collectionmain/gettrips',
    type: 'post',
    data:  {'vehicalnumber' : vehicalnumber, 'CollectionDate': CollectionDate},
    success: function( data ) {
        var data = JSON.parse(data); 
        if(data.length > 0){
          $("#ID_FkTripID option").each(function (index) {
              $(this).prop('disabled', true);
          });

          $.each(data, function(i, v){
            var val = v.ID_FkTripID;
            $('#ID_FkTripID').find('option[value='+val+']').prop('disabled',true);
            $('#ID_FkTripID').val(val+1);
            $("#ID_FkTripID").find('option[value='+(val+1)+']').removeAttr('disabled').attr('selected','selected');;
          });
        }else{
            $("#ID_FkTripID option").each(function (index) {
                $(this).prop('disabled', true);
            });
            $("#ID_FkTripID option:eq(1)").removeAttr('disabled').attr('selected','selected');;
            $("#ID_FkTripID").val($("#ID_FkTripID option:eq(1)").val());
        }
        $("#collectiondetailID_FkWasteID").select2({'disabled':false});
        $("#collectiondetailWasteQty").prop('readonly',false);
        $("#ID_FkTripID").select2({'disabled':false});
        $('#ID_FkTripID').select2('destroy').select2();
    }
  });
});

//trip on change event
$('#ID_FkTripID').on('change', function(){
    $("#collectiondetailID_FkWasteID").select2({'disabled':false});
    $("#collectiondetailWasteQty").prop('readonly',false);
});