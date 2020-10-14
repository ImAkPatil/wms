$(document).ready(function() {
    $('#cenddt').datepicker({
        endDate: new Date(),
        format: 'yyyy/mm/dd',
        todayHighlight:true,
        enableOnReadonly:true,
        autoclose: true
    });

    $('#cstrdt').datepicker({
        endDate: new Date(),
        format: 'yyyy/mm/dd',
        todayHighlight:true,
        enableOnReadonly:true,
        autoclose: true
    });
    
  });