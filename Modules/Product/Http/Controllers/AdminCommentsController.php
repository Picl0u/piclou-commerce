<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Entities\Comment;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class AdminCommentsController extends Controller
{

    /**
     * @var string
     */
    private $viewPath = 'product::admin.comments.';

    /**
     * @var string
     */
    private $route = 'admin.products.comments.';

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
        $data = new Comment();
        $users = User::select('id','firstname','lastname','email')->where('role', 'user')->orderBy('email','asc')->get();
        $products = Product::select('id','reference','name')->orderBy('name','asc')->get();
        
        return view($this->viewPath.'create',compact('data','users','products'));
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

        $user = User::where('id', $request->user_id)->first();
        $product = Product::where('id', $request->product_id)->first();

        $published = 0;
        if($request->published == 'on'){
            $published = 1;
        }

        Comment::create([
            'uuid' => Uuid::uuid4()->toString(),
            'published' => $published,
            'product_id' => $product->id,
            'user_id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'comment' => $request->comment
        ]);
        session()->flash('success',"Le commentaire a bien été créé.");
        return redirect()->route($this->route . 'index');

    }

    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $uuid)
    {
        $data = Comment::where('uuid', $uuid)->firstOrFail();
        $users = User::select('id','firstname','lastname','email')->where('role', 'user')->orderBy('email','asc')->get();
        $products = Product::select('id','reference','name')->orderBy('name','asc')->get();
        return view($this->viewPath . 'edit', compact('data','users','products'));
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

        $comment = Comment::where('uuid', $uuid)->firstOrFail();

        $user = User::where('id', $request->user_id)->first();
        $product = Product::where('id', $request->product_id)->first();

        $published = 0;
        if ($request->published == "on") {
            $published = 1;
        }

        Comment::where('id',$comment->id)->update([
            'published' => $published,
            'product_id' => $product->id,
            'user_id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'comment' => $request->comment
        ]);

        session()->flash('success',"Le commentaire a bien été modifié.");
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

        $comment = Comment::where('uuid', $uuid)->firstOrFail();
        Comment::where("id", $comment->id)->delete();

        session()->flash('success',"Le commentaire a bien été supprimé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $comments = Comment::select(['id','published','uuid','product_id','firstname','lastname','updated_at']);
        return DataTables::of($comments)
            ->editColumn('published', 'admin.datatable.published')
            ->editColumn('updated_at', 'admin.datatable.updatedAt')
            ->addColumn('fullname',function(Comment $comment){
                return $comment->firstname . ' ' . $comment->lastname;
            })
            ->editColumn('product_id',function(Comment $comment){
                $productLink = route('product.show',[
                    'slug' => $comment->Product->getTranslation('slug', config('app.locale')),
                    'id' => $comment->Product->id
                ]);

                return '<a href="' . $productLink . '" target="_blank">'.
                    $comment->Product->name . ' - ' . $comment->Product->reference.
                    '</a>';
            })
            ->addColumn('actions', $this->getTableButtons())
            ->rawColumns(['actions','published','product_id'])
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
