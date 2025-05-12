<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\ZipCodeFinder;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function __construct(
        public CustomerRepositoryInterface $customerRepository,
        public ZipCodeFinder               $zipCodeFinder,
    ) {}

    public function index(Request $request)
    {
        $params = $request->all();

        $cachedParamns = Cache::get('customersListParams');

        if ($cachedParamns === $request->getQueryString() && Cache::has('customersListResult')) {
            return Cache::get('customersListResult');
        }

        $query = DB::table('customers', 'c')
            ->select([
                "c.id",
                "c.full_name",
                "c.cpf",
                "c.email",
                "c.phone",
                'a.street',
                'a.number',
                'a.complement',
                'a.neighborhood',
                'a.state',
                'a.city',
                'zipcode',
                "c.created_at",
                "c.updated_at",
            ])
            ->join('addresses as a', 'c.address_id', '=', 'a.id');

        if (isset($params['full_name']))
            $query->where('c.full_name', 'like', "%{$params['full_name']}%");

        if (isset($params['cpf']))
            $query->where('c.cpf', $params['cpf']);

        if (isset($params['zipcode']))
            $query->where('a.zipcode', $params['zipcode']);

        $result = $query->paginate($params['per_page'] ?? 10, ['*'], 'page', $params['page'] ?? 1);
        $inFiveMin = now()->addMinutes(5);

        Cache::put('customersListResult', $result, $inFiveMin);
        Cache::put('customersListParams', $request->getQueryString(), $inFiveMin);

        return $result;
    }

    public function store(SaveCustomerRequest $request): JsonResponse
    {
        $zipCode = $request->address['zipcode'];

        if (Cache::get('lastZipCodeConsulted') !== $zipCode && !$this->zipCodeFinder->execute($zipCode)) {
            return response()->json(
                ['message' => "The zip code given does not exist!"],
                Response::HTTP_BAD_REQUEST
            );
        }

        Cache::put('lastZipCodeConsulted', $zipCode, now()->addMinutes(10));

        $resp = $this->customerRepository->create($request->all());
        return response()->json($resp, Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $resp = $this->customerRepository->get($id);
        return response()->json($resp, Response::HTTP_OK);
    }

    public function update(SaveCustomerRequest $request, int $id)
    {
        $zipCode = $request->address['zipcode'];

        if (Cache::get('lastZipCodeConsulted') !== $zipCode && !$this->zipCodeFinder->execute($zipCode)) {
            return response()->json(
                ['message' => "The zip code given does not exist!"],
                Response::HTTP_BAD_REQUEST
            );
        }

        Cache::put('lastZipCodeConsulted', $zipCode, now()->addMinutes(10));

        $resp = $this->customerRepository->edit($id, $request->all());
        return response()->json($resp, Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $this->customerRepository->delete($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
