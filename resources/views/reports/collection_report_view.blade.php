<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<table class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Sr. No.</th>
        <th>Vehical Number</th>
        <th>Capacity</th>
        <th>Trip Number</th>
        <th>Total Waste Collected</th>
        <th>Wet Waste Collected</th>
        <th>Dry Waste Collected</th>
        <th>Hazardaz Waste Collected</th>
        <th>E Waste Collected</th>
       </tr>
  </thead>
  <tbody>

  </tbody>
</table>

<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>
@endsection