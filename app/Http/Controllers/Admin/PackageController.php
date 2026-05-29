<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('sort_order')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.form', ['package' => new Package()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);

        // ensure slug uniqueness
        $base = $data['slug'];
        $i    = 1;
        while (Package::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        Package::create($data);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.form', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $this->validated($request, $package->id);
        $package->update($data);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function toggleActive(Package $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        $label = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Paket \"{$package->name}\" {$label}.");
    }

    public function destroy(Package $package)
    {
        $hasTransactions = $package->transactions()->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Paket tidak bisa dihapus karena sudah memiliki transaksi. Nonaktifkan saja.');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'               => 'required|string|max:100',
            'description'        => 'nullable|string|max:500',
            'price'              => 'required|integer|min:0',
            'duration_days'      => 'required|integer|min:1',
            'max_guests'         => 'required|integer|min:1',
            'max_gallery'        => 'required|integer|min:0',
            'max_music'          => 'required|integer|min:0',
            'sort_order'         => 'required|integer|min:0',
            'has_watermark'      => 'boolean',
            'has_analytics'      => 'boolean',
            'has_rsvp_export'    => 'boolean',
            'has_custom_domain'  => 'boolean',
            'has_all_templates'  => 'boolean',
            'has_qr_checkin'     => 'boolean',
            'is_active'          => 'boolean',
            'features'           => 'nullable|string',
        ]);

        // Convert newline-delimited string to JSON array
        if (isset($data['features']) && $data['features'] !== '') {
            $data['features'] = array_values(array_filter(
                array_map('trim', explode("\n", $data['features']))
            ));
        } else {
            $data['features'] = null;
        }

        return $data;
    }
}
