<?php

namespace App\Http\Controllers;

use App\Models\LangkahKecilAnak;
use App\Models\LangkahKecilChallenge;
use App\Models\LangkahKecilChallengeHistory;
use App\Models\LangkahKecilChecklist;
use App\Models\LangkahKecilCompletedSkill;
use App\Models\LangkahKecilEvaluation;
use App\Models\LangkahKecilSchedule;
use App\Models\LangkahKecilSkill;
use App\Models\LangkahKecilSkillActivity;
use App\Models\LangkahKecilWorksheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LangkahKecilController extends Controller
{
    private function getUserAnakIds(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return [];
        }

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
        $anak = LangkahKecilAnak::when($userId, fn ($q) => $q->where('user_id', $userId))
            ->with(['skills.activities', 'completedSkills', 'challenges', 'challengeHistory', 'checklists', 'schedules', 'worksheets'])
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
            'settings' => 'nullable|array',
        ]);

        $data['user_id'] = $request->user()->id ?? null;
        if (! isset($data['umur']) && isset($data['tahun_lahir'])) {
            $data['umur'] = (int) date('Y') - (int) $data['tahun_lahir'];
        }
        $anak = LangkahKecilAnak::create($data);

        return response()->json($anak->load(['skills.activities', 'completedSkills']), 201);
    }

    public function updateAnak(Request $request, $id)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $id, $ids)) {
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
            'settings' => 'nullable|array',
        ]);

        if (! isset($data['umur']) && isset($data['tahun_lahir'])) {
            $data['umur'] = (int) date('Y') - (int) $data['tahun_lahir'];
        }

        $anak = LangkahKecilAnak::findOrFail($id);
        $anak->update($data);

        return response()->json($anak->load(['skills.activities', 'completedSkills']));
    }

    public function deleteAnak(Request $request, $id)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $id, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilAnak::where('id', $id)->delete();

        return response()->json(null, 204);
    }

    // ==================== SKILLS ====================

    public function addSkill(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'key' => 'required|string',
            'emoji' => 'nullable|string|max:10',
            'title' => 'required|string|max:255',
            'pilar' => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'color' => 'nullable|string|max:20',
        ]);

        $skill = LangkahKecilSkill::updateOrCreate(
            ['anak_id' => $anakId, 'key' => $data['key']],
            [
                'emoji' => $data['emoji'] ?? null,
                'title' => $data['title'],
                'pilar' => $data['pilar'] ?? null,
                'progress' => $data['progress'] ?? 0,
                'color' => $data['color'] ?? null,
            ]
        );

        return response()->json($skill->load('activities'), 201);
    }

    public function updateSkill(Request $request, $anakId, $skillId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'progress' => 'nullable|integer|min:0|max:100',
            'title' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        $skill = LangkahKecilSkill::where('anak_id', $anakId)->where('key', $skillId)->firstOrFail();
        $skill->update($data);

        return response()->json($skill->load('activities'));
    }

    public function deleteSkill(Request $request, $anakId, $skillId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilSkill::where('anak_id', $anakId)->where('key', $skillId)->delete();

        return response()->json(null, 204);
    }

    // ==================== ACTIVITIES ====================

    public function addActivity(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'skill_key' => 'required|string',
            'title' => 'required|string|max:255',
            'emoji' => 'nullable|string|max:10',
            'feature' => 'nullable|string',
            'date' => 'nullable|string|max:50',
        ]);

        $skill = LangkahKecilSkill::where('anak_id', $anakId)
            ->where('key', $data['skill_key'])
            ->first();

        if ($skill) {
            $activity = LangkahKecilSkillActivity::create([
                'skill_id' => $skill->id,
                'title' => $data['title'],
                'emoji' => $data['emoji'] ?? '📌',
                'feature' => $data['feature'] ?? null,
                'date' => $data['date'] ?? now()->format('d M Y'),
            ]);

            return response()->json($activity, 201);
        }

        return response()->json(['message' => 'Skill not found'], 404);
    }

    public function deleteActivity(Request $request, $anakId, $activityId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilSkillActivity::where('id', $activityId)
            ->whereHas('skill', fn ($q) => $q->where('anak_id', $anakId))
            ->delete();

        return response()->json(null, 204);
    }

    public function toggleActivity(Request $request, $anakId, $activityId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $activity = LangkahKecilSkillActivity::where('id', $activityId)
            ->whereHas('skill', fn ($q) => $q->where('anak_id', $anakId))
            ->firstOrFail();

        $activity->update(['completed' => ! $activity->completed]);

        return response()->json($activity);
    }

    // ==================== COMPLETED SKILLS ====================

    public function addCompletedSkill(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'key' => 'required|string',
            'emoji' => 'nullable|string|max:10',
            'title' => 'required|string|max:255',
            'pilar' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'completed_at' => 'nullable|date',
        ]);

        $completed = LangkahKecilCompletedSkill::updateOrCreate(
            ['anak_id' => $anakId, 'key' => $data['key']],
            [
                'emoji' => $data['emoji'] ?? null,
                'title' => $data['title'],
                'pilar' => $data['pilar'] ?? null,
                'color' => $data['color'] ?? null,
                'completed_at' => $data['completed_at'] ?? now(),
            ]
        );

        return response()->json($completed, 201);
    }

    public function deleteCompletedSkill(Request $request, $anakId, $key)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilCompletedSkill::where('anak_id', $anakId)->where('key', $key)->delete();

        return response()->json(null, 204);
    }

    // ==================== CHALLENGES ====================

    public function addChallenge(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'emoji' => 'nullable|string|max:10',
            'points' => 'nullable|integer|min:0',
            'maxPoints' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,completed,cancelled',
            'date' => 'nullable|string|max:50',
            'meta' => 'nullable|array',
        ]);

        $challenge = LangkahKecilChallenge::create([
            'anak_id' => $anakId,
            'category' => $data['category'],
            'title' => $data['title'],
            'emoji' => $data['emoji'] ?? null,
            'points' => $data['points'] ?? 0,
            'status' => $data['status'] ?? 'pending',
            'date' => $data['date'] ?? null,
            'meta' => $data['meta'] ?? ($data['maxPoints'] ? ['maxPoints' => $data['maxPoints']] : null),
        ]);

        return response()->json($challenge, 201);
    }

    public function updateChallenge(Request $request, $anakId, $challengeId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'category' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'emoji' => 'nullable|string|max:10',
            'points' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,completed,cancelled',
            'date' => 'nullable|string|max:50',
            'meta' => 'nullable|array',
        ]);

        $challenge = LangkahKecilChallenge::where('id', $challengeId)->where('anak_id', $anakId)->firstOrFail();
        $challenge->update($data);

        return response()->json($challenge);
    }

    public function deleteChallenge(Request $request, $anakId, $challengeId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilChallenge::where('id', $challengeId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }

    // ==================== CHALLENGE HISTORY ====================

    public function addChallengeHistory(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'date' => 'nullable|string|max:50',
            'meta' => 'nullable|array',
        ]);

        $history = LangkahKecilChallengeHistory::create([
            'anak_id' => $anakId,
            ...$data,
        ]);

        return response()->json($history, 201);
    }

    // ==================== CHECKLISTS ====================

    public function addChecklist(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'items' => 'nullable|array',
            'date' => 'nullable|string|max:50',
        ]);

        $checklist = LangkahKecilChecklist::create([
            'anak_id' => $anakId,
            ...$data,
        ]);

        return response()->json($checklist, 201);
    }

    public function updateChecklist(Request $request, $anakId, $checklistId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'items' => 'nullable|array',
        ]);

        $checklist = LangkahKecilChecklist::where('id', $checklistId)->where('anak_id', $anakId)->firstOrFail();
        $checklist->update($data);

        return response()->json($checklist);
    }

    public function deleteChecklist(Request $request, $anakId, $checklistId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilChecklist::where('id', $checklistId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }

    // ==================== SCHEDULES ====================

    public function addSchedule(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'label' => 'required|string|max:255',
            'time' => 'nullable|string|max:20',
            'done' => 'nullable|boolean',
            'date' => 'nullable|string|max:50',
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
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'label' => 'nullable|string|max:255',
            'time' => 'nullable|string|max:20',
            'done' => 'nullable|boolean',
        ]);

        $schedule = LangkahKecilSchedule::where('id', $scheduleId)->where('anak_id', $anakId)->firstOrFail();
        $schedule->update($data);

        return response()->json($schedule);
    }

    public function deleteSchedule(Request $request, $anakId, $scheduleId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilSchedule::where('id', $scheduleId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }

    // ==================== WORKSHEETS ====================

    public function addWorksheet(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'type' => 'required|string|max:50',
            'data' => 'nullable|array',
            'date' => 'nullable|string|max:50',
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
        if (! in_array((int) $anakId, $ids)) {
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

                $anak = LangkahKecilAnak::when($userId, fn ($q) => $q->where('user_id', $userId))
                    ->where('nama', $nama)
                    ->first();

                if (! $anak) {
                    $anak = LangkahKecilAnak::create([
                        'user_id' => $userId,
                        'nama' => $nama,
                        'gender' => $anakData['gender'] ?? null,
                        'umur' => $anakData['umur'] ?? ($anakData['tahun_lahir'] ? (int) date('Y') - (int) $anakData['tahun_lahir'] : null),
                        'tanggal_lahir' => $anakData['tanggal_lahir'] ?? $anakData['tanggal'] ?? null,
                        'bulan_lahir' => $anakData['bulan_lahir'] ?? $anakData['bulan'] ?? null,
                        'tahun_lahir' => $anakData['tahun_lahir'] ?? $anakData['tahun'] ?? null,
                        'emoji' => $anakData['emoji'] ?? '👶',
                        'settings' => $anakData['settings'] ?? [],
                    ]);
                } else {
                    $tahun = $anakData['tahun_lahir'] ?? $anakData['tahun'] ?? $anak->tahun_lahir;
                    $anak->update([
                        'gender' => $anakData['gender'] ?? $anak->gender,
                        'umur' => $anakData['umur'] ?? ($tahun ? (int) date('Y') - (int) $tahun : $anak->umur),
                        'tanggal_lahir' => $anakData['tanggal_lahir'] ?? $anakData['tanggal'] ?? $anak->tanggal_lahir,
                        'bulan_lahir' => $anakData['bulan_lahir'] ?? $anakData['bulan'] ?? $anak->bulan_lahir,
                        'tahun_lahir' => $tahun,
                        'settings' => $anakData['settings'] ?? $anak->settings,
                    ]);
                }

                // Sync skills
                if (isset($anakData['skills']) && is_array($anakData['skills'])) {
                    foreach ($anakData['skills'] as $s) {
                        $skill = LangkahKecilSkill::updateOrCreate(
                            ['anak_id' => $anak->id, 'key' => $s['key'] ?? ''],
                            [
                                'emoji' => $s['emoji'] ?? null,
                                'title' => $s['title'] ?? '',
                                'pilar' => $s['pilar'] ?? null,
                                'progress' => $s['progress'] ?? 0,
                                'color' => $s['color'] ?? null,
                            ]
                        );
                        if (isset($s['activities']) && is_array($s['activities'])) {
                            $skill->activities()->delete();
                            foreach ($s['activities'] as $a) {
                                $skill->activities()->create([
                                    'title' => $a['title'] ?? '',
                                    'emoji' => $a['emoji'] ?? null,
                                    'feature' => $a['feature'] ?? null,
                                    'date' => $a['date'] ?? null,
                                ]);
                            }
                        }
                    }
                }

                // Sync completed skills
                if (isset($anakData['completed_skills']) && is_array($anakData['completed_skills'])) {
                    foreach ($anakData['completed_skills'] as $cs) {
                        LangkahKecilCompletedSkill::updateOrCreate(
                            ['anak_id' => $anak->id, 'key' => $cs['key'] ?? ''],
                            [
                                'emoji' => $cs['emoji'] ?? null,
                                'title' => $cs['title'] ?? '',
                                'pilar' => $cs['pilar'] ?? null,
                                'color' => $cs['color'] ?? null,
                                'completed_at' => $cs['completed_at'] ?? $cs['completedAt'] ?? null,
                            ]
                        );
                    }
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

    // ==================== EVALUATIONS ====================

    public function getEvaluations(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $evaluations = LangkahKecilEvaluation::where('anak_id', $anakId)
            ->orderByDesc('created_at')
            ->get();

        $active = $evaluations->filter(fn ($e) => $e->points < $e->max_points);
        $completed = $evaluations->filter(fn ($e) => $e->points >= $e->max_points);

        $totalPoints = $completed->sum('points');
        $totalMax = $completed->sum('max_points');

        return response()->json([
            'evaluations' => $evaluations,
            'active' => $active->values(),
            'completed_count' => $completed->count(),
            'total_points' => $totalPoints,
            'total_max' => $totalMax,
        ]);
    }

    public function addEvaluation(Request $request, $anakId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'skill_key' => 'required|string',
            'skill_title' => 'nullable|string|max:255',
            'pilar' => 'nullable|string|max:100',
            'points' => 'required|integer|min:0|max:100',
            'max_points' => 'nullable|integer|min:1|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $maxPoints = $data['max_points'] ?? 10;

        $incomplete = LangkahKecilEvaluation::where('anak_id', $anakId)
            ->where('skill_key', $data['skill_key'])
            ->whereRaw('points < max_points')
            ->first();

        if ($incomplete) {
            $incomplete->update([
                'skill_title' => $data['skill_title'] ?? $incomplete->skill_title,
                'pilar' => $data['pilar'] ?? $incomplete->pilar,
                'points' => $data['points'],
                'max_points' => $maxPoints,
                'notes' => $data['notes'] ?? $incomplete->notes,
            ]);
            $evaluation = $incomplete;
        } else {
            $evaluation = LangkahKecilEvaluation::create([
                'anak_id' => $anakId,
                'skill_key' => $data['skill_key'],
                'skill_title' => $data['skill_title'] ?? null,
                'pilar' => $data['pilar'] ?? null,
                'points' => $data['points'],
                'max_points' => $maxPoints,
                'notes' => $data['notes'] ?? null,
            ]);
        }

        return response()->json($evaluation, 201);
    }

    public function deleteEvaluation(Request $request, $anakId, $evalId)
    {
        $ids = $this->getUserAnakIds($request);
        if (! in_array((int) $anakId, $ids)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        LangkahKecilEvaluation::where('id', $evalId)->where('anak_id', $anakId)->delete();

        return response()->json(null, 204);
    }
}
