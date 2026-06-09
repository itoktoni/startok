<?php

namespace App\Http\Controllers;

use App\Models\LangkahKecilAnak;
use App\Models\LangkahKecilChallenge;
use App\Models\LangkahKecilChallengeHistory;
use App\Models\LangkahKecilChecklist;
use App\Models\LangkahKecilSchedule;
use App\Models\LangkahKecilWorksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LangkahKecilController extends Controller
{
    private function getUserAnakIds(Request $request)
    {
        $user = $request->user();
        if (!$user) return [];
        return LangkahKecilAnak::where('user_id', $user->id)->pluck('id')->toArray();
    }

    private function scopeToUser(Request $request, $query)
    {
        $ids = $this->getUserAnakIds($request);
        return $query->whereIn('anak_id', $ids);
    }

    // ==================== ANAK ====================

    public function getAnakList(Request $request)
    {
        $userId = $request->user()->id ?? null;
        $anak = LangkahKecilAnak::when($userId, fn($q) => $q->where('user_id', $userId))
            ->with(['challenges', 'challengeHistory', 'checklists', 'schedules', 'worksheets'])
            ->get();
        return response()->json($anak);
    }

    public function addAnak(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'gender' => 'nullable|string|max:20',
            'umur' => 'nullable|integer|min:1|max:18',
            'tanggal_lahir' => 'nullable|integer|min:1|max:31',
            'bulan_lahir' => 'nullable|integer|min:1|max:12',
            'tahun_lahir' => 'nullable|integer|min:2000|max:2030',
            'emoji' => 'nullable|string|max:10',
            'avatar' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'history' => 'nullable|array',
            'completed_skills' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $data['user_id'] = $request->user()->id ?? null;
        if (!isset($data['umur']) && isset($data['tahun_lahir'])) {
            $data['umur'] = (int) date('Y') - (int) $data['tahun_lahir'];
        }
        $anak = LangkahKecilAnak::create($data);
        return response()->json($anak, 201);
    }

    public function updateAnak(Request $request, $id)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$id, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'gender' => 'nullable|string|max:20',
            'umur' => 'nullable|integer|min:1|max:18',
            'tanggal_lahir' => 'nullable|integer|min:1|max:31',
            'bulan_lahir' => 'nullable|integer|min:1|max:12',
            'tahun_lahir' => 'nullable|integer|min:2000|max:2030',
            'emoji' => 'nullable|string|max:10',
            'avatar' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'history' => 'nullable|array',
            'completed_skills' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        if (!isset($data['umur']) && isset($data['tahun_lahir'])) {
            $data['umur'] = (int) date('Y') - (int) $data['tahun_lahir'];
        }

        $anak = LangkahKecilAnak::findOrFail($id);
        $anak->update($data);
        return response()->json($anak);
    }

    public function deleteAnak(Request $request, $id)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$id, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilAnak::where('id', $id)->delete();
        return response()->json(null, 204);
    }

    // ==================== SKILLS ====================

    public function addSkill(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'key' => 'required|string',
            'emoji' => 'nullable|string|max:10',
            'title' => 'required|string|max:255',
            'pilar' => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'color' => 'nullable|string|max:20',
            'activities' => 'nullable|array',
        ]);

        $anak = LangkahKecilAnak::findOrFail($anakId);
        $skills = $anak->skills ?? [];
        $skills[] = $data;
        $anak->skills = $skills;
        $anak->save();

        return response()->json($anak, 201);
    }

    public function updateSkill(Request $request, $anakId, $skillId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'progress' => 'nullable|integer|min:0|max:100',
            'activities' => 'nullable|array',
        ]);

        $anak = LangkahKecilAnak::findOrFail($anakId);
        $skills = $anak->skills ?? [];
        $index = array_search($skillId, array_column($skills, 'key'));
        if ($index !== false) {
            $skills[$index] = array_merge($skills[$index], $data);
            $anak->skills = $skills;
            $anak->save();
        }

        return response()->json($anak);
    }

    public function deleteSkill(Request $request, $anakId, $skillId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $anak = LangkahKecilAnak::findOrFail($anakId);
        $skills = $anak->skills ?? [];
        $skills = array_values(array_filter($skills, fn($s) => $s['key'] !== $skillId));
        $anak->skills = $skills;
        $anak->save();

        return response()->json(null, 204);
    }

    // ==================== ACTIVITIES ====================

    public function addActivity(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'skill_key' => 'required|string',
            'title' => 'required|string|max:255',
            'emoji' => 'nullable|string|max:10',
            'feature' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $anak = LangkahKecilAnak::findOrFail($anakId);
        $skills = $anak->skills ?? [];
        $skillIndex = array_search($data['skill_key'], array_column($skills, 'key'));

        $activity = [
            'title' => $data['title'],
            'emoji' => $data['emoji'] ?? '📌',
            'feature' => $data['feature'] ?? null,
            'date' => $data['date'] ?? now()->format('d M Y'),
        ];

        if ($skillIndex !== false) {
            if (!isset($skills[$skillIndex]['activities'])) $skills[$skillIndex]['activities'] = [];
            $skills[$skillIndex]['activities'][] = $activity;
            $anak->skills = $skills;
        } else {
            $history = $anak->history ?? [];
            $history[] = $activity;
            $anak->history = $history;
        }

        $anak->save();
        return response()->json($anak, 201);
    }

    public function deleteActivity(Request $request, $anakId, $activityId)
    {
        // activityId here is a simple index or title-based removal
        $request->validate(['activity_title' => 'required|string']);
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $anak = LangkahKecilAnak::findOrFail($anakId);
        $title = $request->activity_title;
        $skills = $anak->skills ?? [];
        foreach ($skills as &$skill) {
            if (isset($skill['activities'])) {
                $skill['activities'] = array_values(array_filter($skill['activities'], fn($a) => $a['title'] !== $title));
            }
        }
        $anak->skills = $skills;
        $anak->save();

        return response()->json(null, 204);
    }

    // ==================== CHALLENGES ====================

    public function addChallenge(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'emoji' => 'nullable|string|max:10',
            'points' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,completed,cancelled',
            'date' => 'nullable|date',
            'meta' => 'nullable|array',
        ]);

        $challenge = LangkahKecilChallenge::create([
            'anak_id' => $anakId,
            ...$data,
            'status' => $data['status'] ?? 'pending',
        ]);

        return response()->json($challenge, 201);
    }

    public function updateChallenge(Request $request, $anakId, $challengeId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'emoji' => 'nullable|string|max:10',
            'points' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,completed,cancelled',
            'date' => 'nullable|date',
            'meta' => 'nullable|array',
        ]);

        $challenge = LangkahKecilChallenge::where('id', $challengeId)->where('anak_id', $anakId)->firstOrFail();
        $challenge->update($data);

        return response()->json($challenge);
    }

    public function deleteChallenge(Request $request, $anakId, $challengeId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilChallenge::where('id', $challengeId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }

    // ==================== CHALLENGE HISTORY ====================

    public function addChallengeHistory(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'date' => 'nullable|date',
            'meta' => 'nullable|array',
        ]);

        $history = LangkahKecilChallengeHistory::create([
            'anak_id' => $anakId,
            ...$data,
            'date' => $data['date'] ?? now()->toDateString(),
        ]);

        return response()->json($history, 201);
    }

    // ==================== CHECKLISTS ====================

    public function addChecklist(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'items' => 'nullable|array',
            'date' => 'nullable|date',
        ]);

        $checklist = LangkahKecilChecklist::create([
            'anak_id' => $anakId,
            ...$data,
            'items' => $data['items'] ?? [],
        ]);

        return response()->json($checklist, 201);
    }

    public function updateChecklist(Request $request, $anakId, $checklistId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'items' => 'nullable|array',
            'date' => 'nullable|date',
        ]);

        $checklist = LangkahKecilChecklist::where('id', $checklistId)->where('anak_id', $anakId)->firstOrFail();
        $checklist->update($data);

        return response()->json($checklist);
    }

    public function deleteChecklist(Request $request, $anakId, $checklistId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilChecklist::where('id', $checklistId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }

    // ==================== SCHEDULES ====================

    public function addSchedule(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'label' => 'required|string|max:255',
            'time' => 'nullable|string|max:20',
            'done' => 'boolean',
            'date' => 'nullable|date',
        ]);

        $schedule = LangkahKecilSchedule::create([
            'anak_id' => $anakId,
            ...$data,
        ]);

        return response()->json($schedule, 201);
    }

    public function updateSchedule(Request $request, $anakId, $scheduleId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'label' => 'sometimes|string|max:255',
            'time' => 'nullable|string|max:20',
            'done' => 'sometimes|boolean',
            'date' => 'nullable|date',
        ]);

        $schedule = LangkahKecilSchedule::where('id', $scheduleId)->where('anak_id', $anakId)->firstOrFail();
        $schedule->update($data);

        return response()->json($schedule);
    }

    public function deleteSchedule(Request $request, $anakId, $scheduleId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilSchedule::where('id', $scheduleId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }

    // ==================== WORKSHEETS ====================

    public function addWorksheet(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'type' => 'required|string|max:100',
            'data' => 'nullable|array',
            'date' => 'nullable|date',
        ]);

        $worksheet = LangkahKecilWorksheet::create([
            'anak_id' => $anakId,
            ...$data,
        ]);

        return response()->json($worksheet, 201);
    }

    public function deleteWorksheet(Request $request, $anakId, $worksheetId)
    {
        $ids = $this->getUserAnakIds($request);
        if (!in_array((int)$anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilWorksheet::where('id', $worksheetId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }

    // ==================== SYNC ====================

    public function sync(Request $request)
    {
        $request->validate([
            'anak_list' => 'required|array',
        ]);

        $userId = $request->user()->id ?? null;
        $results = [];

        DB::beginTransaction();
        try {
            foreach ($request->anak_list as $anakData) {
                $localId = $anakData['id'] ?? null;
                $nama = $anakData['nama'] ?? 'Anak';

                $anak = LangkahKecilAnak::when($userId, fn($q) => $q->where('user_id', $userId))
                    ->where('nama', $nama)
                    ->first();

                if (!$anak) {
                    $anak = LangkahKecilAnak::create([
                        'user_id' => $userId,
                        'nama' => $nama,
                        'gender' => $anakData['gender'] ?? null,
                        'umur' => $anakData['umur'] ?? null,
                        'tanggal_lahir' => $anakData['tanggal_lahir'] ?? null,
                        'bulan_lahir' => $anakData['bulan_lahir'] ?? null,
                        'tahun_lahir' => $anakData['tahun_lahir'] ?? null,
                        'emoji' => $anakData['emoji'] ?? '👶',
                        'skills' => $anakData['skills'] ?? [],
                        'history' => $anakData['history'] ?? [],
                        'completed_skills' => $anakData['completed_skills'] ?? [],
                        'settings' => $anakData['settings'] ?? [],
                    ]);
                } else {
                    $anak->update([
                        'gender' => $anakData['gender'] ?? $anak->gender,
                        'umur' => $anakData['umur'] ?? $anak->umur,
                        'tanggal_lahir' => $anakData['tanggal_lahir'] ?? $anak->tanggal_lahir,
                        'bulan_lahir' => $anakData['bulan_lahir'] ?? $anak->bulan_lahir,
                        'tahun_lahir' => $anakData['tahun_lahir'] ?? $anak->tahun_lahir,
                        'skills' => $anakData['skills'] ?? $anak->skills,
                        'history' => $anakData['history'] ?? $anak->history,
                        'completed_skills' => $anakData['completed_skills'] ?? $anak->completed_skills,
                        'settings' => $anakData['settings'] ?? $anak->settings,
                    ]);
                }

                // Sync challenges
                if (isset($anakData['challenges']) && is_array($anakData['challenges'])) {
                    LangkahKecilChallenge::where('anak_id', $anak->id)->delete();
                    foreach ($anakData['challenges'] as $c) {
                        LangkahKecilChallenge::create([
                            'anak_id' => $anak->id,
                            'category' => $c['category'] ?? '',
                            'title' => $c['title'] ?? '',
                            'emoji' => $c['emoji'] ?? null,
                            'points' => $c['points'] ?? 0,
                            'status' => $c['status'] ?? 'pending',
                            'date' => $c['date'] ?? null,
                            'meta' => $c['meta'] ?? null,
                        ]);
                    }
                }

                // Sync challenge history
                if (isset($anakData['challengeHistory']) && is_array($anakData['challengeHistory'])) {
                    LangkahKecilChallengeHistory::where('anak_id', $anak->id)->delete();
                    foreach ($anakData['challengeHistory'] as $h) {
                        LangkahKecilChallengeHistory::create([
                            'anak_id' => $anak->id,
                            'category' => $h['category'] ?? '',
                            'title' => $h['title'] ?? '',
                            'date' => $h['date'] ?? null,
                            'meta' => $h['meta'] ?? null,
                        ]);
                    }
                }

                // Sync checklists
                if (isset($anakData['checklists']) && is_array($anakData['checklists'])) {
                    LangkahKecilChecklist::where('anak_id', $anak->id)->delete();
                    foreach ($anakData['checklists'] as $cl) {
                        LangkahKecilChecklist::create([
                            'anak_id' => $anak->id,
                            'title' => $cl['title'] ?? '',
                            'items' => $cl['items'] ?? [],
                            'date' => $cl['date'] ?? null,
                        ]);
                    }
                }

                // Sync schedules
                if (isset($anakData['schedules']) && is_array($anakData['schedules'])) {
                    LangkahKecilSchedule::where('anak_id', $anak->id)->delete();
                    foreach ($anakData['schedules'] as $s) {
                        LangkahKecilSchedule::create([
                            'anak_id' => $anak->id,
                            'label' => $s['label'] ?? '',
                            'time' => $s['time'] ?? null,
                            'done' => $s['done'] ?? false,
                            'date' => $s['date'] ?? null,
                        ]);
                    }
                }

                // Sync worksheets
                if (isset($anakData['worksheets']) && is_array($anakData['worksheets'])) {
                    LangkahKecilWorksheet::where('anak_id', $anak->id)->delete();
                    foreach ($anakData['worksheets'] as $w) {
                        LangkahKecilWorksheet::create([
                            'anak_id' => $anak->id,
                            'type' => $w['type'] ?? '',
                            'data' => $w['data'] ?? [],
                            'date' => $w['date'] ?? null,
                        ]);
                    }
                }

                $results[] = [
                    'local_id' => $localId,
                    'server_id' => $anak->id,
                    'nama' => $anak->nama,
                ];
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'synced' => $results,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
