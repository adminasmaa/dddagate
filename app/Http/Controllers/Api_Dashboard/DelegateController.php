<?php

namespace App\Http\Controllers\Api_Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api_Dashboard\DelegateResource;
use App\Models\User as Delegate;
use Illuminate\Http\Request;
use App\Traits\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DelegateController extends Controller
{
    use Response;

    /**
     * get_all_delegates
     *
     * @return void
     */
    public function get_all_delegates()
    {
        // get all delegates
        $delegates = DelegateResource::collection(Delegate::query()->get());
        // return response
        return $this->responseSuccess($delegates, 'get all delegates');

    }


    public function get_delegate($id)
    {
        // get delegate
        $delegate = new DelegateResource(Delegate::query()->findOrFail($id));
        // return response
        return $this->responseSuccess($delegate, 'get delegate');
    }

    public function store(Request $request)
    {
        // make a validation:
        $validator = Validator::make($request->all(), [
           'name' => 'required',
           'email' => 'required|email|unique:users,email',
           'password' => 'required|min:6',
           'phone' => 'required|digits:11|unique:users,phone',
           'car_type' => 'required',
           'zone_id'  => 'required',
           'image_profile' => 'required|image',
           'image_idt_front' => 'required|image',
           'image_idt_back' => 'required|image',
        ]);

        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        /* ----------------------- Make operation of database ----------------------- */

        // create a new delegate
        $delegate = Delegate::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'zone_id' => $request->zone_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'car_type' => $request->car_type,
            'ip_address'   => request()->ip(),
        ]);

        if($delegate) {
            /* ------------------------------ upload images ----------------------------- */
            $time = time();

            $image_profile_Path = $request->file('image_profile');
            $image_idt_front_Path = $request->file('image_idt_front');
            $image_idt_back_Path = $request->file('image_idt_back');

            // uploaded image profile:
            $image_profile_Name = "image-profile-{$delegate->id}-{$time}.{$image_profile_Path->getClientOriginalExtension()}";
            $image_profile_Path->storeAs('public/delegates/profiles', $image_profile_Name);
            // uploaded image identifier front:
            $image_idt_front_Name = "image-identifier-front-{$delegate->id}-{$time}.{$image_idt_front_Path->getClientOriginalExtension()}";
            $image_idt_front_Path->storeAs('public/delegates/identifiers', $image_idt_front_Name);

            // uploaded image identifier front:
            $image_idt_back_Name = "image-identifier-back-{$delegate->id}-{$time}.{$image_idt_back_Path->getClientOriginalExtension()}";
            $image_idt_back_Path->storeAs('public/delegates/identifiers', $image_idt_back_Name);

            $delegate->image_profile = $image_profile_Name;
            $delegate->image_idt_front = $image_idt_front_Name;
            $delegate->image_idt_back = $image_idt_back_Name;

            $delegate->save();

        } else {
            return $this->responseError('Problem in database.', []);
        }

        // returned
        return $this->responseSuccess([], 'Added the Delegate Successfully');

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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|digits:11|unique:users,phone,'.$id,
            'car_type' => 'required',
            'zone_id'  => 'required',
         ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        /* ----------------------- Make operation of database ----------------------- */
        $delegate = Delegate::query()->findOrFail($id);
        // update delegate
        $delegate->update([
            'name' => $request->name,
            'email' => $request->email,
            'zone_id' => $request->zone_id,
            'phone' => $request->phone,
            'car_type' => $request->car_type,
        ]);

        $time = time();
        // check found the image profile
        if($request->has('image_profile')) {
            // check exists profile
            if (Storage::disk('public')->exists("delegates/profiles/{$delegate->image_profile}")) {
                Storage::disk('public')->delete("delegates/profiles/{$delegate->image_profile}");
            }
            $image_profile_Path = $request->file('image_profile');
            // uploaded image profile:
            $image_profile_Name = "image-profile-{$delegate->id}-{$time}.{$image_profile_Path->getClientOriginalExtension()}";
            // store this profile image
            $image_profile_Path->storeAs('public/delegates/profiles', $image_profile_Name);

            // save in database
            $delegate->image_profile = $image_profile_Name;
            $delegate->save();
        }
        // check found the image front
        if($request->has('image_idt_front')) {
            // check exists image front
            if (Storage::disk('public')->exists("delegates/identifiers/{$delegate->image_idt_front}")) {
                Storage::disk('public')->delete("delegates/identifiers/{$delegate->image_idt_front}");
            }

            $image_idt_front_Path = $request->file('image_idt_front');
            // uploaded image identifier front:
            $image_idt_front_Name = "image-identifier-front-{$delegate->id}-{$time}.{$image_idt_front_Path->getClientOriginalExtension()}";
            $image_idt_front_Path->storeAs('public/delegates/identifiers', $image_idt_front_Name);

            // save in database
            $delegate->image_idt_front = $image_idt_front_Name;
            $delegate->save();

        }

        // check found the image back
        if($request->has('image_idt_back')) {
            // check exists image back
            if (Storage::disk('public')->exists("delegates/identifiers/{$delegate->image_idt_back}")) {
                Storage::disk('public')->delete("delegates/identifiers/{$delegate->image_idt_back}");
            }

            $image_idt_back_Path = $request->file('image_idt_back');

            $image_idt_back_Name = "image-identifier-back-{$delegate->id}-{$time}.{$image_idt_back_Path->getClientOriginalExtension()}";
            $image_idt_back_Path->storeAs('public/delegates/identifiers', $image_idt_back_Name);

            // save in database
            $delegate->image_idt_back = $image_idt_back_Name;
            $delegate->save();

        }

        // returned
        return $this->responseSuccess([], 'Update the Delegate Successfully');
    }

    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        // get delegate
        $delegate = Delegate::query()->findOrFail($id);
        $deleted = $delegate->delete();
        if($deleted) {
            // check exists profile
            if (Storage::disk('public')->exists("delegates/profiles/{$delegate->image_profile}")) {
                Storage::disk('public')->delete("delegates/profiles/{$delegate->image_profile}");
            }

            // check exists image front
            if (Storage::disk('public')->exists("delegates/identifiers/{$delegate->image_idt_front}")) {
                Storage::disk('public')->delete("delegates/identifiers/{$delegate->image_idt_front}");
            }

            // check exists image back
            if (Storage::disk('public')->exists("delegates/identifiers/{$delegate->image_idt_back}")) {
                Storage::disk('public')->delete("delegates/identifiers/{$delegate->image_idt_back}");
            }
        } else {
            return $this->responseError('Problem in database.', []);
        }

        // returned
        return $this->responseSuccess([], 'Delete the Delegate Successfully');

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
        $delegate = Delegate::query()->findOrFail($id);
        if($delegate->status == 1) {
            // make update the delegate
            $delegate->update([
                'status' => 0,
            ]);
            // returned
            return $this->responseSuccess([], 'disactive the delegate Successfully');
        } else {
            // make update the delegate
            $delegate->update([
                'status' => 1,
            ]);
            // returned
            return $this->responseSuccess([], 'active the delegate Successfully');

        }
    }

    /**
     * change_password
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function change_password(Request $request, $id)
    {
        // make a validation:
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
         ]);

        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }

        // Make operation of database
        $delegate = Delegate::query()->findOrFail($id);
        // update password
        $delegate->update(['password' => bcrypt($request->password)]);
        // returned
        return $this->responseSuccess([], 'Update password delegate Successfully');
    }

}
