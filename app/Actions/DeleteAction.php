<?php

namespace App\Actions;

use App\Concerns\PayloadTrait;
use App\Concerns\RulesTrait;
use App\Models\Product;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Validator;

class DeleteAction
{
    use AsAction, RulesTrait, PayloadTrait;

    public function rules(): array
    {
        return [
            'ids' => 'required|array'
        ];
    }

    public function handle(Request $request, $model)
    {
        $this->model = $model;
        $data = $request->validate($this->rules());

        try {

            $model->whereIn($model->field_primary(), $data['ids'])->delete();
            return $this->payload(TOAST_SUCCESS, $data['ids']);

        } catch (\Throwable $th) {
            return $this->payload(TOAST_FAILED, $th->getMessage());
        }
    }

    public function remove($id, $model)
    {
        $validator = Validator::make(['id' => request()->input('id', $id)], ['id' => 'required']);

         if ($validator->fails()) {
            $errors = $validator->errors();

            return $this->payload($validator->errors()->first(), $errors);
         }

        try {
            $model->findOrFail($id)->delete();
            return $this->payload(TOAST_SUCCESS, ['id' => $id]);

        } catch (\Throwable $th) {
            return $this->payload(TOAST_FAILED, $th->getMessage());
        }
    }
}
