<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollectionRequest;
use App\Services\CollectionService;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function __construct(
        private CollectionService $collectionService,
        private CollectionRepositoryInterface $collectionRepository,
        private CompanyRepositoryInterface $companyRepository,
    ) {}

    public function index()
    {
        $collections = \App\Models\Collection::where('contractor_id', Auth::id())
            ->with('company')
            ->latest()
            ->paginate(15);

        return view('contractor.collections.index', compact('collections'));
    }

    public function show($id)
    {
        $collection = $this->collectionRepository->findById($id);
        
        if (!$collection || $collection->contractor_id !== Auth::id()) {
            abort(403);
        }

        return view('contractor.collections.show', compact('collection'));
    }

    public function generate(StoreCollectionRequest $request)
    {
        $company = $this->companyRepository->findById($request->input('company_id'));
        
        if (!$company || $company->contractor_id !== Auth::id()) {
            abort(403);
        }

        $statement = $this->collectionService->generateStatement(
            $request->input('company_id'),
            $request->input('period_start'),
            $request->input('period_end')
        );

        return view('contractor.collections.generate-preview', compact('statement'));
    }

    public function store(StoreCollectionRequest $request)
    {
        $statement = $this->collectionService->generateStatement(
            $request->input('company_id'),
            $request->input('period_start'),
            $request->input('period_end')
        );

        $collection = $this->collectionService->saveCollection([
            'contractor_id' => Auth::id(),
            ...$statement,
        ]);

        return redirect()->route('collections.show', $collection->id)
            ->with('success', 'تم حفظ الفاتورة بنجاح');
    }

    public function pay($id)
    {
        $collection = $this->collectionRepository->findById($id);
        
        if (!$collection || $collection->contractor_id !== Auth::id()) {
            abort(403);
        }

        $this->collectionService->recordPayment(
            $id,
            request('payment_method'),
            request('payment_date')
        );

        return back()->with('success', 'تم تسجيل الدفع بنجاح');
    }
}
