<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Review::create([
            'user_id' => auth()->id(),
            'location_id' => $validated['location_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'wifi_rating' => $validated['wifi_rating'],
            'comfort_rating' => $validated['comfort_rating'],
        ]);

        return redirect()->back()->with('success', 'Sua avaliação foi enviada com sucesso! Obrigado por colaborar com a comunidade.');
    }
}
