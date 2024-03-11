<?php

namespace App\Http\Controllers;

use App\Models\notas;
use App\Http\Requests\StorenotasRequest;
use App\Http\Requests\UpdatenotasRequest;

class NotasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorenotasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(notas $notas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(notas $notas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatenotasRequest $request, notas $notas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(notas $notas)
    {
        //
    }
}
