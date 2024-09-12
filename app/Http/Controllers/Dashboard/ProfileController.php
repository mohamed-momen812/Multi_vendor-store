<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    public function edit() {
        $locales = Languages::getNames('en'); // Gets the list of language names indexed with alpha2 codes as keys. from symfony pakage
        $countries = Countries::getNames('en'); // Gets the list of country names indexed with alpha2 codes as keys.
        $user = Auth::user();
        return view("dashboard.profile.edit", compact("user", "locales","countries"));
    }

    public function update(Request $request) {


        $request->validate([
            "first_name" =>  ["required","string","max:255"],
            "last_name" => ["required","string","max:255"],
            "birthday" => ["required","date","before:today"],
            "country"=> ["required"],
        ]);

        $user = Auth::user();
        // fill will put data into model not in db, so use here save to update if existing and create if not existing
        $user->profile->fill($request->all())->save(); // profile from relation return profile object and can access to it

        // $profile = $user->profile;
        // if($profile->first_name) {
        //     $profile->update($request->all());
        // } else {
        //     // $request->merge([
        //     //     'user_id' => $user->id,
        //     // ]);
        //     // Profile::create($request->all());
        //     $user->profile()->create($request->all()); // access to ptofile object and uesr_id from user form realtion
        // }

        return redirect()->route("dashboard.profile.edit")->with("success","Profile updated");

    }
}
