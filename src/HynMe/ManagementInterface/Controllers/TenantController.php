<?php namespace HynMe\ManagementInterface\Controllers;

use HynMe\Framework\Controllers\AbstractController;
use Config, Response, Input;
use HynMe\MultiTenant\Contracts\TenantRepositoryContract;
use Illuminate\Http\Request;

class TenantController extends AbstractController
{
    /**
     * @var TenantRepositoryContract
     */
    protected $tenant;

    public function __construct(TenantRepositoryContract $tenant)
    {
        $this->tenant = $tenant;

        $this->view_namespace = Config::get('management-interface.views-namespace');
    }

    public function index(Request $request)
    {
        $this->setViewVariable('section_title', trans_choice('management-interface::website.website',2));
        $this->setViewVariable('websites', $this->tenant->paginated());

        // form request
//        $this->catchFormRequest($request, $this->website->newInstance('website'), new WebsiteValidator);

        return view("{$this->view_namespace}::website.index");
    }

    /**
     * Ajax results
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajax()
    {
        $query = Input::get('query');


        $tenants = $this->tenant->queryBuilder('tenant')->where('name', 'ilike', "%{$query}%")->take(10)->lists('name', 'id');
        return Response::json( $tenants);
    }
}