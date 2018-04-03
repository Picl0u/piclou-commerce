<?php

namespace Modules\Slider\Http\Controllers;

use App\Http\Picl0u\DeleteCache;
use App\Http\Picl0u\FormTranslate\FormTranslate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Modules\Slider\Http\Requests\Sliders;
use Modules\Slider\Entities\Slider;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminSliderController extends Controller
{
    use DeleteCache;

    protected $viewPath = 'slider::admin.';
    protected $route = 'sliders.';

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
        if ($request->ajax()) {
            return $this->dataTable();
        }

        return view($this->viewPath.'index');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = new Slider();
        return view($this->viewPath . 'create', compact('data'));
    }


    /**
     * @param Sliders $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Sliders $request)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $published = 0;
        if ($request->published == "on") {
            $published = 1;
        }
        $insertImage = '';
        if($request->hasFile('image')){
            $insertImage = uploadImage('sliders', $request->image);
        }

        $slider = Slider::create([
            'uuid' => Uuid::uuid4()->toString(),
            'published' => $published,
            'image' => $insertImage,
            'link' => $request->link,
            'position' => $request->position,
            'order' => (Slider::count()+1)
        ]);
        $slider
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->update();

        session()->flash('success',"Votre slide a bien été créée.");
        return redirect()->route($this->route . 'index');

    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Slider::where('uuid', $uuid)->FirstOrFail();

        return view($this->viewPath . 'edit', compact('data'));
    }


    /**
     * @param Sliders $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Sliders $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $slider = Slider::where('uuid', $uuid)->FirstOrFail();

        $published = 0;
        if ($request->published == "on") {
            $published = 1;
        }

        $insertImage = $slider->image;
        if($request->hasFile('image')){
            $insertImage = uploadImage('sliders', $request->image);
            if(file_exists($slider->image)) {
                unlink($slider->image);
            }
        }

        Slider::where('id', $slider->id)->update([
            'published' => $published,
            'image' => $insertImage,
            'link' => $request->link,
            'position' => $request->position,
        ]);

        $slider
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->update();

        /* Supprimer le cache */
        $this->flush('slider', $slider->id);

        session()->flash('success',"Votre slide a bien été modifiée.");
        return redirect()->route($this->route . 'index');

    }


    /**
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        unlink($slider->image);
        Slider::where('id', $slider->id)->delete();

        /* Supprimer le cache */
        $this->flush('slider', $slider->id);

        session()->flash('success',"Votre slide a bien été supprimée.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function positions(){
        $sliders = Slider::OrderBy('order','asc')->get();
        $datas = [];
        foreach($sliders as $slider){
            $datas[] = [
                'id' => $slider->id,
                'name' => $slider->getTranslation('name', config('app.locale')),
                'order' => $slider->order,
                'parent_id' => 0,
                'slug' => '',
            ];
        }
        return view($this->viewPath.'positions',compact('datas'));
    }

    public function positionsStore(Request $request)
    {
        if(config('ikCommerce.demo')) {
            return "Cette fonctionnalité a été désactivée pour la version démo.";
        }

        $datas = Slider::all();
        $dataArray = [];
        foreach ($datas as $data) {
            $dataArray[$data->id] = [
                'order' => $data->order
            ];
        }
        foreach ($request->orders as $key => $order) {
            if (!empty($order['id'])) {
                if ($dataArray[$order['id']]['order'] != $key) {
                    Slider::where('id', $order['id'])->update([
                        'order' => $key,
                    ]);
                }
            }
        }

        return "Les positions ont bien été mises à jours";
    }

    /**
     * Traductions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        return (new FormTranslate(Slider::class))->formRequest($request);
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $categories = Slider::select(['id','published','uuid','image','name','updated_at']);
        return DataTables::of($categories)
            ->editColumn('published', 'admin.datatable.published')
            ->editColumn('image', 'admin.datatable.image')
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('name', function(Slider $slider){
                return $slider->getTranslation('name', config('app.locale'));
            })
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->addColumn('actions', $this->getTableButtons())
            ->rawColumns(['actions','published','image'])
            ->make(true);
    }

    /**
     * @return string
     */
    private function getTableButtons(): string
    {
        $html = '<a href="{{ route(\'sliders.edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> Editer</a>';
        $html .= '<a href="{{ route(\'sliders.delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> Supprimer</a>';
        return $html;
    }
}
