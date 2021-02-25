<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySellerRequest;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\Seller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('seller_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sellers = Seller::all();

        return view('admin.sellers.index', compact('sellers'));
    }

    public function create()
    {
        abort_if(Gate::denies('seller_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sellers.create');
    }

    public function store(StoreSellerRequest $request)
    {
        $seller = Seller::create($request->all());

        return redirect()->route('admin.sellers.index');
    }

    public function edit(Seller $seller)
    {
        abort_if(Gate::denies('seller_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sellers.edit', compact('seller'));
    }

    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        $seller->update($request->all());

        return redirect()->route('admin.sellers.index');
    }

    public function show(Seller $seller)
    {
        abort_if(Gate::denies('seller_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sellers.show', compact('seller'));
    }

    public function destroy(Seller $seller)
    {
        abort_if(Gate::denies('seller_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seller->delete();

        return back();
    }

    public function massDestroy(MassDestroySellerRequest $request)
    {
        Seller::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
