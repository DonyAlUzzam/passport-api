<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Helpers\ResponseJson;

use Illuminate\Support\Facades\Validator;


class CrudController extends Controller
{
    protected $service;
    protected $viewDirectorry;

    protected function generateMessage($data, $type = null)
    {
        return;
    }

    protected function generateMessagev2($title = "data", $type = null){
        $translated = trans('messages', [], 'id');
        $message = '';
        $title = isset($translated[strtolower($title)]) ? $translated[strtolower($title)] : $title;
        switch ($type) {
            case 'create':
                $message = $title.' '.$translated['created successfully'];
                break;

            case 'update':
                $message = $title.' '.$translated['updated successfully'];
                break;

            case 'delete':
                $message = $title.' '.$translated['deleted successfully'];
                break;

            case 'restore':
                $message = $title.' '.$translated['restored successfully'];
                break;

            default:
                $message = $translated['success'];
                break;
        }

        return ucwords($message);
    }


    public function export(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->limit <= 30 ? $request->limit : 30;

        $dataTable = $this->service->getDatatable($request, $meta);

        // return response()->json([
        //     'error' => false,
        //     'message' => 'Successfully',
        //     'status_code' => HttpStatusCodes::HTTP_OK,
        //     'data' => $dataTable['data'],
        //     'pagination' => $dataTable['meta']
        // ], HttpStatusCodes::HTTP_OK);
        return ResponseJson::success($dataTable['data']);
    }

    public function index(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->limit <= 30 ? $request->limit : 30;

        $dataTable = $this->service->getDatatable($request, $meta);

        return ResponseJson::success([
            'data' => $dataTable['data'],
            'pagination' => $dataTable['meta']
        ]);
    }

    public function show(Request $request)
    {
        $validator = $this->runValidationShow($request);

        if ($validator->fails()) {
            return ResponseJson::error([
                'message'       => $validator->errors()->all()[0]
            ], HttpStatusCodes::HTTP_BAD_REQUEST);
        }

        $data = $this->service->getDetailByID($request->id);

        if($data==null){
            return ResponseJson::notFound('Data Tidak Ada.');
        }

        return ResponseJson::success([
            'data' => $data,
        ]);

    }

    public function store(Request $request)
    {
        $validator = $this->runValidationCreate($request);

        if ($validator->fails()) {
            return ResponseJson::error($validator->errors()->all()[0]);
        }

        $data = $this->service->store($request);

        return ResponseJson::success([
            'data' => $data,
        ], $this->generateMessage($data, 'create'));
    }

    public function restore(Request $request)
    {
        $validator = $this->runValidationRestore($request);

        if ($validator->fails()) {
            return ResponseJson::error($validator->errors()->all()[0]);
        }

        $data = $this->service->restore($request->id);

        return ResponseJson::success([
            'data' => $data,
        ], $this->generateMessage($data, 'restore'));
    }

    public function update(Request $request)
    {
        $validator = $this->runValidationUpdate($request);
        if ($validator->fails()) {
            return ResponseJson::error($validator->errors()->all()[0]);
        }

        $data = $this->service->update($request->id, $request);
        return ResponseJson::success([
            'data' => $data,
        ], $this->generateMessage($data, 'update'));
    }

    public function destroy(Request $request)
    {
        $validator =  $this->runValidationDestroy($request);

        if ($validator->fails()) {
            return ResponseJson::error($validator->errors()->all()[0]);
        }

        $obj = $this->service->delete($request->id);
        // dd($obj);

        return ResponseJson::success(null, $this->generateMessage($obj, 'delete'));
    }
}
