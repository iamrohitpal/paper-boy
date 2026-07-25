<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtraNewspaperRequest;
use App\Models\Customer;
use App\Models\Newspaper;
use App\Services\CustomerService;
use App\Services\ExtraNewspaperService;
use App\Services\NewspaperService;
use Illuminate\Http\Request;

class ExtraNewspaperController extends Controller
{
    protected ExtraNewspaperService $extraNewspaperService;

    protected CustomerService $customerService;

    protected NewspaperService $newspaperService;

    public function __construct(
        ExtraNewspaperService $extraNewspaperService,
        CustomerService $customerService,
        NewspaperService $newspaperService
    ) {
        $this->extraNewspaperService = $extraNewspaperService;
        $this->customerService = $customerService;
        $this->newspaperService = $newspaperService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $extraNewspapers = $this->extraNewspaperService->getPaginatedExtraNewspapers($search);

        return view('extra-newspapers.index', compact('extraNewspapers', 'search'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        $newspapers = Newspaper::where('status', 'Active')->orderBy('name')->get();

        return view('extra-newspapers.create', compact('customers', 'newspapers'));
    }

    public function store(ExtraNewspaperRequest $request)
    {
        $data = $request->validated();
        $data['is_billed'] = $request->has('is_billed') ? true : false;

        $this->extraNewspaperService->createExtraNewspaper($data);

        if (str_contains(url()->previous(), '/customers/')) {
            return redirect()->back()->with('success', 'Extra Newspaper logged successfully.');
        }

        return redirect()->route('extra-newspapers.index')
            ->with('success', 'Extra Newspaper added successfully.');
    }

    public function edit($id)
    {
        $extraNewspaper = $this->extraNewspaperService->getExtraNewspaper($id);
        $customers = Customer::orderBy('name')->get();
        $newspapers = Newspaper::orderBy('name')->get();

        return view('extra-newspapers.edit', compact('extraNewspaper', 'customers', 'newspapers'));
    }

    public function update(ExtraNewspaperRequest $request, $id)
    {
        $data = $request->validated();
        $data['is_billed'] = $request->has('is_billed') ? true : false;

        $this->extraNewspaperService->updateExtraNewspaper($id, $data);

        if (str_contains(url()->previous(), '/customers/')) {
            return redirect()->back()->with('success', 'Extra Newspaper updated successfully.');
        }

        return redirect()->route('extra-newspapers.index')
            ->with('success', 'Extra Newspaper updated successfully.');
    }

    public function destroy($id)
    {
        $this->extraNewspaperService->deleteExtraNewspaper($id);

        if (str_contains(url()->previous(), '/customers/')) {
            return redirect()->back()->with('success', 'Extra Newspaper deleted successfully.');
        }

        return redirect()->route('extra-newspapers.index')
            ->with('success', 'Extra Newspaper deleted successfully.');
    }
}
