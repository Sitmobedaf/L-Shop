<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Frontend\Profile;

use App\Handlers\Frontend\Profile\Cart\PaginationHandler;
use App\Http\Controllers\Controller;
use function App\permission_middleware;
use App\Services\Auth\Permissions;
use App\Services\Infrastructure\Response\JsonResponse;
use App\Services\Infrastructure\Response\Status;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(permission_middleware(Permissions::PROFILE_GAME_CART_ACCESS));
    }

    public function pagination(Request $request, PaginationHandler $handler): JsonResponse
    {
        $page = is_numeric($request->get('page')) ? (int)$request->get('page') : 1;
        $server = is_numeric($request->get('server')) ? (int)$request->get('server') : null;
        $orderBy = $request->get('order_by');
        $descending = (bool)$request->get('descending');

        $dto = $handler->handle($page, $server, $orderBy, $descending);

        return new JsonResponse(Status::SUCCESS, [
            'paginator' => $dto->getPaginator(),
            'items' => $dto->getItems(),
            'servers' => $dto->getServers()
        ]);
    }
}