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
  $('#SalesDate').datepicker({
      endDate: new Date(),
      todayHighlight:true,
      enableOnReadonly:true,
      autoclose: true
  }).datepicker('setDate','today');
  setInputFilter(document.getElementById('salesdetailSalesQty'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });
  setInputFilter(document.getElementById('salesdetailAmount'), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
  });

  var qtyHtml = '<div class="form-group"><label class="control-label col-sm-2">Stock Quantity</label><div class="col-sm-10"><input id="StockQuantity" type="text" name="StockQuantity" class="form-control" readonly="true"></div></div>';

  $(".child-form-area").find('.form-group:nth-child(2)').after(qtyHtml);

  var wstype = '<div class="form-group"><label class="control-label col-sm-2">Waste Type<span class="text-danger" title="This field is required">*</span></label><div class="col-sm-10"><select style="width:100%" class="form-control select2 required select2-hidden-accessible" id="salesdetailID_ID_PkWasteID" name="salesdetailID_ID_PkWasteID" tabindex="-1" aria-hidden="true"></select></div></div>';

  $(".child-form-area").find('.form-group:first').before(wstype);

  $("#salesdetailID_ID_PkWasteID").select2({'disabled':true});
  $("#salesdetailID_FkCategoryID").parent().parent().hide();
  $("#salesdetailID_FkCategoryID").select2({'disabled':true});
  $("#salesdetailID_PkWasteSCID").select2({'disabled':true});

  $("#salesdetailSalesQty").prop('readonly',true);
  $("#salesdetailAmount").prop('readonly',true);

  getwastetype();

  $('#btn-reset-form-salesdetail').click(function(){
    resetData();
    $('#salesdetailID_ID_PkWasteID').select2('destroy').select2();
  });

  $("#salesdetailID_FkCategoryID").html("");
  $('#salesdetailID_FkCategoryID').prepend('<option value="0" selected=""></option>').select2({placeholder: "Please select category"});
  $('#salesdetailID_FkCategoryID').select2('destroy').select2();
});

$('#ID_FkCustomerID').on('change', function(){
    $("#salesdetailID_ID_PkWasteID").select2({'disabled':false});
});

//Get Category Name
$('body').on('change', '#salesdetailID_PkWasteSCID',function(){
  var wasteSubId = $(this).children('option:selected').val();
  var wasteId = $("#salesdetailID_ID_PkWasteID").children('option:selected').val();
  if(wasteSubId == 7 || wasteSubId == 6){
    $.ajax({
      url: '/admin/salesdetails/getcategory',
      type: 'post',
      data:  {'wasteSubId' : wasteSubId},
      success: function( data ) {
          var data = JSON.parse(data);
          $("#salesdetailID_FkCategoryID").html("");
          $("#salesdetailID_FkCategoryID").parent().parent().show();
          $("#salesdetailID_FkCategoryID").select2({'disabled':false});
          $('#salesdetailID_FkCategoryID').select2('destroy').select2();
          $.each(data, function(i, v){
            option = new Option(v.Category, v.id);
            $('#salesdetailID_FkCategoryID').append(option);
          });
          $('#salesdetailID_FkCategoryID').prepend('<option value="0" selected=""></option>').select2({placeholder: "Please select category"});
      }
    });
    $("#form-group-ID_FkCategoryID").css('display', 'block');
  }else{
    $("#salesdetailID_FkCategoryID").html("");
    $("#salesdetailID_FkCategoryID").parent().parent().hide();
  }
  
  //call to function for setting previous stock
  var params = {'wasteId' : wasteId, 'wasteSubId': wasteSubId};
  getSalesStock(params);
  $("#salesdetailSalesQty").prop('readonly', false);
  $("#salesdetailAmount").prop('readonly', false);
});

//On Waste Category Change
$('body').on('change', '#salesdetailID_ID_PkWasteID', function(){
  var wasteId = $(this).children('option:selected').val();
  $.ajax({
    url: '/admin/processingdetails/getwastesubtype',
    type: 'post',
    data:  {'wasteId' : wasteId},
    success: function( data ) {
        var data = JSON.parse(data);
        $("#salesdetailID_PkWasteSCID").select2({'disabled':false});
        $("#salesdetailID_PkWasteSCID").empty();
        $.each(data.subType, function(i, v){
          option = new Option(v.SubCategoryName, v.id);
          $('#salesdetailID_PkWasteSCID').append(option);
        });
        $('#salesdetailID_PkWasteSCID').prepend('<option selected=""></option>').select2({placeholder: "Please select waste sub type"});
    }
  });
});

