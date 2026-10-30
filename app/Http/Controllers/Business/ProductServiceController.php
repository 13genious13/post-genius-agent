<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessProductService;
use Illuminate\Http\Request;

class ProductServiceController extends Controller
{
    public function index(Business $business)
    {
        $this->authorize('view', $business);

        $items = $business
            ->productsServices()
            ->get();

        return view(
            'businesses.products.index',
            compact('business', 'items')
        );
    }

    public function store(
        Request $request,
        Business $business
    ) {
        $this->authorize('update', $business);

        $data = $request->validate([
            'type' => ['required', 'in:product,service'],
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['nullable', 'string', 'max:150'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'url' => ['nullable', 'url', 'max:255'],
            'unique_selling_point' => ['nullable', 'string', 'max:2000'],
        ]);

        $business->productsServices()
            ->create($data);

        $business->refreshProfileCompletion();

        return back()->with(
            'success',
            'Product/service added.'
        );
    }

    public function update(
        Request $request,
        Business $business,
        BusinessProductService $item
    ) {
        $this->authorize('update', $business);

        abort_unless(
            $item->business_id === $business->id,
            404
        );

        $data = $request->validate([
            'type' => ['required', 'in:product,service'],
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['nullable', 'string', 'max:150'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'url' => ['nullable', 'url', 'max:255'],
            'unique_selling_point' => ['nullable', 'string', 'max:2000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_featured'] =
            $request->boolean('is_featured');

        $data['is_active'] =
            $request->boolean('is_active');

        $item->update($data);

 return back()->with(
     'success',
     'Product/service updated.'
 );
 }

    public function destroy(
        Business $business,
        BusinessProductService $item
    ) {
        $this->authorize('update', $business);

        abort_unless(
            $item->business_id === $business->id,
            404
        );

        $item->delete();

        $business->refreshProfileCompletion();

        return back()->with(
            'success',
            'Product/service removed.'
        );
    }
}
