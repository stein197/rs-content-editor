<?php

use App\Http\Request;
use App\Http\Response;
use App\Http\Status;
use App\Controller\HtmlStatic;

return fn (Request $request, Response $response): Response => (new HtmlStatic())->handle($request, $response->status(Status::NOT_FOUND));
