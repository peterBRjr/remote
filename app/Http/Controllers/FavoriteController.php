<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $favorites = auth()->user()->favorites()->latest()->paginate(9);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Location $location): RedirectResponse
    {
        $user = auth()->user();

        if ($user->favorites()->where('location_id', $location->id)->exists()) {
            $user->favorites()->detach($location->id);
            $message = 'Local removido dos seus favoritos.';
        } else {
            $user->favorites()->attach($location->id);
            $message = 'Local adicionado aos seus favoritos com sucesso!';
        }

        return redirect()->back()->with('info', $message);
    }
}
