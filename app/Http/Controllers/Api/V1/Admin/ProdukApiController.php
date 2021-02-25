<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Http\Resources\Admin\ProdukResource;
use App\Models\Produk;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProdukApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('produk_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProdukResource(Produk::with(['seller'])->get());
    }

    public function store(StoreProdukRequest $request)
    {
        $produk = Produk::create($request->all());

        if ($request->input('foto_produk', false)) {
            $produk->addMedia(storage_path('tmp/uploads/' . $request->input('foto_produk')))->toMediaCollection('foto_produk');
        }

        return (new ProdukResource($produk))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Produk $produk)
    {
        abort_if(Gate::denies('produk_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProdukResource($produk->load(['seller']));
    }

    public function update(UpdateProdukRequest $request, Produk $produk)
    {
        $produk->update($request->all());

        if ($request->input('foto_produk', false)) {
            if (!$produk->foto_produk || $request->input('foto_produk') !== $produk->foto_produk->file_name) {
                if ($produk->foto_produk) {
                    $produk->foto_produk->delete();
                }

                $produk->addMedia(storage_path('tmp/uploads/' . $request->input('foto_produk')))->toMediaCollection('foto_produk');
            }
        } elseif ($produk->foto_produk) {
            $produk->foto_produk->delete();
        }

        return (new ProdukResource($produk))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Produk $produk)
    {
        abort_if(Gate::denies('produk_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $produk->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
