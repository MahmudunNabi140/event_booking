<?php
    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Services\EventService;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Schema;
    use Inertia\Inertia;
    use App\Traits\SystemTrait;
    use Exception;

    class EventController extends Controller
    {
        use SystemTrait;

        protected $eventService;

        public function __construct(EventService $eventService)
        {
            $this->eventService = $eventService;
        }

        public function index()
        {
            return Inertia::render(
                'Backend/Event/Index',
                [
                    'pageTitle' => fn () => 'Event List',
                    'breadcrumbs' => fn () => [
                        ['link' => null, 'title' => 'Event Manage'],
                        ['link' => route('backend.event.index'), 'title' => 'Event List'],
                    ],
                    'tableHeaders' => fn () => $this->getTableHeaders(),
                    'dataFields' => fn () => $this->getDataFields(),
                    'datas' => fn () => $this->getDatas(),
                ]
            );
        }

    private function getDataFields()
    {
        return [
            ['fieldName' => 'index', 'class' => 'text-center'],
            ['fieldName' => 'name', 'class' => 'text-center'],
			['fieldName' => 'description', 'class' => 'text-center'],
			['fieldName' => 'date', 'class' => 'text-center'],
			['fieldName' => 'time', 'class' => 'text-center'],
			['fieldName' => 'location', 'class' => 'text-center'],
			['fieldName' => 'available_seats', 'class' => 'text-center'],
			['fieldName' => 'price', 'class' => 'text-center'],
            ['fieldName' => 'status', 'class' => 'text-center'],
        ];
    }
    private function getTableHeaders()
    {
        return [
            'Sl/No',
            'name',
			'description',
			'date',
			'time',
			'location',
			'available_seats',
			'price',
            'Status',
            'Action'
        ];
    }

    private function getDatas()
    {
        $query = $this->eventService->list();

        if(request()->filled('name'))
				$query->where('name', 'like', request()->title .'%');


        $datas = $query->paginate(request()->numOfData ?? 10)->withQueryString();

        $formatedDatas = $datas->map(function ($data, $index) {
            $customData = new \stdClass();
            $customData->index = $index + 1;
            $customData->name = $data->name;
            $customData->description = $data->description;
            $customData->date = $data->date;
            $customData->time = $data->time;
            $customData->location = $data->location;
            $customData->available_seats = $data->available_seats;
            $customData->price = $data->price;
            $customData->status = getStatusText($data->status);
            $customData->hasLink = true;
            $customData->links = [

                  [
                    'linkClass' => 'semi-bold text-white statusChange ' . (($data->status == 'Active') ? "bg-gray-500" : "bg-green-500"),
                    'link' => route('backend.event.status.change', ['id' => $data->id, 'status' => $data->status == 'Active' ? 'Inactive' : 'Active']),
                    'linkLabel' => getLinkLabel((($data->status == 'Active') ? "Inactive" : "Active"), null, null)
                ],

                [
                    'linkClass' => 'bg-yellow-400 text-black semi-bold',
                    'link' => route('backend.event.edit', $data->id),
                    'linkLabel' => getLinkLabel('Edit', null, null)
                ],
                [
                    'linkClass' => 'deleteButton bg-red-500 text-white semi-bold',
                    'link' => route('backend.event.destroy', $data->id),
                    'linkLabel' => getLinkLabel('Delete', null, null)
                ]
            ];
            return $customData;
        });

        return regeneratePagination($formatedDatas, $datas->total(), $datas->perPage(), $datas->currentPage());
    }

        public function create()
        {
            return Inertia::render(
                'Backend/Event/Form',
                [
                    'pageTitle' => fn () => 'Event Create',
                    'breadcrumbs' => fn () => [
                        ['link' => null, 'title' => 'Event Manage'],
                        ['link' => route('backend.event.create'), 'title' => 'Event Create'],
                    ],
                ]
            );
        }


        public function store(EventRequest $request)
        {

            DB::beginTransaction();
            try {

                $data = $request->validated();

                $dataInfo = $this->eventService->create($data);

                if ($dataInfo) {
                    $message = 'Event created successfully';
                    $this->storeAdminWorkLog($dataInfo->id, 'events', $message);

                    DB::commit();

                    return redirect()
                        ->back()
                        ->with('successMessage', $message);
                } else {
                    DB::rollBack();

                    $message = "Failed To create Event.";
                    return redirect()
                        ->back()
                        ->with('errorMessage', $message);
                }
            } catch (Exception $err) {

                DB::rollBack();
                $this->storeSystemError('Backend', 'EventController', 'store', substr($err->getMessage(), 0, 1000));

                DB::commit();
                $message = "Server Errors Occur. Please Try Again.";

                return redirect()
                    ->back()
                    ->with('errorMessage', $message);
            }
        }

        public function edit($id)
        {
            $event = $this->eventService->find($id);
            // dd($event);
            return Inertia::render(
                'Backend/Event/Form',
                [
                    'pageTitle' => fn () => 'Event Edit',
                    'breadcrumbs' => fn () => [
                        ['link' => null, 'title' => 'Event Manage'],
                        ['link' => route('backend.event.edit', $id), 'title' => 'Event Edit'],
                    ],
                    'event' => fn () => $event,
                    'id' => fn () => $id,
                ]
            );
        }

        public function update(EventRequest $request, $id)
        {
            DB::beginTransaction();
            try {
                $event = $this->eventService->find($id);
                $data = $request->validated();
                $dataInfo = $this->eventService->update($data, $id);
                if ($dataInfo->save()) {
                    $message = 'Event updated successfully';
                    $this->storeAdminWorkLog($dataInfo->id, 'events', $message);

                    DB::commit();

                    return redirect()
                        ->back()
                        ->with('successMessage', $message);
                } else {
                    DB::rollBack();

                    $message = "Failed To update Event.";
                    return redirect()
                        ->back()
                        ->with('errorMessage', $message);
                }
            } catch (Exception $err) {
                DB::rollBack();
                $this->storeSystemError('Backend', 'Eventscontroller', 'update', substr($err->getMessage(), 0, 1000));
                DB::commit();
                $message = "Server Errors Occur. Please Try Again.";
                return redirect()
                    ->back()
                    ->with('errorMessage', $message);
            }
        }

        public function destroy($id)
        {

            DB::beginTransaction();

            try {

                if ($this->eventService->delete($id)) {
                    $message = 'Event deleted successfully';
                    $this->storeAdminWorkLog($id, 'events', $message);

                    DB::commit();

                    return redirect()
                        ->back()
                        ->with('successMessage', $message);
                } else {
                    DB::rollBack();

                    $message = "Failed To Delete Event.";
                    return redirect()
                        ->back()
                        ->with('errorMessage', $message);
                }
            } catch (Exception $err) {
                DB::rollBack();
                $this->storeSystemError('Backend', 'Eventcontroller', 'destroy', substr($err->getMessage(), 0, 1000));
                DB::commit();
                $message = "Server Errors Occur. Please Try Again.";
                return redirect()
                    ->back()
                    ->with('errorMessage', $message);
            }
        }

    public function changeStatus()
    {
        DB::beginTransaction();

        try {
            $dataInfo = $this->eventService->changeStatus(request());

            if ($dataInfo->wasChanged()) {
                $message = 'Event ' . request()->status . ' Successfully';
                $this->storeAdminWorkLog($dataInfo->id, 'events', $message);

                DB::commit();

                return redirect()
                    ->back()
                    ->with('successMessage', $message);
            } else {
                DB::rollBack();

                $message = "Failed To " . request()->status . " Event.";
                return redirect()
                    ->back()
                    ->with('errorMessage', $message);
            }
        } catch (Exception $err) {
            DB::rollBack();
            $this->storeSystemError('Backend', 'EventsController', 'changeStatus', substr($err->getMessage(), 0, 1000));
            DB::commit();
            $message = "Server Errors Occur. Please Try Again.";
            return redirect()
                ->back()
                ->withErrors( ['error'=>$message]);
        }
    }
    public function Eventlist()
    {
        $events = Event::where('date', '>=', now())->paginate(10);
        return Inertia::render('Backend/Event/Form', ['events' => $events]);
    }
}
