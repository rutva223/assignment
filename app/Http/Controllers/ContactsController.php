<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use SimpleXMLElement;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contacts::get();
        return view('dashboard', compact('contacts'));
    }

    public function getContacts(Request $request)
    {
        if ($request->ajax()) {
            $users = Contacts::select(['id', 'name', 'contact_no', 'created_at']);
            return DataTables::of($users)
                ->addColumn('action', function ($row) {
                    $url = route('contacts.edit', $row->id);
                    $deleteUrl = route('contacts.destroy', $row->id);

                    return '<a href="javascript:;" data-size="md" data-title="Edit Contact" class="btn btn-primary"
                            data-url="' . $url . '" data-ajax-popup="true">Edit</a>

                            <button type="button" class="btn btn-xs btn-danger btn-flat delete-button"
                                data-id="' . $row->id . '" data-url="' . $deleteUrl . '" title="Delete">
                                Delete
                            </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_no' => 'required|numeric|digits_between:10,12',
        ]);
        Contacts::create([
            'name' => $request->name,
            'contact_no' => $request->contact_no,
        ]);

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contacts = Contacts::find($id);
        return view('contacts.edit', compact('contacts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_no' => 'required|numeric|digits_between:10,12',
        ]);

        $contact = Contacts::findOrFail($id);
        $contact->update([
            'name' => $request->name,
            'contact_no' => $request->contact_no,
        ]);
        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contacts::findOrFail($id);
        $contact->delete();

        return redirect()->back();
    }

    public function importXML(Request $request)
    {
        $request->validate([
            'xmlFile' => 'required',
        ]);

        $xmlFile = $request->file('xmlFile');
        $xmlContent = file_get_contents($xmlFile);

        try {
            $xml = new SimpleXMLElement($xmlContent);

            foreach ($xml->contact as $contact) {
                Contacts::create([
                    'name' => (string) $contact->name,
                    'contact_no' => (string) $contact->contact_no,
                ]);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Log::info('importXML --- ');
        }
    }

}
