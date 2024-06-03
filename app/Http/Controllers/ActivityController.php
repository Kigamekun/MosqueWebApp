<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ActivityController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $blogs = Activity::where('title', 'like', '%' . $search . '%')->paginate(10);
        } else if ($_GET['type'] == 'coming'){
            $activities = Activity::where('start_date', '>', Carbon::now())->get();

            $groupedActivities = $activities->groupBy(function($activity) {
                return Carbon::parse($activity->start_date)->format('m-Y');
            });

            $formattedData = [];
            foreach ($groupedActivities as $month => $activities) {
                $formattedData[$month] = $activities;
            }

            return response()->json([
                'message' => 'Data berhasil di load',
                'status' => 'success',
                'data' => $formattedData,
                'statusCode' => 200
            ], 200);
        } else if ($_GET['type'] == 'today'){

            $activities = Activity::whereDate('start_date', Carbon::today())->get();

            $groupedActivities = $activities->groupBy(function($activity) {
                return Carbon::parse($activity->start_date)->format('m-Y');
            });

            $formattedData = [];
            foreach ($groupedActivities as $month => $activities) {
                $formattedData[$month] = $activities;
            }

            return response()->json([
                'message' => 'Data berhasil di load',
                'status' => 'success',
                'data' => $formattedData,
                'statusCode' => 200
            ], 200);

        } else {
            $blogs = Activity::paginate(10);
        }
        return response()->json(['message' => 'Data berhasil di load', 'status' => 'success','data' => $blogs, 'statusCode' => 200], 200);
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',

            ]);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $activity = new Activity();
        $activity->title = $request->title;
        $activity->description = $request->description;
        $activity->start_date = $request->start_date;
        $activity->end_date = $request->end_date;
        $activity->user_id = auth()->user()->id;

        $activity->save();

        return response()->json(['message' => 'Activity berhasil di tambahkan', 'status' => 'success','data' => $activity, 'statusCode' => 200], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data tidak lengkap', 'status' => 'error', 'statusCode' => 400,'errors' => $th->validator->errors()], 400);
        }

        $activity = Activity::findOrFail($id);
        $activity->title = $request->title;
        $activity->description = $request->description;
        $activity->start_date = $request->start_date;
        $activity->end_date = $request->end_date;
        $activity->user_id = auth()->user()->id;

        $activity->save();

        return response()->json(['message' => 'Activity berhasil di update', 'status' => 'success','data' => $activity, 'statusCode' => 200], 200);
    }

    public function destroy($id)
    {
        $blog = Activity::findOrFail($id);
        if ($blog->thumbnail) {
            $filePath = Storage::disk('public')->path('blog/'.$blog->thumbnail);
            File::delete($filePath);
        }
        $blog->delete();

        return response()->json(['message' => 'Activity berhasil di hapus', 'status' => 'success', 'statusCode' => 200], 200);
    }
}
