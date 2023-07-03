<?php

namespace App\Http\Controllers\Api_mobile\Auth;

use App\Http\Resources\Api_Mobile\ShopResource;
use App\Http\Resources\Api_Mobile\ZoneResource;
use App\Models\Zone;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Response;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Validator;

class ShopContoller extends Controller
{
    use Response;

    public function detailshop(Request $request)
    {
        // Make a validation of shop
        $validator = Validator::make($request->all(), [
            'shop_id' => 'required',
        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        $shops = Shop::find($request->shop_id);

        $shop = new ShopResource($shops);
        return response()->json([
            'success' => true,
            'message' => trans('message.Comment retrieved successfully.'),
            'data' => $shop
        ]);
    }

    public function listallshop(Request $request)
    {

        $shops = Shop::get();
        $shop = ShopResource::collection($shops);

        return response()->json([
            'success' => true,
            'message' => trans('message.Comment retrieved successfully.'),
            'data' => $shop
        ]);
    }

    public function listallzone(Request $request)
    {

        $zones = Zone::get();

        $zoness = ZoneResource::collection($zones);
        return response()->json([
            'success' => true,
            'message' => trans('message.Comment retrieved successfully.'),
            'data' => $zoness
        ]);
    }

    public function addshop(Request $request)
    {
//        return $request;
        // Make a validation of shop
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'name' => 'required',
            'zone_id' => 'required',
            'phone' => 'required|min:9|unique:shops',
            'address' => 'required|string',
            'latitude' => 'required',
//            'image_profile' => 'required',
//            'image_idt_front' => 'required',
//            'image_idt_back' => 'required',
        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        $input = $request->except('name', 'image_profile', 'image_idt_back', 'image_idt_front');
        $input['name_en'] = $request->name ?? '';
        $input['name_ar'] = $request->name ?? '';

        $shop = Shop::create($input);


        $time = time();

        $image_profile_Path = $request->file('image_profile');
        $image_idt_front_Path = $request->file('image_idt_front');
        $image_idt_back_Path = $request->file('image_idt_back');

        // uploaded image profile:
        if (!empty($image_profile_Path)) {

            $image_profile_Name = "image-profile-{$shop->id}-{$time}.{$image_profile_Path->getClientOriginalExtension()}";
            $image_profile_Path->storeAs('public/shops/profiles', $image_profile_Name);
            $shop->image_profile = $image_profile_Name;

        }
        // uploaded image identifier front:
        if (!empty($image_idt_front_Path)) {
            $image_idt_front_Name = "image-identifier-front-{$shop->id}-{$time}.{$image_idt_front_Path->getClientOriginalExtension()}";
            $image_idt_front_Path->storeAs('public/shops/identifiers', $image_idt_front_Name);
            $shop->image_idt_front = $image_idt_front_Name;

        }
        // uploaded image identifier front:
        if (!empty($image_idt_back_Path)) {

            $image_idt_back_Name = "image-identifier-back-{$shop->id}-{$time}.{$image_idt_back_Path->getClientOriginalExtension()}";
            $image_idt_back_Path->storeAs('public/shops/identifiers', $image_idt_back_Name);

            $shop->image_idt_back = $image_idt_back_Name;
        }

        $shop->save();

        $shops = new ShopResource($shop);

        return response()->json([
            'success' => true,
            'message' => trans('message.User register successfully.'),
            'data' => $shops
        ]);
    }

    public function updateShop(Request $request, $id)
    {
        // Make a validation of shop
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'name' => 'required',
            'zone_id' => 'required',
            'phone' => 'required|min:9',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        // check if find the errors
        if ($validator->fails()) {
            return $this->responseError('Validation Error.', $validator->errors());
        }
        $input = $request->except('name', 'image_profile', 'image_idt_front', 'image_idt_back');
        $input['name_en'] = $request->name ?? '';
        $input['name_ar'] = $request->name ?? '';

        $shop = Shop::find($id);
        $shop->update($input);

        $time = time();

        $image_profile_Path = $request->file('image_profile');
        $image_idt_front_Path = $request->file('image_idt_front');
        $image_idt_back_Path = $request->file('image_idt_back');

        // uploaded image profile:
        if (!empty($image_profile_Path)) {

            $image_profile_Name = "image-profile-{$shop->id}-{$time}.{$image_profile_Path->getClientOriginalExtension()}";
            $image_profile_Path->storeAs('public/shops/profiles', $image_profile_Name);
            $shop->image_profile = $image_profile_Name;


        }
        // uploaded image identifier front:
        if (!empty($image_idt_front_Path)) {
            $image_idt_front_Name = "image-identifier-front-{$shop->id}-{$time}.{$image_idt_front_Path->getClientOriginalExtension()}";
            $image_idt_front_Path->storeAs('public/shops/identifiers', $image_idt_front_Name);
            $shop->image_idt_front = $image_idt_front_Name;

        }
        // uploaded image identifier front:
        if (!empty($image_idt_back_Path)) {

            $image_idt_back_Name = "image-identifier-back-{$shop->id}-{$time}.{$image_idt_back_Path->getClientOriginalExtension()}";
            $image_idt_back_Path->storeAs('public/shops/identifiers', $image_idt_back_Name);

            $shop->image_idt_back = $image_idt_back_Name;
        }

        $shop->save();

        $shops = new ShopResource($shop);

        return response()->json([
            'success' => true,
            'message' => trans('message.User updated successfully'),
            'data' => $shops
        ]);
    }

}
