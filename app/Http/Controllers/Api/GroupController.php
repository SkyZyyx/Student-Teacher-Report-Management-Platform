<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getGroupsBySection($sectionId)
    {
        $groups = Group::where('section_id', $sectionId)->get();
        return response()->json($groups);
    }
}
