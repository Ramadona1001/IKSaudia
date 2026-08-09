<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductSpecDownloadRequest;
use App\Services\ProductCatalogService;
use App\Services\ProductSpecDownloadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductSpecDownloadController extends Controller
{
    public function __construct(
        protected ProductCatalogService $products,
        protected ProductSpecDownloadService $downloads,
    ) {}

    public function store(StoreProductSpecDownloadRequest $request, string $slug): JsonResponse
    {
        $locale = app()->getLocale();
        $product = $this->products->findPublishedBySlug($slug, $locale);

        if ($product === null || ! $product->hasSpecificationPdf()) {
            return response()->json(['message' => __('front.products.spec_request_not_found')], 404);
        }

        if ($request->isHoneypotTriggered()) {
            return response()->json([
                'message' => __('front.products.spec_request_success'),
                'reference' => 'PDF-'.strtoupper(\Illuminate\Support\Str::random(8)),
            ]);
        }

        $submission = $this->downloads->create([
            'product_id' => $product->id,
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'company' => $request->validated('company'),
            'locale' => $locale,
            'ip_address' => $request->ip(),
            'user_agent' => \Illuminate\Support\Str::limit((string) $request->userAgent(), 500),
        ]);

        return response()->json([
            'message' => __('front.products.spec_request_success'),
            'reference' => $submission->reference_number,
        ]);
    }

    public function download(Request $request, string $token): BinaryFileResponse
    {
        $downloadRequest = $this->downloads->findByToken($token);

        abort_if($downloadRequest === null || ! $downloadRequest->tokenIsValid(), 404);

        $product = $downloadRequest->product;

        abort_if($product === null || ! $product->hasSpecificationPdf(), 404);

        $path = Storage::disk('public')->path($product->pdf_path);

        abort_unless(is_file($path), 404);

        return response()->download(
            $path,
            $product->specificationPdfDownloadName($downloadRequest->locale),
            ['Content-Type' => 'application/pdf'],
        );
    }
}
