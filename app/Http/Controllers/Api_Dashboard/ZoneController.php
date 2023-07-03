<?php

namespace App\Http\Controllers\Api_Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api_Dashboard\ZoneResource;
use App\Models\State;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Traits\Response;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    use Response;
    /**
     * get_all_zones
     *
     * @return void
     */
    public function get_all_zones()
    {
        $zones = ZoneResource::collection(Zone::query()->get());
        // return response
        return $this->responseSuccess($zones, 'get all zones');
    }

    /**
     * get_zone
     *
     * @return void
     */
    public function get_zone($id)
    {
        $zone = new ZoneResource(Zone::query()->findOrFail($id));
        // return response
        return $this->responseSuccess($zone, 'get zone');
    }


    /**
     * get_zone_with_delegates
     *
     * @param  mixed $id
     * @return void
     */
    public function get_zone_with_delegates($id)
    {
        $zone = new ZoneResource(Zone::query()->with('delegates')->findOrFail($id));
        // return response
        return $this->responseSuccess($zone, 'get zone this delegates');
    }

    /**
     * get_zone_with_shops
     *
     * @param  mixed $id
     * @return void
     */
    public function get_zone_with_shops($id)
    {
        $zone = new ZoneResource(Zone::query()->with('shops')->findOrFail($id));
        // return response
        return $this->responseSuccess($zone, 'get zone with shops');

    }


    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        // make a validation:
        $validator = Validator::make($request->all(), [
           'zones' => 'required',
           'zones.*.name_ar' => 'required',
           'zones.*.name_en' => 'required',
           'state_id' => 'required'
        ]);

        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        // Make operation of database
        $state = State::query()->findOrFail($request->state_id);
        $state->zones()->createMany($request->zones);

        // returned
        return $this->responseSuccess([], 'Added the zones Successfully');
    }


    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return void
     */
    public function update($id, Request $request)
    {
        // make a validation:
        $validator = Validator::make($request->all(), [
           'name_ar' => 'required',
           'name_en' => 'required',
        ]);

        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }

        // Make operation of database
        $zone = Zone::query()->findOrFail($id);
        // make update the zone
        $zone->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);

        // returned
        return $this->responseSuccess([], 'Updated the zone Successfully');

    }

    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        // Make operation of database
        $zone = Zone::query()->findOrFail($id);
        // make update the zone
        $zone->delete();
        // returned
        return $this->responseSuccess([], 'deleted the zone Successfully');
    }

    /**
     * update_toggle_status
     *
     * @param  mixed $id
     * @return void
     */
    public function update_toggle_status($id)
    {
        // Make operation of database
        $zone = Zone::query()->findOrFail($id);
        if($zone->status == 1) {
            // make update the zone
            $zone->update([
                'status' => 0,
            ]);
            // returned
            return $this->responseSuccess([], 'disactive the zone Successfully');
        } else {
            // make update the zone
            $zone->update([
                'status' => 1,
            ]);
            return $this->responseSuccess([], 'active the zone Successfully');

        }
    }

}
