<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your html goes here -->
<div class='panel panel-default'>
  <div class='panel-heading'>Sales Details</div>
  <div class='panel-body'>      
    <div class="box-body" id="parent-form-area">

      <div class="table-responsive">
        <table id="table-detail" class="table table-striped">
          <tbody>
            <tr>
              <td>Customer Name</td>
              <td>{{ $row[0]->CustomerName }}</td>
            </tr>
            <tr>
              <td>Sales Date</td>
              <td>{{date('F, d Y',strtotime($row[0]->SalesDate))}}</td>
            </tr>
            <tr>
              <td colspan="2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <i class="fa fa-bars"></i> Sales Sub Detail
                  </div>
                  <div class="panel-body">
                    <table id="table-compostdetail" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Waste Sub type</th>
                          <th>Category</th>
                          <th>Quantity</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($row as $data)
                        <tr>
                          <td>{{ $data->SubCategoryName }}</td>
                          <td>{{ $data->Category }}</td>
                          <td>{{ $data->SalesQty }}</td>
                          <td>{{ $data->Amount }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Total Sales Quantity</td>
              <td>{{ $row[0]->TotalSalesQty }}</td>
            </tr>
            <tr>
              <td>Total Amount</td>
              <td>{{ $row[0]->TotalAmount }}</td>
            </tr>
          </tbody>
        </table>
      </div>                                            
    </div>
  </div>
</div>
@endsection