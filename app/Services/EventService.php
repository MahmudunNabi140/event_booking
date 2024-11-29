<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    protected $eventModel;

    public function __construct(Event $eventModel)
    {
        $this->eventModel = $eventModel;
    }

    public function list()
    {
        return $this->eventModel->whereNull('deleted_at');
    }

    public function all()
    {
        return $this->eventModel->whereNull('deleted_at')->get();
    }

    public function find($id)
    {
        return $this->eventModel->find($id);
        
    }

    public function create(array $data)
    {
        return $this->eventModel->create($data);
    }

    public function update(array $data, $id)
    {
        $dataInfo = $this->eventModel->findOrFail($id);

        $dataInfo->update($data);

        return $dataInfo;
    }

    public function delete($id)
    {
        $dataInfo = $this->eventModel->find($id);

        if (!empty($dataInfo)) {

            $dataInfo->deleted_at = date('Y-m-d H:i:s');

            $dataInfo->status = 'Deleted';

            return ($dataInfo->save());
        }
        return false;
    }

    public function changeStatus($request)
    {
        $dataInfo = $this->eventModel->findOrFail($request->id);

        $dataInfo->update(['status' => $request->status]);

        return $dataInfo;
    }

    public function activeList()
    {
        return $this->eventModel->whereNull('deleted_at')->where('status', 'Active')->get();
    }

}
