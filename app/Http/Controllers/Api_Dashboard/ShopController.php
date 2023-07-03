<?php

namespace App\Http\Controllers\Api_Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api_Dashboard\DelegateForAssignShopReource;
use App\Http\Resources\Api_Dashboard\ShopResource;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\Response;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    use Response;
    /**
     * get_all_shops
     *
     * @return void
     */
    public function get_all_shops()
    {
        // get all shops
        $shops = ShopResource::collection(Shop::query()->with('delegate')->get());
        // return response
        return $this->responseSuccess($shops, 'get all shops');
    }

    /**
     * get_shop
     *
     * @param  mixed $id
     * @return void
     */
    public function get_shop($id)
    {
        // get all shops
        $shops = new ShopResource(Shop::query()->with('delegate')->findOrFail($id));
        // return response
        return $this->responseSuccess($shops, 'get shop');
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
           'name_ar' => 'required',
           'name_en' => 'required',
           'fullname' => 'required',
           'phone' => 'required|digits:11|unique:shops,phone',
           'zone_id'  => 'required',
           'longitude'  => 'required',
           'latitude'  => 'required',
           'address'  => 'required',
           'image_profile' => 'required|image',
           'image_idt_front' => 'required|image',
           'image_idt_back' => 'required|image',
        ]);

        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }

        /* ----------------------- Make operation of database ----------------------- */

        // create a new shop
        $shop = Shop::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'fullname' => $request->fullname,
            'zone_id' => $request->zone_id,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'phone' => $request->phone,
            'address' => $request->address,
            'status'  => 1,
            'status_requested' => 1
        ]);

        if($shop) {
            /* ------------------------------ upload images ----------------------------- */
            $time = time();

            $image_profile_Path = $request->file('image_profile');
            $image_idt_front_Path = $request->file('image_idt_front');
            $image_idt_back_Path = $request->file('image_idt_back');

            // uploaded image profile:
            $image_profile_Name = "image-profile-{$shop->id}-{$time}.{$image_profile_Path->getClientOriginalExtension()}";
            $image_profile_Path->storeAs('public/shops/profiles', $image_profile_Name);
            // uploaded image identifier front:
            $image_idt_front_Name = "image-identifier-front-{$shop->id}-{$time}.{$image_idt_front_Path->getClientOriginalExtension()}";
            $image_idt_front_Path->storeAs('public/shops/identifiers', $image_idt_front_Name);

            // uploaded image identifier front:
            $image_idt_back_Name = "image-identifier-back-{$shop->id}-{$time}.{$image_idt_back_Path->getClientOriginalExtension()}";
            $image_idt_back_Path->storeAs('public/shops/identifiers', $image_idt_back_Name);

            $shop->image_profile = $image_profile_Name;
            $shop->image_idt_front = $image_idt_front_Name;
            $shop->image_idt_back = $image_idt_back_Name;

            $shop->save();

        } else {
            return $this->responseError('Problem in database.', []);
        }

        // returned
        return $this->responseSuccess([], 'Added the shop Successfully');

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
           'fullname' => 'required',
           'longitude' => 'required',
           'latitude' => 'required',
           'address'  => 'required',
           'phone' => 'required|digits:11|unique:shops,phone,'.$id,
           'zone_id'  => 'required',


        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        /* ----------------------- Make operation of database ----------------------- */
        $shop = Shop::query()->findOrFail($id);
        // update shop
        $shop->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'fullname' => $request->fullname,
            'zone_id' => $request->zone_id,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $time = time();
        // check found the image profile
        if($request->has('image_profile')) {
            // check exists profile
            if (Storage::disk('public')->exists("shops/profiles/{$shop->image_profile}")) {
                Storage::disk('public')->delete("shops/profiles/{$shop->image_profile}");
            }
            $image_profile_Path = $request->file('image_profile');
            // uploaded image profile:
            $image_profile_Name = "image-profile-{$shop->id}-{$time}.{$image_profile_Path->getClientOriginalExtension()}";
            // store this profile image
            $image_profile_Path->storeAs('public/shops/profiles', $image_profile_Name);

            // save in database
            $shop->image_profile = $image_profile_Name;
            $shop->save();
        }
        // check found the image front
        if($request->has('image_idt_front')) {
            // check exists image front
            if (Storage::disk('public')->exists("shops/identifiers/{$shop->image_idt_front}")) {
                Storage::disk('public')->delete("shops/identifiers/{$shop->image_idt_front}");
            }

            $image_idt_front_Path = $request->file('image_idt_front');
            // uploaded image identifier front:
            $image_idt_front_Name = "image-identifier-front-{$shop->id}-{$time}.{$image_idt_front_Path->getClientOriginalExtension()}";
            $image_idt_front_Path->storeAs('public/shops/identifiers', $image_idt_front_Name);

            // save in database
            $shop->image_idt_front = $image_idt_front_Name;
            $shop->save();

        }

        // check found the image back
        if($request->has('image_idt_back')) {
            // check exists image back
            if (Storage::disk('public')->exists("shops/identifiers/{$shop->image_idt_back}")) {
                Storage::disk('public')->delete("shops/identifiers/{$shop->image_idt_back}");
            }

            $image_idt_back_Path = $request->file('image_idt_back');

            $image_idt_back_Name = "image-identifier-back-{$shop->id}-{$time}.{$image_idt_back_Path->getClientOriginalExtension()}";
            $image_idt_back_Path->storeAs('public/shops/identifiers', $image_idt_back_Name);

            // save in database
            $shop->image_idt_back = $image_idt_back_Name;
            $shop->save();

        }

        // returned
        return $this->responseSuccess([], 'Update the shop Successfully');
    }


    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        // get shop
        $shop = Shop::query()->findOrFail($id);
        $deleted = $shop->delete();
        if($deleted) {
            // check exists profile
            if (Storage::disk('public')->exists("shops/profiles/{$shop->image_profile}")) {
                Storage::disk('public')->delete("shops/profiles/{$shop->image_profile}");
            }

            // check exists image front
            if (Storage::disk('public')->exists("shops/identifiers/{$shop->image_idt_front}")) {
                Storage::disk('public')->delete("shops/identifiers/{$shop->image_idt_front}");
            }

            // check exists image back
            if (Storage::disk('public')->exists("shops/identifiers/{$shop->image_idt_back}")) {
                Storage::disk('public')->delete("shops/identifiers/{$shop->image_idt_back}");
            }
        } else {
            return $this->responseError('Problem in database.', []);
        }

        // returned
        return $this->responseSuccess([], 'Delete the shop Successfully');
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
        $shop = Shop::query()->findOrFail($id);
        if($shop->status == 1) {
            // make update the shop
            $shop->update([
                'status' => 0,
            ]);
            // returned
            return $this->responseSuccess([], 'disactive the shop Successfully');
        } else {
            // make update the shop
            $shop->update([
                'status' => 1,
            ]);
            // returned
            return $this->responseSuccess([], 'active the shop Successfully');

        }
    }

    /**
     * make_approved
     *
     * @param  mixed $id
     * @return void
     */
    public function make_approved($id)
    {
        // Make operation of database
        $shop = Shop::query()->findOrFail($id);
        if($shop->status_requested == 0) {
            // make update the shop
            $shop->update([
                'status_requested' => 1,
            ]);
            // returned
            return $this->responseSuccess([], 'Approved the shop Successfully');
        } else {
            // returned
            return $this->responseSuccess([], 'Already Approved');

        }

    }

    /**
     * make_assign_with_delegate
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function make_assign_with_delegate(Request $request, $id)
    {
        // make a validation:
        $validator = Validator::make($request->all(), [
           'delegate_id' => 'required|exists:users,id',
        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }

        //get shop
        $shop = Shop::findorfail($id);
        // Make assign with delegate
        $shop->update(['user_id' => $request->delegate_id]);
        // returned
        return $this->responseSuccess([], 'Assign the shop with delegate a Successfully');

    }

    public function get_delegates_for_assign()
    {
        $delegates = DelegateForAssignShopReource::collection(User::query()->get());
        // return response
        return $this->responseSuccess($delegates, 'get all delegates');
    }
}
