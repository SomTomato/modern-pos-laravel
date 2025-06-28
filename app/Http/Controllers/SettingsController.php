<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the store settings page.
     */
    public function storeSettings()
    {
        $this->authorize('admin-only');
        $breadcrumbs = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Store Settings']];
        $settings = Setting::all()->pluck('value', 'key');

        return view('settings.store', compact('breadcrumbs', 'settings'));
    }

    /**
     * THE FIX: Update only the General Information settings.
     */
    public function updateGeneral(Request $request)
    {
        $this->authorize('admin-only');
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Setting::updateOrCreate(['key' => 'store_name'], ['value' => $request->store_name]);
        Setting::updateOrCreate(['key' => 'store_address'], ['value' => $request->store_address]);
        Setting::updateOrCreate(['key' => 'store_phone'], ['value' => $request->store_phone]);

        if ($request->hasFile('store_logo')) {
            $imageName = 'logo.' . $request->file('store_logo')->getClientOriginalExtension();
            $request->file('store_logo')->storeAs('public', $imageName);
            Setting::updateOrCreate(['key' => 'store_logo'], ['value' => $imageName]);
        }

        return back()->with('success', 'General settings updated successfully!');
    }

    /**
     * THE FIX: Update only the Financial settings.
     */
    public function updateFinancial(Request $request)
    {
        $this->authorize('admin-only');
        $request->validate([
            'currency_symbol' => 'required|string|max:5',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        Setting::updateOrCreate(['key' => 'currency_symbol'], ['value' => $request->currency_symbol]);
        Setting::updateOrCreate(['key' => 'tax_rate'], ['value' => $request->tax_rate]);

        return back()->with('success', 'Financial settings updated successfully!');
    }

    /**
     * THE FIX: Update only the Receipt settings.
     */
    public function updateReceipt(Request $request)
    {
        $this->authorize('admin-only');
        $request->validate(['receipt_footer' => 'nullable|string']);

        Setting::updateOrCreate(['key' => 'receipt_footer'], ['value' => $request->receipt_footer]);
        
        return back()->with('success', 'Receipt settings updated successfully!');
    }
}
