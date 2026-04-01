<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        title: 'BarengSaya API',
        version: '1.0',
        description: 'API documentation for BarengSaya application'
    ),
    servers: [
        new OA\Server(url: 'http://localhost:8000', description: 'Local server')
    ]
)]
class OpenApiSpec
{
    // This class serves as the root OpenAPI specification
}