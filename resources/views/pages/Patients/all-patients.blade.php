@foreach($patients as $patient)
    <li>{{$patient->last_name}}</li>
    <li>{{$patient->first_name}}</li>
@endforeach
