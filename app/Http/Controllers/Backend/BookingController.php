<?php
    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\BookingRequest;
    use App\Services\AdminService;
    use App\Services\BookingService;
    use App\Services\EventService;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Schema;
    use Inertia\Inertia;
    use App\Traits\SystemTrait;
    use Exception;

    class BookingController extends Controller
    {
        use SystemTrait;

        protected $bookingService,$adminService,$eventService;

        public function __construct(BookingService $bookingService,AdminService $adminService,EventService $eventService)
        {
            $this->bookingService = $bookingService;
            $this->adminService = $adminService;
            $this->eventService = $eventService;
        }

        public function index()
        {
            return Inertia::render(
                'Backend/Booking/Index',
                [
                    'pageTitle' => fn () => 'Booking List',
                    'breadcrumbs' => fn () => [
                        ['link' => null, 'title' => 'Booking Manage'],
                        ['link' => route('backend.booking.index'), 'title' => 'Booking List'],
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
            ['fieldName' => 'user_id', 'class' => 'text-center'],
			['fieldName' => 'event_id', 'class' => 'text-center'],
			['fieldName' => 'seats', 'class' => 'text-center'],
            ['fieldName' => 'status', 'class' => 'text-center'],
        ];
    }
    private function getTableHeaders()
    {
        return [
            'Sl/No',
            'User Name',
			'Event Name',
			'Seats',
            'Status',
            'Action'
        ];
    }

    private function getDatas()
    {
        $query = $this->bookingService->list();

        if(request()->filled('user_id'))
				$query->where('user_id', 'like', request()->title .'%');


        $datas = $query->paginate(request()->numOfData ?? 10)->withQueryString();

        $formatedDatas = $datas->map(function ($data, $index) {
            $customData = new \stdClass();
            $customData->index = $index + 1;
            $customData->user_id = $data->user->name;
            $customData->event_id = $data->event->name;
            $customData->seats = $data->seats;
            $customData->status = getStatusText($data->status);
            $customData->hasLink = true;
            $customData->links = [

                  [
                    'linkClass' => 'semi-bold text-white statusChange ' . (($data->status == 'Active') ? "bg-gray-500" : "bg-green-500"),
                    'link' => route('backend.booking.status.change', ['id' => $data->id, 'status' => $data->status == 'Active' ? 'Inactive' : 'Active']),
                    'linkLabel' => getLinkLabel((($data->status == 'Active') ? "Inactive" : "Active"), null, null)
                ],

                [
                    'linkClass' => 'bg-yellow-400 text-black semi-bold',
                    'link' => route('backend.booking.edit', $data->id),
                    'linkLabel' => getLinkLabel('Edit', null, null)
                ],
                [
                    'linkClass' => 'deleteButton bg-red-500 text-white semi-bold',
                    'link' => route('backend.booking.destroy', $data->id),
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
                'Backend/Booking/Form',
                [
                    'pageTitle' => fn () => 'Booking Create',
                    'breadcrumbs' => fn () => [
                        ['link' => null, 'title' => 'Booking Manage'],
                        ['link' => route('backend.booking.create'), 'title' => 'Booking Create'],
                    ],
                    'user' => fn () => $this->adminService->all(),
                    'event' => fn () => $this->eventService->all(),
                ]
            );
        }


        public function store(BookingRequest $request)
        {

            DB::beginTransaction();
            try {

                $data = $request->validated();

                $dataInfo = $this->bookingService->create($data);

                if ($dataInfo) {
                    $message = 'Booking created successfully';
                    $this->storeAdminWorkLog($dataInfo->id, 'bookings', $message);

                    DB::commit();

                    return redirect()
                        ->back()
                        ->with('successMessage', $message);
                } else {
                    DB::rollBack();

                    $message = "Failed To create Booking.";
                    return redirect()
                        ->back()
                        ->with('errorMessage', $message);
                }
            } catch (Exception $err) {

                DB::rollBack();
                $this->storeSystemError('Backend', 'BookingController', 'store', substr($err->getMessage(), 0, 1000));

                DB::commit();
                $message = "Server Errors Occur. Please Try Again.";

                return redirect()
                    ->back()
                    ->with('errorMessage', $message);
            }
        }

        public function edit($id)
        {
            $booking = $this->bookingService->find($id);
            return Inertia::render(
                'Backend/Booking/Form',
                [
                    'pageTitle' => fn () => 'Booking Edit',
                    'breadcrumbs' => fn () => [
                        ['link' => null, 'title' => 'Booking Manage'],
                        ['link' => route('backend.booking.edit', $id), 'title' => 'Booking Edit'],
                    ],
                    'booking' => fn () => $booking,
                    'id' => fn () => $id,
                    'user' => fn () => $this->adminService->all(),
                    'event' => fn () => $this->eventService->all(),
                ]
            );
        }

        public function update(BookingRequest $request, $id)
        {
            DB::beginTransaction();
            try {
                $booking = $this->bookingService->find($id);
                $data = $request->validated();
                $dataInfo = $this->bookingService->update($data, $id);
                if ($dataInfo->save()) {
                    $message = 'Booking updated successfully';
                    $this->storeAdminWorkLog($dataInfo->id, 'bookings', $message);

                    DB::commit();

                    return redirect()
                        ->back()
                        ->with('successMessage', $message);
                } else {
                    DB::rollBack();

                    $message = "Failed To update Booking.";
                    return redirect()
                        ->back()
                        ->with('errorMessage', $message);
                }
            } catch (Exception $err) {
                DB::rollBack();
                $this->storeSystemError('Backend', 'Bookingcontroller', 'update', substr($err->getMessage(), 0, 1000));
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

                if ($this->bookingService->delete($id)) {
                    $message = 'Booking deleted successfully';
                    $this->storeAdminWorkLog($id, 'bookings', $message);

                    DB::commit();

                    return redirect()
                        ->back()
                        ->with('successMessage', $message);
                } else {
                    DB::rollBack();

                    $message = "Failed To Delete Booking.";
                    return redirect()
                        ->back()
                        ->with('errorMessage', $message);
                }
            } catch (Exception $err) {
                DB::rollBack();
                $this->storeSystemError('Backend', 'Bookingcontroller', 'destroy', substr($err->getMessage(), 0, 1000));
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
            $dataInfo = $this->bookingService->changeStatus(request());

            if ($dataInfo->wasChanged()) {
                $message = 'Booking ' . request()->status . ' Successfully';
                $this->storeAdminWorkLog($dataInfo->id, 'bookings', $message);

                DB::commit();

                return redirect()
                    ->back()
                    ->with('successMessage', $message);
            } else {
                DB::rollBack();

                $message = "Failed To " . request()->status . " Booking.";
                return redirect()
                    ->back()
                    ->with('errorMessage', $message);
            }
        } catch (Exception $err) {
            DB::rollBack();
            $this->storeSystemError('Backend', 'BookingController', 'changeStatus', substr($err->getMessage(), 0, 1000));
            DB::commit();
            $message = "Server Errors Occur. Please Try Again.";
            return redirect()
                ->back()
                ->withErrors( ['error'=>$message]);
        }
    }
}
