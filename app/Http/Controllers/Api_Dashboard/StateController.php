<?php

namespace App\Http\Controllers\Api_Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api_Dashboard\StateResource;
use App\Models\State;
use Illuminate\Http\Request;
use App\Traits\Response;

class StateController extends Controller
{
    use Response;

    /**
     * get_all_states
     *
     * @return void
     */
    public function get_all_states()
    {
        // get all states
        $states = StateResource::collection(State::query()->get());
        // return response
        return $this->responseSuccess($states, 'get all states');

    }

    /**
     * get_state_with_zones
     *
     * @param  mixed $state
     * @return void
     */
    public function get_state_with_zones($id)
    {
        // get state
        $state = new StateResource(State::query()->with('zones')->findOrFail($id));
        // return response
        return $this->responseSuccess($state, 'get state');
    }
}
