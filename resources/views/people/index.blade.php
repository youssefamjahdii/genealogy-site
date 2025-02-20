@extends('layouts.app')

@section('content')
<h2>List of People</h2>
<ul>
@foreach($people as $person)
    <li><a href="{{ route('people.show', $person->id) }}">{{ $person->first_name }} {{ $person->last_name }}</a> (Created by: {{ $person->creator->name }})</li>
@endforeach
</ul>
@endsection
