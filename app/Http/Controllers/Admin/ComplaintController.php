<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ComplaintStatusRequest;
use App\Models\Complaint;
use App\Services\ComplaintService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function __construct(private readonly ComplaintService $complaintService) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $complaints = Complaint::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('client_name', 'like', '%'.$search.'%')
                        ->orWhere('client_phone', 'like', '%'.$search.'%')
                        ->orWhere('subject', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($status, Complaint::STATUSES, true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->get();

        $stats = [
            'total' => Complaint::query()->count(),
            'pending' => Complaint::query()->where('status', 'pending')->count(),
            'resolved' => Complaint::query()->where('status', 'resolved')->count(),
            'rejected' => Complaint::query()->where('status', 'rejected')->count(),
        ];

        return view('dashboard.complaints.index', [
            'complaints' => $complaints,
            'search' => $search,
            'status' => $status,
            'stats' => $stats,
            'activeMenu' => 'complaints',
        ]);
    }

    public function show(Complaint $complaint): View
    {
        $complaint->load(['client', 'order']);

        return view('dashboard.complaints.show', [
            'complaint' => $complaint,
            'activeMenu' => 'complaints',
        ]);
    }

    public function updateStatus(ComplaintStatusRequest $request, Complaint $complaint): RedirectResponse
    {
        $this->complaintService->updateStatus(
            $complaint,
            $request->validated('status'),
            $request->validated('admin_response'),
        );

        return redirect()
            ->route('admin.complaints.show', $complaint)
            ->with('success', 'تم تحديث حالة الشكوى بنجاح.');
    }
}
