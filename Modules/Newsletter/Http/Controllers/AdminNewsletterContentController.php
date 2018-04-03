<?php
namespace Modules\Newsletter\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Picl0u\DeleteCache;
use App\Http\Picl0u\FormTranslate\FormTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\Newsletter\Entities\NewsletterContents;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminNewsletterContentController extends Controller{

    use DeleteCache;

    /**
     * @var string
     */
    private $viewPath = 'newsletter::admin.content.';
    /**
     * @var string
     */
    private $route = 'admin.newsletter.content.';

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
        $data = new NewsletterContents();
        return view($this->viewPath . 'create', compact('data'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $insertImage = '';
        if($request->hasFile('image')){
            $insertImage = uploadImage('newsletter', $request->image);
        }

        $content = NewsletterContents::create([
            'uuid' => Uuid::uuid4()->toString(),
            'image' => $insertImage,
        ]);

        $content
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->update();

        session()->flash('success',"Le contenu a bien été créé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = NewsletterContents::where('uuid', $uuid)->FirstOrFail();

        return view($this->viewPath . 'edit', compact('data'));

    }

    /**
     * @param Request $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $uuid)
    {
        if(config('ikCommerce.demo')) {
            session()->flash('error',"Cette fonctionnalité a été désactivée pour la version démo.");
            return redirect()->route($this->route . 'index');
        }

        $content = NewsletterContents::where('uuid', $uuid)->FirstOrFail();

        $insertImage = $content->image;
        if($request->hasFile('image')){
            $insertImage = uploadImage('newsletter', $request->image);
            if(!empty($content->image)) {
                unlink($content->image);
            }
        }

        NewsletterContents::where('id', $content->id)->update([
            'image' => $insertImage,
        ]);
        $content
            ->setTranslation('name', config('app.locale'), $request->name)
            ->setTranslation('description', config('app.locale'), $request->description)
            ->update();

        /* Supprimer le cache */
        $this->flush('newsletter', $content->id);

        session()->flash('success',"Le contenu a bien été modifié.");
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

        $content = NewsletterContents::where('uuid', $uuid)->FirstOrFail();

        /* Supprimer le cache */
        $this->flush('content', $content->id);

        NewsletterContents::where('id', $content->id)->delete();

        if(!empty($content->image)) {
            unlink($content->image);
        }
        session()->flash('success',"Le contenu a bien été supprimé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * Traductions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        return (new FormTranslate(NewsletterContents::class))->formRequest($request);
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $contents = NewsletterContents::select(['id','uuid','name','image','updated_at']);
        return DataTables::of($contents)
            ->addColumn('actions', $this->getTableButtons())
            ->editColumn('name', function(NewsletterContents $contents){
                return $contents->getTranslation('name', 'fr');
            })
            ->editColumn('image', 'admin.datatable.image')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')

            ->rawColumns(['actions','image'])
            ->make(true);
    }

    /**
     * @return string
     */
    private function getTableButtons(): string
    {
        $html = '<a href="{{ route(\''.$this->route.'edit\', [\'uuid\' => $uuid]) }}" class="table-button edit-button"><i class="fas fa-pencil-alt"></i> Editer</a>';
        $html .= '<a href="{{ route(\''.$this->route.'delete\', [\'uuid\' => $uuid]) }}" class="table-button delete-button confirm-alert"><i class="fas fa-trash"></i> Supprimer</a>';
        return $html;
    }
}