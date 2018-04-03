<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Entities\OrdersExports;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrdersProducts;
use Modules\Order\Entities\OrdersStatus;
use Modules\Order\Http\Requests\InvoiceExport;
use Modules\Order\Http\Requests\OrderCarrier;
use Modules\Order\Http\Requests\OrderStatus;
use Modules\Order\Entities\Status;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;
use \Mail;
use \ZipArchive;

class AdminOrdersController extends Controller
{

    /**
     * @var string
     */
    protected $viewPath = 'order::admin.orders.';

    /**
     * @var string
     */
    protected $route = 'orders.orders.';

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * @param string $viewPath
     */
    public function setViewPath(string $viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route)
    {
        $this->route = $route;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return $this->dataTable();
        }
        return view($this->viewPath . "index");
    }

    /**
     * @param string $uuid
     * @return mixed
     */
    public function getInvoice(string $uuid)
    {
        $order = Order::where('uuid', $uuid)->firstorFail();
        $name = config('ikCommerce.invoicePath') . "/" .
            config('ikCommerce.invoiceName') . "-" .
            $order->reference . '.pdf';
        return Storage::download($name);
    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $order = Order::where('uuid', $uuid)->firstorFail();
        $products = OrdersProducts::where('order_id', $order->id)->get();
        if(!empty($order->user_id)){
            $user = User::where('id', $order->user_id)->first();
            $nbOrder = Order::where('user_id', $order->user_id)->count();
            $totalOrder = Order::select('price_ttc')->where('user_id', $order->user_id)->sum('price_ttc');
        } else{
            $user = new User();
            $nbOrder = Order::where('user_email', $order->user_email)->count();
            $totalOrder = Order::select('price_ttc')->where('user_email', $order->user_email)->sum('price_ttc');
        }
        $status = Status::all();

        return view(
            $this->viewPath . "edit",
            compact('order','products', 'user', 'status', 'nbOrder', 'totalOrder')
        );
    }

    /**
     * @param OrderStatus $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusUpdate(OrderStatus $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $order = Order::where('uuid', $uuid)->firstOrFail();
        $status = Status::where('id', $request->status_id)->firstOrFail();

        if($order->status_id != $status->id) {

            Order::where('id', $order->id)->update([
               'status_id' => $status->id
            ]);

            OrdersStatus::where('order_id', $order->id)->create([
                'status_id' => $status->id,
                'order_id'  => $order->id
            ]);

            /* Envoie email au client */
            Mail::to($order->user_email)
                ->send(new \App\Mail\OrderStatus($order, $status));

            session()->flash('success',"Le statut de la commande a été mis à jours.");
        } else {
            session()->flash('error',"Le statut de la commande est identique.");
        }

        return redirect()->route($this->route . 'edit',['uuid' => $order->uuid]);

    }

    /**
     * @param OrderCarrier $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function carrierUpdate(OrderCarrier $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $order = Order::where('uuid', $uuid)->firstOrFail();

        Order::where('id', $order->id)->update([
            'shipping_url' => $request->shipping_url,
            'shipping_order_id' => $request->shipping_order_id,
            'shipping_delay' => $request->shipping_delay
        ]);

        /* Envoie email au client */
        Mail::to($order->user_email)
            ->send(new \App\Mail\OrderCarrier($order, $request->shipping_url, $request->shipping_order_id));

        session()->flash('success',"Les informations du transporteur ont été mises à jours.");
        return redirect()->route($this->route . 'edit',['uuid' => $order->uuid]);

    }


    public function destroy(string $uuid)
    {
        //
    }

    public function invoices()
    {
        $exports = OrdersExports::orderBy('id','desc')->get();
        return view($this->viewPath.'invoices', compact('exports'));
    }

    public function invoicesExport(InvoiceExport $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;

        $orders = Order::select('reference')
            ->where('created_at', '>=', $date_begin. ' 00:00:00')
            ->where('created_at','<=', $date_end.' 23:59:59')
            ->get();
        if(count($orders) == 0) {
            session()->flash('errors',"Aucune commande n'existe pour cette période.");
            return redirect()->route('orders.invoices');
            exit();
        }
        $uuid = Uuid::uuid4()->toString();
        $zipName = $this->fileNameExists("invoices-".$date_begin."-".$date_end).".zip";

        $zip = new ZipArchive;
        if ($zip->open(
            storage_path("app/" . config('ikCommerce.invoiceExportPath') . "/" . $zipName),
            ZipArchive::CREATE) === TRUE
        ) {
            foreach ($orders as $order) {
                $invoice = storage_path('app/'.
                    config('ikCommerce.invoicePath') . "/" .
                    config('ikCommerce.invoiceName') . "-" .
                    $order->reference . '.pdf');

                if(file_exists($invoice)) {
                    $zip->addFile($invoice, config('ikCommerce.invoiceName') . "-" . $order->reference . '.pdf');
                }

            }
            $zip->close();
        }

        OrdersExports::create([
            'uuid' => $uuid,
            'fileName' => $zipName,
            'begin' => $date_begin,
            'end' => $date_end
        ]);
        session()->flash('success',"Votre fichier d'export a bien été généré.");
        return redirect()->route('orders.invoices');
    }
    public function invoicesDownload(string $uuid)
    {
        $zip = OrdersExports::select("fileName")->where('uuid', $uuid)->firstOrFail();
        $name = config('ikCommerce.invoiceExportPath') . "/" . $zip->fileName;

        return Storage::download($name);

    }


    /**
     * @return mixed
     */
    private function dataTable()
    {
        $order = Order::select([
            'id',
            'uuid',
            'reference',
            'total_quantity',
            'price_ttc',
            'user_id',
            'user_firstname',
            'user_lastname',
            'delivery_country_name',
            'status_id',
            'updated_at'
        ]);
        return DataTables::of($order)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('user_id', 'admin.datatable.guest')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->editColumn('price_ttc', function(Order $order){
                return priceFormat($order->price_ttc)." ({$order->total_quantity})";
            })
            ->editColumn('user_firstname', function(Order $order){
                return $order->user_firstname." ".$order->user_lastname;
            })
            ->editColumn('status_id',function(Order $order){
                if(!empty($order->status_id))
                {
                    $html = '<div class="label"';
                        if(!empty($order->Status->color)) {
                            $html .=' style="background-color:'.$order->Status->color.';"';
                        }
                    $html .= '>';
                        $html .= $order->Status->name;
                    $html .= '</label>';
                    return $html;
                }
                return "";
            })
            ->rawColumns(['actions','price_ttc','user_firstname','user_id','status_id'])
            ->make(true);
    }

    /**
     * @return string
     */
    private function getTableButtons(): string
    {
        $html = '<a href="{{ route(\''.$this->route.'invoice\', [\'uuid\' => $uuid]) }}" class="table-button"><i class="fas fa-file-pdf"></i> Facture</a>';
        $html .= '<a href="{{ route(\''.$this->route.'edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> Editer</a>';
        $html .= '<a href="{{ route(\''.$this->route.'delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> Supprimer</a>';
        return $html;
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function fileNameExists(string $fileName): string
    {
        if(file_exists(storage_path(
            "app/". config('ikCommerce.invoiceExportPath') . "/" . $fileName . ".zip"
        ))) {
            return $this->fileNameExists($fileName ."_copy");
        }
        return $fileName;
    }
}
