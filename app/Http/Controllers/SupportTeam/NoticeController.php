<?php
namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.support_team.notices.index', ['notices' => Notice::latest()->get()]);
    }

    public function create()
    {
        return view('pages.support_team.notices.form', ['notice' => new Notice()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['title' => 'required|string|max:180', 'body' => 'required|string', 'audience' => 'required|in:all,parents,students,teachers,staff', 'published_at' => 'nullable|date', 'expires_at' => 'nullable|date|after_or_equal:published_at', 'is_published' => 'nullable|boolean']);
        Notice::create($data + ['is_published' => $request->boolean('is_published')]);
        return redirect()->route('notices.index')->with('flash_success', 'Notice published successfully.');
    }

    public function edit(Notice $notice)
    {
        return view('pages.support_team.notices.form', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $data = $request->validate(['title' => 'required|string|max:180', 'body' => 'required|string', 'audience' => 'required|in:all,parents,students,teachers,staff', 'published_at' => 'nullable|date', 'expires_at' => 'nullable|date|after_or_equal:published_at', 'is_published' => 'nullable|boolean']);
        $notice->update($data + ['is_published' => $request->boolean('is_published')]);
        return redirect()->route('notices.index')->with('flash_success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notices.index')->with('flash_success', 'Notice deleted successfully.');
    }
}