//Sales quantity on blur event
$("#SalesQty").on('blur', function(){
  var salesQty = $("#SalesQty").val();
  if(parseFloat(salesQty) <= 0){
    alert('Sales quantity can not be zero or less than zero');
    $(this).val('');
    return false;
  }   
});

//Sales Amount on blur event
$("#Amount").on('blur', function(){
  var salesAmount = $("#Amount").val();
  if(parseFloat(salesAmount) <= 0){
    alert('Sales amount can not be zero or less than zero');
    $(this).val('');
    return false;
  }   
});

function getSalesStock(params){
  $.ajax({
    url: '/admin/salesdetails/getsalesstock',
    type: 'post',
    data:  params,
    success: function( data ) {
      if(data != 'null'){
        var data = JSON.parse(data);
        var stock = parseFloat(data);
        $("#StockQuantity").val(stock.toFixed(2));
      }else{
        $("#StockQuantity").val(0);
      }
    }
  });
}

function getwastetype(){
  $.ajax({
    url: '/admin/salesdetails/getwastetype',
    type: 'post',
    success: function( data ) {
      var data = JSON.parse(data);
        $("#salesdetailID_ID_PkWasteID").select2({'disabled':true});
        $("#salesdetailID_ID_PkWasteID").empty();
        $.each(data, function(i, v){
          option = new Option(v.CategoryName, v.id);
          $('#salesdetailID_ID_PkWasteID').append(option);
        });
        $('#salesdetailID_ID_PkWasteID').prepend('<option selected=""></option>').select2({placeholder: "Please select waste type"});
        $('#salesdetailID_ID_PkWasteID').select2('destroy').select2();
    }
  });
}

function resetData(){
  if($('#table-salesdetail tbody tr:not(.trNull)').length > 0){
      var totalSalesQty = totalAmountQty = 0;
    $('#table-salesdetail tbody tr:not(.trNull)').each(function() {
      var wasteType = $(this).find('td.ID_FkWasteID').find('input').val();

      // loop each select and set the selected value to disabled in all other selects
        $('#salesdetailID_ID_PkWasteID option').each(function(){ 
               if($(this).attr('value') == wasteType){            
                   $(this).prop('disabled',true);               
               }
        });

      var SalesQty = $(this).find('td.SalesQty').find('input').val();
      totalSalesQty = parseFloat(totalSalesQty) + parseFloat(SalesQty);

      var AmountQty = $(this).find('td.Amount').find('input').val();
      totalAmountQty = parseFloat(totalAmountQty) + parseFloat(AmountQty);

      if($(this).find('td.ID_FkCategoryID').find('input').val() == "null"){
         $(this).find('td.ID_FkCategoryID').find('input').val(0);
      }
    });
    $('#TotalSalesQty').attr('value', totalSalesQty);
    $('#TotalAmount').attr('value', totalAmountQty);
  }else{
    $('#TotalSalesQty').attr('value', 0);
    $('#TotalAmount').attr('value', 0);
  }
}

function resetDelete(){
  if($('#table-salesdetail tbody tr:not(.trNull)').length > 0){
    var totalSalesQty = totalAmountQty = 0;
    $('#salesdetailID_ID_PkWasteID option').each(function(){ 
          $(this).removeAttr('disabled');
    });
    $('#salesdetailID_ID_PkWasteID').select2('destroy').select2();
    $('#table-salesdetail tbody tr:not(.trNull)').each(function() {
      var wasteType = $(this).find('td.ID_FkWasteID').find('input').val();
      $('#salesdetailID_ID_PkWasteID').find('option[value='+wasteType+']').prop('disabled',true);
      // loop each select and set the selected value to disabled in all other selects
      
      var SalesQty = $(this).find('td.SalesQty').find('input').val();
      totalSalesQty = parseFloat(totalSalesQty) + parseFloat(SalesQty);

      var AmountQty = $(this).find('td.Amount').find('input').val();
      totalAmountQty = parseFloat(totalAmountQty) + parseFloat(AmountQty);
    });
    $('#salesdetailID_ID_PkWasteID').select2('destroy').select2();
    
    $('#TotalSalesQty').attr('value', totalSalesQty);
    $('#TotalAmount').attr('value', totalAmountQty);
  }else{
    $('#salesdetailID_ID_PkWasteID option').each(function(){ 
          $(this).removeAttr('disabled');
      });
    $('#TotalSalesQty').attr('value', 0);
    $('#TotalAmount').attr('value', 0);
  }
}