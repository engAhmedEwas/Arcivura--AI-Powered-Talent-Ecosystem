<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\Keyword; 
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingCount = Keyword::where('status', 'pending')->count();
        $approvedCount = Keyword::where('is_approved', true)->count();
        $totalCategories = Category::count();

        $blacklistedCount = Blacklist::count();

        $approvedKeywords = Keyword::where('is_approved', true)
            ->with('categories')
            ->latest()
            ->take(10)
            ->paginate(10)
        ;

        return view('admin.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'totalCategories',
            'approvedKeywords',
            'blacklistedCount'
        ));
    }
}