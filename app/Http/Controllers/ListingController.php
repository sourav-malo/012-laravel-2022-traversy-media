<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller {
  public function index() {
    return view('listings.index', [
      'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
    ]);
  }

  public function show(Listing $listing) {
    return view('listings.show', [
      'listing' => $listing
    ]);
  }

  public function create() {
    return view('listings.create');
  }

  public function store(Request $request) {
    $formFields = $request->validate([
      'title' => 'required|max:255',
      'company' => 'required|max:255',
      'location' => 'required|max:255',
      'email' => 'required|max:255|unique:listings',
      'website' => 'required|max:255|url',
      'tags' => 'required|max:255',
      'description' => 'required|max:5000'
    ]);

    if($request->hasFile('logo')) {
      $formFields['logo'] = $request->file('logo')->store('logos', 'public');
    }

    Listing::create($formFields);

    return redirect('/')->with('message', 'Listing created successfully');
  }
}

