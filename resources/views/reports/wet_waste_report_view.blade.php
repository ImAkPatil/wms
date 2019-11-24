<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<table class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Sr. No.</th>
        <th>Type of Processing</th>
        <th>Capacity</th>
        <th>Actual Intake</th>
        <th>Actual Processing</th>
        <th>Stock</th>
       </tr>
  </thead>
  <tbody>

  </tbody>
</table>

<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>
@endsection