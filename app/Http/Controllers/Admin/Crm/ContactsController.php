<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\Crm\Contact;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Display a listing of contacts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.crm.contacts.index');
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.crm.contacts.create');
    }

    /**
     * Show the form for editing the specified contact.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return \Illuminate\View\View
     */
    public function edit(Contact $contact)
    {
        return view('admin.crm.contacts.edit', compact('contact'));
    }

    /**
     * Show the deletion confirmation page for the specified contact.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return \Illuminate\View\View
     */
    public function delete(Contact $contact)
    {
        return view('admin.crm.contacts.delete', compact('contact'));
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact)
    {
        // Simple delete will trigger the observer's deleted method
        $contact->delete();

        return redirect()->route('admin.crm.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
} 