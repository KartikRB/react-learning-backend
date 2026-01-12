<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public FaqService $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    public function store(StoreFaqRequest $request)
    {
        $validated = $request->validated();
        $faq = $this->faqService->create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Faq created successfully!',
            'data' => $faq
        ]);
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $validated = $request->validated();
        $faq = $this->faqService->update($faq, $validated);

        return response()->json([
            'status' => true,
            'message' => 'Faq updated successfully!',
            'data' => $faq
        ]);
    }

    public function destroy(Faq $faq)
    {
        $this->faqService->delete($faq);

        return response()->json([
            'status' => true,
            'message' => 'Faq deleted successfully!',
        ]);
    }

    public function show($id)
    {
        $faq = $this->faqService->getFaq($id);
        return response()->json([
            'status' => true,
            'message' => 'Faq data found successfully!',
            'data' => $faq
        ]);
    }

    public function getFaqs()
    {
        $data = $this->faqService->orderBy('order', 'ASC')->get();

        return response()->json([
            'status' => true,
            'message' => 'Faq data found!',
            'data' => $data
        ]);
    }

    public function updateFaqStatus(Request $request, $id)
    {
        $faq = $this->faqService->getFaq($id);
        $is_active = $request->is_active;
        $this->faqService->update($faq, ['is_active' => $is_active]);

        return response()->json([
            'status' => true,
            'message' => 'Faq status updated successfully!',
            'is_active' => $is_active
        ]);
    }
}
