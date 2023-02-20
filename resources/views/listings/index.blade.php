@extends('layouts.layout')

@section('content')

<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
  @unless (count($listings))
    <p>No listings found</p>
  @else
    @foreach ($listings as $listing)
      <x-listing-card :listing="$listing"/>
    @endforeach
  @endunless
</div>

@endsection('content')
