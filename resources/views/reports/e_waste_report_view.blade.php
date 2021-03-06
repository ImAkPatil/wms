<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<div class="col-lg-12" style="line-height:30px;">
  <form method="GET" action="<?php echo URL::to('admin/ewastereport'); ?>" class ="form-horizontal" role ="form">
    <div class="form-group">
      <div class="col-md-2 pull-left" id="dtrng">
        <label>Date Filter</label>
      </div>
      <div class="col-md-10">
          <div class="col-md-3" id="dtrng"> 
              <div class="input-group">
                  <span class="input-group-addon open-datetimepicker"><a><i class="fa fa-calendar "></i></a></span>
                  <input type="text" title="Start Date" readonly="" required="" class="form-control notfocus input_date" name="cstrdt" id="cstrdt" value="<?php echo $_GET['cstrdt'];?>">
              </div>    
          </div>
          <div class="col-md-1">To</div>
          <div class="col-md-3" id="dtrng">
            <div class="input-group">
                <span class="input-group-addon open-datetimepicker"><a><i class="fa fa-calendar "></i></a></span>
                <input type="text" title="End Date" readonly="" required="" class="form-control notfocus input_date" name="cenddt" id="cenddt" value="<?php echo $_GET['cenddt'];?>">
            </div> 
          </div>  
          <div class="col-md-2">
              <button type="submit" id="cstsmbt" class="btn waves-effect waves-light btn-success">Submit</button>
          </div>
      </div>
      <em id="cst-error" class="error help-block">Please select start and end dates to filter date-wise report.</em>
   </div>
 </form>
 </div>
<table id="table_dashboard" class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Sr. No.</th>
        <th>Type of Processing</th>
        <th>Actual Processing</th>
        <th>Stock Qty</th>
        <th>Sales Qty</th>
       </tr>
  </thead>
  <tbody>
      @foreach ($result as $k => $row)
      <tr>
        <td>{{ ($k + 1) }}</td>
        <td>{{ $row->SubCategoryName }}</td>
        <td>{{ $row->ActaulProcessing }}</td>
        <td>{{ $row->StockQty }}</td>
        <td>{{ $row->SalesQty }}</td>
      </tr>
      @endforeach
      <tfoot>
        <tr>
        <td colspan="2">TOTAL</td>
        <td>{{ $result->sum('ActaulProcessing')  }}</td>
        <td>{{ $result->sum('StockQty') }}</td>
        <td>{{ $result->sum('SalesQty') }}</td>
      </tr>
      </tfoot>
  </tbody>
</table>

<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>
@extends('reports.report_footer_view')
@endsection