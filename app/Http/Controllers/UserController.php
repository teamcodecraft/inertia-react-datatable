<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'field' => Rule::in(['created_at', 'name', 'email', 'posts_count']),
            'direction' => Rule::in(['asc', 'desc']),
        ]);
        $limit = $request->input('limit', 10);

        $users = \App\Http\Resources\UserResource::collection(
            User::query()
            ->withCount('posts')
            ->when(
                value: $request->search,
                callback: fn ($query, $value) => $query->where('name', 'like', '%' . $value . '%')
                ->orWhere('email', 'like', '%' . $value . '%')
            )
            ->when(
                value: $request->field && $request->direction,
                callback: fn ($query) => $query->orderBy($request->field, $request->direction),
                default: fn ($query) => $query->latest()
            )
            ->paginate($limit)
            ->withQueryString()
        );

        return inertia('Users/Index', [
            'users' => fn () => $users,
            'state' => $request->only('limit', 'page', 'search', 'field', 'direction'),
        ]);
    }
}
