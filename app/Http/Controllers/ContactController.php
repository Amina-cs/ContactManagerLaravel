<?php 
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function add(Request $req) {
        $info = $req->validate([
            'nom' => ['required', 'min:3'],
            'num' => ['required', 'min:10', Rule::unique('contacts', 'num')]
        ]);

        Contact::create($info);
        return redirect('/')->with('success', 'Contact ajouté !');
    }

    public function getAll() {
        return view('welcome', ['contacts' => Contact::all()]);
    }

    public function delete($id) {
        Contact::findOrFail($id)->delete();
        return redirect('/')->with('success', 'Contact supprimé !');
    }

    public function update(Request $request, $id) {
        $contact = Contact::findOrFail($id);
        $info = $request->validate([
            'nom' => ['required', 'min:3'],
            'num' => ['required', Rule::unique('contacts', 'num')->ignore($id)],
        ]);

        $contact->update($info);
        return redirect('/')->with('success', 'Contact mis à jour !');
    }
}