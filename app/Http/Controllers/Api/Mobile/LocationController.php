<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Organisation\Location;
use App\Services\Mobile\MobileSerializer;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class LocationController extends MobileController
{
    public function __construct(
        protected ScheduleService $scheduleService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $scheduled = $this->scheduleService->getScheduledTasksForUser($user);

        $locations = Location::query()
            ->where('company_id', $user->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function (Location $location) use ($scheduled) {
                $open = $scheduled->where('location_id', $location->id)->count();

                return MobileSerializer::locationItem($location, $open);
            })
            ->values();

        return $this->success($locations);
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();
        $location = Location::query()
            ->where('company_id', $user->company_id)
            ->findOrFail($id);

        $open = $this->scheduleService
            ->getScheduledTasksForUser($user)
            ->where('location_id', $location->id)
            ->count();

        return $this->success(MobileSerializer::locationItem($location, $open));
    }
}
