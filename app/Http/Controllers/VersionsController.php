<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

class VersionsController extends Controller
{
    public function index(Room $room)
    {
    	$versions = $room->versions;

    	if (request()->expectsJson()) return ['versions' => $versions];
    }
}
