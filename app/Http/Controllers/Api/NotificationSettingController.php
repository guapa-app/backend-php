<?php

namespace App\Http\Controllers\Api;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class NotificationSettingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = NotificationSetting::query();
        if (!$user->isSuperAdmin()) {
            $query->where('admin_id', $user->id);
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        $user = Auth::user();
        $setting = NotificationSetting::findOrFail($id);
        if (!$user->isSuperAdmin() && $setting->admin_id !== $user->id) {
            abort(403);
        }
        return response()->json($setting);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'notification_module' => 'required|string',
            'channels' => 'required|string',
        ]);
        $data['admin_id'] = $user->isSuperAdmin() ? $request->input('admin_id') : $user->id;
        $data['created_by_super_admin'] = $user->isSuperAdmin();
        $setting = NotificationSetting::updateOrCreate(
            [
                'notification_module' => $data['notification_module'],
                'admin_id' => $data['admin_id'],
            ],
            $data
        );
        return response()->json($setting, 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $setting = NotificationSetting::findOrFail($id);
        if (!$user->isSuperAdmin() && $setting->admin_id !== $user->id) {
            abort(403);
        }
        $data = $request->validate([
            'channels' => 'required|string',
        ]);
        $setting->update($data);
        return response()->json($setting);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $setting = NotificationSetting::findOrFail($id);
        if (!$user->isSuperAdmin() && $setting->admin_id !== $user->id) {
            abort(403);
        }
        $setting->delete();
        return response()->json(['success' => true]);
    }
}
