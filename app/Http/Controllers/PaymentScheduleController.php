<?php

namespace App\Http\Controllers;

use App\Helpers\Messages;
use App\PaymentSchedule;
use Illuminate\Http\Request;

class PaymentScheduleController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new PaymentSchedule();
        $shared = [
            "model" => $model
        ];
        return view('payment-schedule.create', $shared);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $model = new PaymentSchedule();
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->save();
        return redirect()
            ->route('payment-schedules.index')
            ->with('success', Messages::INSERT_SUCCESS);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = $this->finById($id);
        $shared = [
            "model" => $model
        ];
        return view('payment-schedule.edit', $shared);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $model = $this->finById($id);
        $this->validate($request, $model->validateRules, $model->validateMessage);
        $model->fill($request->all());
        $model->save();
        return redirect()
            ->route('payment-schedules.index')
            ->with('success', Messages::UPDATE_SUCCESS);
    }

    /**
     * @param $id
     * @return PaymentSchedule
     */
    protected function finById($id)
    {
        return PaymentSchedule::findOrFail($id);
    }

}
