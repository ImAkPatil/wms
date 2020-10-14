<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<table class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Sr. No.</th>
        <th>Collection Date</th>
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
      @foreach ($result as $row)
      <tr>
        <td>{{ $row->SrNo }}</td>
        <td>{{ $row->CollectionDate }}</td>
        <td>{{ $row->VehicalNumber }}</td>
        <td>{{ $row->VehicalCapacity }}</td>
        <td>{{ $row->TripNumber }}</td>
        <td>{{ $row->TotalWasteQty }}</td>
        <td>{{ $row->DryWasteQty }}</td>
        <td>{{ $row->WetWasteQty }}</td>
        <td>{{ $row->HazardousWasteQty }}</td>
        <td>{{ $row->EWasteQty }}</td>
      </tr>
      @endforeach
  </tbody>
</table>

@endsection