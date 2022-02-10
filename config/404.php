<?php

use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Controller\Index;

return fn (Request $request, Response $response): Response => (new Index())->handle($request, $response->status(Status::NOT_FOUND));
