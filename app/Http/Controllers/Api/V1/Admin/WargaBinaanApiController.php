<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreWargaBinaanRequest;
use App\Http\Requests\UpdateWargaBinaanRequest;
use App\Http\Resources\Admin\WargaBinaanResource;
use App\Models\WargaBinaan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WargaBinaanApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('warga_binaan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WargaBinaanResource(WargaBinaan::all());
    }

    public function store(StoreWargaBinaanRequest $request)
    {
        $wargaBinaan = WargaBinaan::create($request->all());

        if ($request->input('foto', false)) {
            $wargaBinaan->addMedia(storage_path('tmp/uploads/' . $request->input('foto')))->toMediaCollection('foto');
        }

        return (new WargaBinaanResource($wargaBinaan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WargaBinaan $wargaBinaan)
    {
        abort_if(Gate::denies('warga_binaan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WargaBinaanResource($wargaBinaan);
    }

    public function update(UpdateWargaBinaanRequest $request, WargaBinaan $wargaBinaan)
    {
        $wargaBinaan->update($request->all());

        if ($request->input('foto', false)) {
            if (!$wargaBinaan->foto || $request->input('foto') !== $wargaBinaan->foto->file_name) {
                if ($wargaBinaan->foto) {
                    $wargaBinaan->foto->delete();
                }

                $wargaBinaan->addMedia(storage_path('tmp/uploads/' . $request->input('foto')))->toMediaCollection('foto');
            }
        } elseif ($wargaBinaan->foto) {
            $wargaBinaan->foto->delete();
        }

        return (new WargaBinaanResource($wargaBinaan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WargaBinaan $wargaBinaan)
    {
        abort_if(Gate::denies('warga_binaan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wargaBinaan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
