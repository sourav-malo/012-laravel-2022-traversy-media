<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
  public function index()
  {
    return view('listings.index', [
      'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
    ]);
  }

  public function show(Listing $listing)
  {
    return view('listings.show', [
      'listing' => $listing
    ]);
  }

  public function create()
  {
    return view('listings.create');
  }

  public function store(Request $request)
  {
    $formFields = $request->validate([
      'title' => 'required|max:255',
      'company' => 'required|max:255',
      'location' => 'required|max:255',
      'email' => 'required|max:255',
      'website' => 'required|max:255|url',
      'tags' => 'required|max:255',
      'description' => 'required|max:5000'
    ]);

    if ($request->hasFile('logo')) {
      $formFields['logo'] = $request->file('logo')->store('logos', 'public');
    }

    $formFields['user_id'] = auth()->id();

    Listing::create($formFields);

    return redirect('/')->with('message', 'Listing created successfully');
  }

  public function edit(Listing $listing)
  {
    return view('listings.edit', [
      'listing' => $listing
    ]);
  }

  public function update(Request $request, Listing $listing)
  {
    if(auth()->user()->id != $listing->user_id) {
      abort(403, 'Unauthorized Action');
    }

    $formFields = $request->validate([
      'title' => 'required|max:255',
      'company' => 'required|max:255',
      'location' => 'required|max:255',
      'email' => 'required|max:255',
      'website' => 'required|max:255|url',
      'tags' => 'required|max:255',
      'description' => 'required|max:5000'
    ]);

    if ($request->hasFile('logo')) {
      $formFields['logo'] = $request->file('logo')->store('logos', 'public');
    }

    $listing->update($formFields);

    return back()->with('message', 'Listing updated successfully');
  }

  public function destroy(Listing $listing)
  {
    if(auth()->user()->id != $listing->user_id) {
      abort(403, 'Unauthorized Action');
    }

    $listing->delete();

    return back()->with('message', 'Listing deleted successfully');
  }

  public function manage()
  {
    return view('listings.manage', [
      'listings' => auth()->user()->listings()->paginate(10)
    ]);
  }
}
