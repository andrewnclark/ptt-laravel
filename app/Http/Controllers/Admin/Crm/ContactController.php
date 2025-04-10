<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\Crm\Contact;
use App\Services\Crm\ContactService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * @var ContactService
     */
    protected ContactService $contactService;

    /**
     * Create a new controller instance.
     *
     * @param ContactService $contactService
     * @return void
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the contacts.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.crm.contacts.index');
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.crm.contacts.create');
    }

    /**
     * Display the specified contact.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $contact = $this->contactService->getContactById($id);
        
        // If contact not found, abort with 404
        if (!$contact) {
            abort(404);
        }
        
        // Get related opportunities
        $opportunities = $contact->opportunities()->with('company')->get();
        
        return view('admin.crm.contacts.show', [
            'contact' => $contact,
            'opportunities' => $opportunities,
        ]);
    }

    /**
     * Show the form for editing the specified contact.
     *
     * @param  \App\Models\Crm\Contact  $contact
     * @return \Illuminate\View\View
     */
    public function edit(Contact $contact)
    {
        return view('admin.crm.contacts.edit', [
            'contact' => $contact
        ]);
    }
} 