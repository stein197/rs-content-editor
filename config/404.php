<?php

use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Controller\Index;
use function App\container;

return fn (Request $request, Response $response): Response => (container()->make(Index::class))->handle($request, $response->status(Status::NOT_FOUND));
