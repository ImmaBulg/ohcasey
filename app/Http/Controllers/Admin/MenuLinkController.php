<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuLink;
use App\Models\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Class OrderStatusController
 * @package App\Http\Controllers
 */
class MenuLinkController extends Controller
{
    /**
     * @var array
     */
    protected $rules = [
        'name'      => 'required',
        'sort'      => 'numeric',
        'parent_id' => 'numeric',
    ];

    /**
     * @param Request $request
     * @return \View|LengthAwarePaginator
     */
    public function index(Request $request)
    {
        if (! $request->isXmlHttpRequest() && ! $request->wantsJson()) {
            return app()->call([$this, 'showView']);
        }

        $perPage = $request->get('per_page', 50);

        $query = MenuLink::query()->with([
            'parent.parent.parent'
        ]);

        $links = $query->paginate($perPage);

        if ($links->currentPage() > $links->lastPage()) {
            $links = $query->paginate($perPage, ['*'], 'page', $links->lastPage());
        }

        return $links;
    }

    /**
     * @param Request $request
     * @return MenuLink
     */
    public function store(Request $request)
    {
        $menuLink = new MenuLink();
        $this->validate($request, $this->rules);
        $menuLink->fill($request->all())->save();
        return $menuLink;
    }

    /**
     * @param $id
     * @return array
     */
    public function edit($id)
    {
        return ['form' => MenuLink::findOrFail($id)];
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $menuLink = MenuLink::findOrFail($id);
        $this->validate($request, $this->rules);
        $menuLink->fill($request->all())->save();
        return $menuLink;
    }

    /**
     * @param  int $id
     * @return MenuLink
     */
    public function destroy($id)
    {
        $menuLink = MenuLink::findOrFail($id);
        $menuLink->delete();
        return $menuLink;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showView()
    {
        return view('admin.vue_menu_links');
    }
}
