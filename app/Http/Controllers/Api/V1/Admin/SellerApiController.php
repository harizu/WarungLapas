<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Http\Resources\Admin\SellerResource;
use App\Models\Seller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('seller_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SellerResource(Seller::all());
    }

    public function store(StoreSellerRequest $request)
    {
        $seller = Seller::create($request->all());

        return (new SellerResource($seller))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Seller $seller)
    {
        abort_if(Gate::denies('seller_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SellerResource($seller);
    }

    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        $seller->update($request->all());

        return (new SellerResource($seller))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Seller $seller)
    {
        abort_if(Gate::denies('seller_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seller->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
