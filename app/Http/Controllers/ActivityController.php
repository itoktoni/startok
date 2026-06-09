<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::active()->orderBy('sort_order');

        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        $activities = $query->get();

        if ($request->has('grouped')) {
            return response()->json($activities->groupBy('type'));
        }

        return response()->json($activities);
    }

    public function show($slug)
    {
        $activity = Activity::where('slug', $slug)->active()->firstOrFail();

        return response()->json($activity);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'moral' => 'nullable|string|max:500',
            'ages' => 'nullable|array',
            'skills' => 'nullable|array',
            'data' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        $data['active'] = $data['active'] ?? true;

        $activity = Activity::create($data);

        return response()->json($activity, 201);
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'moral' => 'nullable|string|max:500',
            'ages' => 'nullable|array',
            'skills' => 'nullable|array',
            'data' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $activity->update($data);

        return response()->json($activity);
    }

    public function destroy($id)
    {
        Activity::findOrFail($id)->delete();

        return response()->json(null, 204);
    }

    public function types()
    {
        $types = [
            'storytelling' => ['emoji' => '📖', 'title' => 'Story Telling', 'desc' => 'Anak belajar mendengar, bercerita dan menyampaikan ide secara verbal.', 'color' => '#4CAF50', 'bg' => '#E8F5E9', 'feature' => 'story'],
            'bermain_peran' => ['emoji' => '🎭', 'title' => 'Bermain Peran', 'desc' => 'Anak belajar memahami perspektif orang lain melalui peran.', 'color' => '#FF9800', 'bg' => '#FFF3E0', 'feature' => 'roleplay'],
            'permainan' => ['emoji' => '🎲', 'title' => 'Permainan', 'desc' => 'Anak belajar aturan, kerja sama, dan sportivitas.', 'color' => '#E91E63', 'bg' => '#FCE4EC', 'feature' => 'game'],
            'monolog' => ['emoji' => '🎤', 'title' => 'Monolog', 'desc' => 'Anak belajar berani tampil dan berbicara di depan umum.', 'color' => '#9C27B0', 'bg' => '#F3E5F5', 'feature' => 'monolog'],
            'proyek_kreatif' => ['emoji' => '🎨', 'title' => 'Proyek Kreatif & Seni', 'desc' => 'Anak belajar mengekspresikan diri melalui seni.', 'color' => '#2196F3', 'bg' => '#E3F2FD', 'feature' => 'project'],
            'musik_gerak' => ['emoji' => '🎵', 'title' => 'Musik & Gerak', 'desc' => 'Anak belajar ritme, koordinasi, dan ekspresi tubuh.', 'color' => '#FF5722', 'bg' => '#FBE9E7', 'feature' => 'music'],
            'puzzle' => ['emoji' => '🧩', 'title' => 'Puzzle & Problem Solving', 'desc' => 'Anak belajar berpikir logis dan memecahkan masalah.', 'color' => '#673AB7', 'bg' => '#EDE7F6', 'feature' => 'puzzle'],
            'mindfulness' => ['emoji' => '🧘', 'title' => 'Mindfulness & Refleksi', 'desc' => 'Anak belajar mengenali perasaan dan menenangkan diri.', 'color' => '#795548', 'bg' => '#EFEBE9', 'feature' => 'mindfulness'],
            'outdoor' => ['emoji' => '🌿', 'title' => 'Outdoor Exploration', 'desc' => 'Anak belajar mengenal alam dan lingkungan sekitar.', 'color' => '#009688', 'bg' => '#E0F2F1', 'feature' => 'outdoor'],
            'ilmu_pengetahuan' => ['emoji' => '🔬', 'title' => 'Ilmu Pengetahuan & Literasi', 'desc' => 'Anak belajar sains, eksperimen, dan meningkatkan kemampuan literasi.', 'color' => '#0D47A1', 'bg' => '#E3F2FD', 'feature' => 'ilmu_pengetahuan'],
        ];

        return response()->json($types);
    }
}
