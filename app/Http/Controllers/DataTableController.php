<?php

namespace App\Http\Controllers;

use App\DataTable\DataTableCustomer;
use App\Models\Customer;
use DataTable;
use Illuminate\Http\Request;

class DataTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * API to load DataTable data
     *
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    public function getCustomerList(Request $request)
    {

        $params = $request->post();

        $customer = new DataTableCustomer();
        $customer->setSearch($params['search']['value']);
        $customer->setPage($params['length'], $params['start']);
        $customer->setOrder([
            'column' => $params['order'][0]['column'],
            'direction' => $params['order'][0]['dir']
        ]);
        $customer->setDraw($params['draw']);

        $customer->setActionButton(false, false, false);
        $customer->setActionRoute(
            'datatable.show',
            'datatable.edit',
            'datatable.destroy'
        );

        echo json_encode($customer->getDataTable());
    }
}
