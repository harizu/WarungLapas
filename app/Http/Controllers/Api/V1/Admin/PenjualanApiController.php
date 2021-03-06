<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Http\Resources\Admin\PenjualanResource;
use App\Models\Penjualan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PenjualanApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('penjualan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PenjualanResource(Penjualan::with(['product'])->get());
    }

    public function store(StorePenjualanRequest $request)
    {
        $penjualan = Penjualan::create($request->all());

        return (new PenjualanResource($penjualan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Penjualan $penjualan)
    {
        abort_if(Gate::denies('penjualan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PenjualanResource($penjualan->load(['product']));
    }

    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan)
    {
        $penjualan->update($request->all());

        return (new PenjualanResource($penjualan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Penjualan $penjualan)
    {
        abort_if(Gate::denies('penjualan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $penjualan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
