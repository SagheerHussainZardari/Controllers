<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;

class ContactFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {

        $contact_forms = ContactForm::all();
        return view('admin.contact_form.index', compact('contact_forms'));
    }

    public function create()
    {
        return view('admin.ContactForm.create');
    }

    public function store(Request $request)
    {
        $ContactForm = new ContactForm();
        $data = $request->only($ContactForm->getFillable());

        $request->validate([
            'ContactForm_title' => 'required',
            'ContactForm_content' => 'required',
            'ContactForm_order' => 'numeric|min:0|max:32767'
        ]);

        $ContactForm->fill($data)->save();
        return redirect()->route('admin.ContactForm.index')->with('success', 'ContactForm is added successfully!');
    }

    public function edit($id)
    {
        $ContactForm = ContactForm::findOrFail($id);
        return view('admin.ContactForm.edit', compact('ContactForm'));
    }

    public function update(Request $request, $id)
    {
        $ContactForm = ContactForm::findOrFail($id);
        $data = $request->only($ContactForm->getFillable());

        $request->validate([
            'ContactForm_title' => 'required',
            'ContactForm_content' => 'required',
            'ContactForm_order' => 'numeric|min:0|max:32767'
        ]);

        $ContactForm->fill($data)->save();
        return redirect()->route('admin.ContactForm.index')->with('success', 'ContactForm is updated successfully!');
    }

    public function destroy($id)
    {
        $ContactForm = ContactForm::findOrFail($id);
        $ContactForm->delete();
        return Redirect()->back()->with('success', 'ContactForm is deleted successfully!');
    }

    public function view_message($id){
        $ContactForm = ContactForm::findOrFail($id);
        return view('admin.contact_form.message', compact('ContactForm'));


    }
}
