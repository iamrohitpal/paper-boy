<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewspaperRequest;
use App\Services\NewspaperService;
use Illuminate\Http\Request;

class NewspaperController extends Controller
{
    protected NewspaperService $newspaperService;

    public function __construct(NewspaperService $newspaperService)
    {
        $this->newspaperService = $newspaperService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $newspapers = $this->newspaperService->getPaginatedNewspapers($search);

        return view('newspapers.index', compact('newspapers', 'search'));
    }

    public function create()
    {
        return view('newspapers.create');
    }

    public function store(NewspaperRequest $request)
    {
        $this->newspaperService->createNewspaper($request->validated());

        return redirect()->route('newspapers.index')
            ->with('success', 'Newspaper created successfully.');
    }

    public function edit($id)
    {
        $newspaper = $this->newspaperService->getNewspaper($id);

        return view('newspapers.edit', compact('newspaper'));
    }

    public function update(NewspaperRequest $request, $id)
    {
        $this->newspaperService->updateNewspaper($id, $request->validated());

        return redirect()->route('newspapers.index')
            ->with('success', 'Newspaper updated successfully.');
    }

    public function destroy($id)
    {
        $this->newspaperService->deleteNewspaper($id);

        return redirect()->route('newspapers.index')
            ->with('success', 'Newspaper deleted successfully.');
    }
}
